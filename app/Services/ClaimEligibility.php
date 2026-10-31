<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ClaimDeniedReason;
use App\Enums\KeyFeedback;
use App\Models\Group;
use App\Models\Key;
use App\Models\User;
use App\Policies\KeyPolicy;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Auth\Access\Gate;

class ClaimEligibility
{
    public function __construct(
        private Gate $gate,
        private KeyPolicy $policy,
    ) {}

    /**
     * @return array{
     *     view: bool,
     *     claim: bool,
     *     delete: bool,
     *     feedback: bool,
     *     claimDeniedReason: ?ClaimDeniedReason,
     *     cooldownEndsAt: ?string,
     *     ownedKeyId: ?string
     * }
     */
    public function for(Key $key, ?User $user): array
    {
        if ($user === null) {
            return [
                'view'              => false,
                'claim'             => false,
                'delete'            => false,
                'feedback'          => false,
                'claimDeniedReason' => null,
                'cooldownEndsAt'    => null,
                'ownedKeyId'        => null,
            ];
        }

        $claimDeniedReason = $this->policy->claimDeniedReason($user, $key);

        $cooldownEndsAt = null;
        $ownedKeyId     = null;

        if ($claimDeniedReason === ClaimDeniedReason::CooldownActive) {
            $group = $key->group;
            if ($group instanceof Group) {
                $cooldownEndsAt = $this->policy->cooldownEndsAt($user, $group)?->toIso8601String();
            }
        }

        if ($claimDeniedReason === ClaimDeniedReason::AlreadyOwnsGame) {
            $ownedKeyId = Key::query()
                ->where('game_id', $key->game_id)
                ->where('platform_id', $key->platform_id)
                ->where('owned_user_id', $user->id)
                ->where(fn ($q) => $q->whereNull('feedback')->orWhere('feedback', '!=', KeyFeedback::DidNotWork))
                ->value('id');
        }

        return [
            'view'              => $this->gate->forUser($user)->allows('view', $key),
            'claim'             => ! $claimDeniedReason instanceof ClaimDeniedReason,
            'delete'            => $this->gate->forUser($user)->allows('delete', $key),
            'feedback'          => $this->gate->forUser($user)->allows('feedback', $key),
            'claimDeniedReason' => $claimDeniedReason,
            'cooldownEndsAt'    => $cooldownEndsAt,
            'ownedKeyId'        => $ownedKeyId !== null ? (string) $ownedKeyId : null,
        ];
    }

    public function cooldownEndsAt(User $user, Group $group): ?CarbonInterface
    {
        return $this->policy->cooldownEndsAt($user, $group);
    }
}
