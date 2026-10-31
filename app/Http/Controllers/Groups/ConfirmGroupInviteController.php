<?php

declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Actions\Groups\JoinGroup;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConfirmGroupInviteController extends Controller
{
    public function __invoke(Request $request, string $code, JoinGroup $joinGroup): RedirectResponse
    {
        $group = Group::query()->where('invite_code', $code)->firstOrFail();
        $user  = $request->user();

        if (! $user->onboarded_at) {
            session(['pending_invite_code' => $code]);

            return to_route($user->onboardingStep()->routeName());
        }

        if ($group->hasMember($user)) {
            $joinGroup->handle($group, $user);

            return to_route('groups.show', $group)
                ->with('message', 'You are already a member of this group.');
        }

        $joinGroup->handle($group, $user);
        session()->forget('pending_invite_code');

        return to_route('groups.show', $group)
            ->with('message', 'You have joined the group.');
    }
}
