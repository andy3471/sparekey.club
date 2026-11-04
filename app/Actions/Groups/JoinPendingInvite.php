<?php

declare(strict_types=1);

namespace App\Actions\Groups;

use App\DataTransferObjects\Shared\PendingGroupData;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class JoinPendingInvite
{
    public function __construct(private JoinGroup $joinGroup) {}

    public function bannerData(): ?PendingGroupData
    {
        $code = session('pending_invite_code');

        if (! $code) {
            return null;
        }

        $group = Group::query()->where('invite_code', $code)->first();

        if (! $group) {
            return null;
        }

        $group->loadMissing('media');

        return new PendingGroupData(
            name: $group->name,
            avatar: $group->avatar_url,
        );
    }

    public function consume(User $user): ?RedirectResponse
    {
        $code = session()->pull('pending_invite_code');

        if (! $code) {
            return null;
        }

        $group = Group::query()->where('invite_code', $code)->first();

        if (! $group) {
            return null;
        }

        $this->joinGroup->handle($group, $user, completeOnboarding: true);

        return to_route('groups.show', $group)
            ->with('message', 'You\'re in '.$group->name.'.');
    }
}
