<?php

declare(strict_types=1);

use App\Enums\GroupRole;
use App\Models\Game;
use App\Models\Group;
use App\Models\Key;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('lets a user store a key', function (): void {
    $user     = User::factory()->create();
    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);
    $group    = Group::factory()->create(['owner_id' => $user->id]);
    $group->addMember($user, GroupRole::Owner);

    $this->actingAs($user)->post('/keys', [
        'platform_id' => $platform->id,
        'key'         => 'ABCD-EFGH-IJKL',
        'igdb_id'     => (string) $game->igdb_id,
        'group_id'    => $group->id,
    ])->assertRedirect();

    $key = Key::query()
        ->where('platform_id', $platform->id)
        ->where('created_user_id', $user->id)
        ->where('group_id', $group->id)
        ->first();

    expect($key)->not->toBeNull()
        ->and($key->key)->toBe('ABCD-EFGH-IJKL');
});

it('stores a key for a new igdb game when id arrives as a string', function (): void {
    Http::fake([
        'id.twitch.tv/*' => Http::response([
            'access_token' => 'test-token',
            'expires_in'   => 3600,
        ]),
        'api.igdb.com/*' => Http::response([
            [
                'id'          => 1942,
                'name'        => 'Fake Game',
                'summary'     => 'A test game',
                'parent_game' => null,
            ],
        ]),
    ]);

    $user     = User::factory()->create();
    $platform = Platform::create(['name' => 'Steam']);
    $group    = Group::factory()->create(['owner_id' => $user->id]);
    $group->addMember($user, GroupRole::Owner);

    $this->actingAs($user)->post('/keys', [
        'platform_id' => $platform->id,
        'key'         => 'NEW-IGDB-KEY-1',
        'igdb_id'     => '1942',
        'group_id'    => $group->id,
    ])->assertRedirect();

    $game = Game::query()->where('igdb_id', 1942)->first();
    $key  = Key::query()
        ->where('platform_id', $platform->id)
        ->where('created_user_id', $user->id)
        ->where('group_id', $group->id)
        ->first();

    expect($game)->not->toBeNull()
        ->and($key)->not->toBeNull()
        ->and($key->game_id)->toBe($game->id)
        ->and($key->key)->toBe('NEW-IGDB-KEY-1');
});

it('lets a group member view a key', function (): void {
    $owner  = User::factory()->create();
    $member = User::factory()->create();
    $group  = Group::factory()->create(['owner_id' => $owner->id]);
    $group->addMember($owner, GroupRole::Owner);
    $group->addMember($member, GroupRole::Member);

    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'TEST-KEY-1234',
        'created_user_id' => $owner->id,
        'group_id'        => $group->id,
    ]);

    $this->actingAs($member)
        ->get("/keys/{$key->id}")
        ->assertOk();
});

it('prevents a non-member from viewing a group key', function (): void {
    $owner    = User::factory()->create();
    $outsider = User::factory()->create();
    $group    = Group::factory()->create(['owner_id' => $owner->id]);
    $group->addMember($owner, GroupRole::Owner);

    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'TEST-KEY-5678',
        'created_user_id' => $owner->id,
        'group_id'        => $group->id,
    ]);

    $this->actingAs($outsider)
        ->get("/keys/{$key->id}")
        ->assertForbidden();
});

it('lets a member claim a key', function (): void {
    $owner   = User::factory()->create();
    $claimer = User::factory()->create();
    $group   = Group::factory()->create(['owner_id' => $owner->id]);
    $group->addMember($owner, GroupRole::Owner);
    $group->addMember($claimer, GroupRole::Member);

    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'CLAIM-ME-1234',
        'created_user_id' => $owner->id,
        'group_id'        => $group->id,
    ]);

    $this->actingAs($claimer)
        ->post("/keys/{$key->id}/claim")
        ->assertRedirect();

    expect($key->fresh()->owned_user_id)->toBe($claimer->id);
});

it('redirects guests away from key pages', function (): void {
    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $platform = Platform::create(['name' => 'Steam']);

    $key = Key::create([
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'key'             => 'GUEST-KEY-1234',
        'created_user_id' => User::factory()->create()->id,
    ]);

    $this->get("/keys/{$key->id}")
        ->assertRedirect('/login');
});
