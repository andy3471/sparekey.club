<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Game;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;

class ExistingIgdbId implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_numeric($value)) {
            $fail('Game not found');

            return;
        }

        $igdbId = (int) $value;

        if (Game::query()->where('igdb_id', $igdbId)->exists()) {
            return;
        }

        if (! IgdbGame::find($igdbId)) {
            $fail('Game not found');
        }
    }
}
