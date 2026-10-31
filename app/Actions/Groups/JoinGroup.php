<?php

declare(strict_types=1);

namespace App\Actions\Groups;

use App\Enums\GroupRole;
use App\Models\Group;
use App\Models\User;
use App\Support\ActiveGroup;

class JoinGroup
{
    public function handle(Group $group, User $user, GroupRole $role = GroupRole::Member, bool $completeOnboarding = false): void
    {
        if (! $group->hasMember($user)) {
            $group->addMember($user, $role);
        }

        ActiveGroup::set($group);

        if ($completeOnboarding && ! $user->onboarded_at) {
            $user->update(['onboarded_at' => now()]);
        }
    }
}
