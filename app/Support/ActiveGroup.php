<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ActiveGroup
{
    public static function id(?Request $request = null, ?User $user = null): ?string
    {
        $request ??= request();
        $user    ??= $request->user();

        if (! $user) {
            return null;
        }

        $groupId = $request->session()->get('active_group_id');

        if (! $groupId) {
            return null;
        }

        $group = Group::query()->find($groupId);

        if (! $group instanceof Group || ! $group->hasMember($user)) {
            $request->session()->forget('active_group_id');

            return null;
        }

        return (string) $groupId;
    }

    public static function set(Group $group): void
    {
        session(['active_group_id' => $group->id]);
    }

    public static function clear(): void
    {
        session()->forget('active_group_id');
    }

    /** @return Collection<int, string> */
    public static function userGroupIds(User $user): Collection
    {
        return $user->groups->pluck('id');
    }
}
