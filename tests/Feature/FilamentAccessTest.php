<?php

declare(strict_types=1);

use App\Models\User;

it('forbids non-admins from the filament panel', function (): void {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('allows admins to access the filament panel', function (): void {
    $user = User::factory()->create(['is_admin' => true]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});
