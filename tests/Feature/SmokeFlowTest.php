<?php

declare(strict_types=1);

use App\Enums\GroupRole;
use App\Models\Game;
use App\Models\Group;
use App\Models\Key;
use App\Models\Platform;
use App\Models\User;

it('covers the core share and claim happy path', function (): void {
    $owner    = User::factory()->create();
    $claimer  = User::factory()->create();
    $platform = Platform::create(['name' => 'Steam']);
    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);

    $this->actingAs($owner)->post('/groups', [
        'name'        => 'Smoke Group',
        'description' => 'Smoke test group',
        'is_public'   => true,
    ])->assertRedirect();

    $group = Group::query()->where('name', 'Smoke Group')->firstOrFail();

    $this->actingAs($claimer)
        ->post("/groups/{$group->id}/join")
        ->assertRedirect();

    expect($group->fresh()->hasMember($claimer->fresh()))->toBeTrue();

    $this->actingAs($owner->fresh())->post('/keys', [
        'platform_id' => $platform->id,
        'key'         => 'SMOKE-KEY-1234',
        'igdb_id'     => $game->igdb_id,
        'group_id'    => $group->id,
    ])->assertRedirect();

    $key = Key::query()->where('group_id', $group->id)->firstOrFail();

    $this->actingAs($claimer->fresh())
        ->post("/keys/{$key->id}/claim")
        ->assertRedirect()
        ->assertSessionHas('message');

    expect($key->fresh()->owned_user_id)->toBe($claimer->id)
        ->and($key->fresh()->key)->toBe('SMOKE-KEY-1234');
});

it('covers invite confirm then post join', function (): void {
    $owner   = User::factory()->create();
    $invitee = User::factory()->create();
    $group   = Group::factory()->private()->create(['owner_id' => $owner->id]);
    $group->addMember($owner, GroupRole::Owner);

    $this->actingAs($invitee)
        ->get("/invite/{$group->invite_code}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Groups/ConfirmInvite'));

    $this->actingAs($invitee)
        ->post("/invite/{$group->invite_code}")
        ->assertRedirect(route('groups.show', $group));

    expect($group->fresh()->hasMember($invitee->fresh()))->toBeTrue();
});
