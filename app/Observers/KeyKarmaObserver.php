<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\KeyFeedback;
use App\Models\Key;
use App\Services\KarmaService;

class KeyKarmaObserver
{
    public function __construct(private KarmaService $karmaService) {}

    public function updated(Key $key): void
    {
        if ($key->wasChanged('owned_user_id') && $key->owned_user_id !== null) {
            if ($sharer = $key->createdUser) {
                $this->karmaService->adjustKarma($sharer, 1);
            }

            if ($claimer = $key->claimedUser) {
                $this->karmaService->adjustKarma($claimer, -1);
            }
        }

        if (! $key->wasChanged('feedback')) {
            return;
        }

        $sharer = $key->createdUser;

        if (! $sharer) {
            return;
        }

        $rawOriginal = $key->getRawOriginal('feedback');
        $from        = is_string($rawOriginal) ? KeyFeedback::tryFrom($rawOriginal) : null;
        $delta       = KeyFeedback::sharerKarmaDeltaBetween($from, $key->feedback);

        if ($delta !== 0) {
            $this->karmaService->adjustKarma($sharer, $delta);
        }
    }
}
