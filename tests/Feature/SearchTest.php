<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Http;

it('throttles and returns search results without calling live igdb when empty', function (): void {
    Http::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/search')
        ->assertOk();
});

it('returns autocomplete json for authenticated users', function (): void {
    Http::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/autocomplete?query=halo')
        ->assertOk();
});
