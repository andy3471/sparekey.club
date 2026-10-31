<?php

declare(strict_types=1);

use App\Enums\GroupRole;
use App\Models\Group;
use App\Models\User;

it('redirects users needing credentials to onboarding', function (): void {
    $user = User::factory()->needsOnboarding()->create();

    $this->actingAs($user)
        ->get('/games')
        ->assertRedirect(route('onboarding.credentials'));
});

it('lets a social user set credentials then join a group', function (): void {
    $user = User::factory()->needsOnboarding()->create([
        'name' => 'Social User',
    ]);

    $this->actingAs($user)
        ->post('/onboarding/credentials', [
            'email'                 => 'real@example.com',
            'password'              => 'password-password',
            'password_confirmation' => 'password-password',
        ])
        ->assertRedirect(route('onboarding.group'));

    expect($user->fresh()->email)->toBe('real@example.com')
        ->and($user->fresh()->onboarded_at)->toBeNull();

    $this->actingAs($user->fresh())
        ->post('/onboarding/group/skip')
        ->assertRedirect();

    expect($user->fresh()->onboarded_at)->not->toBeNull();
});

it('auto-joins a pending invite after setting credentials', function (): void {
    $owner = User::factory()->create();
    $group = Group::factory()->private()->create(['owner_id' => $owner->id]);
    $group->addMember($owner, GroupRole::Owner);

    $user = User::factory()->needsOnboarding()->create();

    $this->actingAs($user)
        ->withSession(['pending_invite_code' => $group->invite_code])
        ->post('/onboarding/credentials', [
            'email'                 => 'joined@example.com',
            'password'              => 'password-password',
            'password_confirmation' => 'password-password',
        ])
        ->assertRedirect(route('groups.show', $group));

    expect($group->fresh()->hasMember($user->fresh()))->toBeTrue()
        ->and($user->fresh()->onboarded_at)->not->toBeNull();
});
