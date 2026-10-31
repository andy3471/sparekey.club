<?php

declare(strict_types=1);

use App\Enums\GroupRole;
use App\Models\Game;
use App\Models\Group;
use App\Models\Key;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Support\Facades\DB;

it('omits invite code and discord webhook from public group show for non-admins', function (): void {
    $owner  = User::factory()->create();
    $viewer = User::factory()->create();
    $group  = Group::factory()->create([
        'owner_id'            => $owner->id,
        'is_public'           => true,
        'discord_webhook_url' => 'https://discord.com/api/webhooks/123/abc',
    ]);
    $group->addMember($owner, GroupRole::Owner);

    $this->actingAs($viewer)
        ->get("/groups/{$group->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Groups/Show')
            ->where('group.invite_code', null)
            ->where('group.discord_webhook_url', null)
        );
});

it('includes invite code and webhook for group owners on edit', function (): void {
    $owner = User::factory()->create();
    $group = Group::factory()->create([
        'owner_id'            => $owner->id,
        'discord_webhook_url' => 'https://discord.com/api/webhooks/123/abc',
    ]);
    $group->addMember($owner, GroupRole::Owner);

    $this->actingAs($owner)
        ->get("/groups/{$group->id}/edit")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Groups/Edit')
            ->where('group.invite_code', $group->invite_code)
            ->where('group.discord_webhook_url', 'https://discord.com/api/webhooks/123/abc')
        );
});

it('hides email on other users public profiles', function (): void {
    $viewer = User::factory()->create();
    $other  = User::factory()->create(['email' => 'secret@example.com']);

    $this->actingAs($viewer)
        ->get("/users/{$other->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Users/Show')
            ->missing('user.email')
        );
});

it('hides email on key sharer props', function (): void {
    $owner = User::factory()->create(['email' => 'owner@example.com']);
    $owner->load('media');

    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'LEAK-TEST-KEY',
        'created_user_id' => $owner->id,
    ]);
    $key->setRelation('createdUser', $owner);
    $key->setRelation('claimedUser', null);
    $key->setRelation('platform', $platform);
    $key->unsetRelation('game');
    $key->unsetRelation('group');

    $this->actingAs(User::factory()->create());

    $data = App\DataTransferObjects\Keys\KeyData::fromModel($key)->toArray();

    expect($data['createdUser'] ?? null)->not->toBeNull()
        ->and($data['createdUser'])->not->toHaveKey('email')
        ->and($data['createdUser'])->not->toHaveKey('is_admin');
});

it('only allows one successful claim when claimed twice', function (): void {
    $owner    = User::factory()->create();
    $claimerA = User::factory()->create();
    $claimerB = User::factory()->create();
    $group    = Group::factory()->create(['owner_id' => $owner->id]);
    $group->addMember($owner, GroupRole::Owner);
    $group->addMember($claimerA, GroupRole::Member);
    $group->addMember($claimerB, GroupRole::Member);

    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'RACE-KEY-1234',
        'created_user_id' => $owner->id,
        'group_id'        => $group->id,
    ]);

    $this->actingAs($claimerA)
        ->post("/keys/{$key->id}/claim")
        ->assertRedirect()
        ->assertSessionHas('message');

    $this->actingAs($claimerB)
        ->post("/keys/{$key->id}/claim")
        ->assertForbidden();

    expect($key->fresh()->owned_user_id)->toBe($claimerA->id);
});

it('stores key codes encrypted at rest', function (): void {
    $user     = User::factory()->create();
    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);
    $group    = Group::factory()->create(['owner_id' => $user->id]);
    $group->addMember($user, GroupRole::Owner);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'PLAIN-TEXT-KEY',
        'created_user_id' => $user->id,
        'group_id'        => $group->id,
    ]);

    $raw = DB::table('keys')->where('id', $key->id)->value('key');

    expect($raw)->not->toBe('PLAIN-TEXT-KEY')
        ->and($key->fresh()->key)->toBe('PLAIN-TEXT-KEY');
});
