<?php

declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\DataTransferObjects\Groups\GroupData;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AcceptGroupInviteController extends Controller
{
    public function __invoke(string $code): RedirectResponse|Response
    {
        $group = Group::query()
            ->where('invite_code', $code)
            ->with('media')
            ->withCount('members')
            ->firstOrFail();

        if (! Auth::check()) {
            session(['pending_invite_code' => $code]);

            return to_route('register')
                ->with('message', 'Create an account to join '.$group->name.'.');
        }

        $user = Auth::user();
        $user->loadMissing(['media', 'groups']);

        if (! $user->onboarded_at) {
            session(['pending_invite_code' => $code]);

            return to_route($user->onboardingStep()->routeName());
        }

        if ($group->hasMember($user)) {
            session(['active_group_id' => $group->id]);

            return to_route('groups.show', $group)
                ->with('message', 'You are already a member of this group.');
        }

        return Inertia::render('Groups/ConfirmInvite', [
            'group' => GroupData::fromModel($group),
            'code'  => $code,
        ]);
    }
}
