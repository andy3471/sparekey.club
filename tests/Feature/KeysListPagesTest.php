<?php

declare(strict_types=1);

use App\Enums\GroupRole;
use App\Models\Game;
use App\Models\Group;
use App\Models\Key;
use App\Models\Platform;
use App\Models\User;

it('renders claimed keys with paginated scroll props', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('keys.claimed.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Keys/Claimed')
            ->has('keys.data')
            ->has('keys.current_page')
        );
});

it('renders shared keys with paginated scroll props', function (): void {
    $user     = User::factory()->create();
    $platform = Platform::create(['name' => 'Steam']);
    $game     = Game::create(['igdb_id' => fake()->unique()->randomNumber(5)]);
    $group    = Group::factory()->create(['owner_id' => $user->id]);
    $group->addMember($user, GroupRole::Owner);

    Key::factory()->create([
        'created_user_id' => $user->id,
        'game_id'         => $game->id,
        'platform_id'     => $platform->id,
        'group_id'        => $group->id,
    ]);

    $this->actingAs($user)
        ->get(route('keys.shared.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Keys/Shared')
            ->has('keys.data', 1)
            ->has('keys.current_page')
        );
});
