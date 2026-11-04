<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\DataTransferObjects\Groups\GroupData;
use App\DataTransferObjects\Shared\AuthData;
use App\DataTransferObjects\Shared\FlashData;
use App\DataTransferObjects\Users\UserData;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function handle(Request $request, Closure $next): Response
    {
        auth()->user()?->loadMissing(['media', 'groups']);

        return parent::handle($request, $next);
    }

    /** @return array<string, mixed> */
    public function share(Request $request): array
    {
        $user = auth()->user();

        return array_merge(parent::share($request), [
            'auth' => AuthData::from([
                'user' => $user ? UserData::fromModel($user) : null,
            ]),
            'flash' => FlashData::from([
                'message' => $request->session()->get('message'),
                'error'   => $request->session()->get('error'),
                'status'  => $request->session()->get('status'),
            ]),
            'activeGroup' => fn (): ?GroupData => $this->getActiveGroup($request),
            'userGroups'  => fn () => $user
                ? $user->groups()->withCount('members')->with('media')->get()->map(fn (Group $group): GroupData => GroupData::fromModel($group, $group->pivot->role))
                : [],
        ]);
    }

    private function getActiveGroup(Request $request): ?GroupData
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        $groupId = $request->session()->get('active_group_id');

        if (! $groupId) {
            return null;
        }

        $user->loadMissing('groups');
        $membership = $user->groups->firstWhere('id', $groupId);

        if (! $membership) {
            $request->session()->forget('active_group_id');

            return null;
        }

        $group = Group::with('media')->find($groupId);

        if (! $group) {
            $request->session()->forget('active_group_id');

            return null;
        }

        return GroupData::fromModel($group, $membership->pivot->role);
    }
}
