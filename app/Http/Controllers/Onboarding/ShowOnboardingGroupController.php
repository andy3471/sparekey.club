<?php

declare(strict_types=1);

namespace App\Http\Controllers\Onboarding;

use App\Actions\Groups\JoinPendingInvite;
use App\Enums\OnboardingStep;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowOnboardingGroupController extends Controller
{
    public function __invoke(Request $request, JoinPendingInvite $joinPendingInvite): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user->onboardingStep() === OnboardingStep::Complete) {
            return to_route('games.index');
        }

        if ($user->onboardingStep() === OnboardingStep::SetCredentials) {
            return to_route('onboarding.credentials');
        }

        if (($redirect = $joinPendingInvite->consume($user)) instanceof RedirectResponse) {
            return $redirect;
        }

        $publicGroups = Group::query()
            ->public()
            ->whereNotMember($user)
            ->withCount('members')
            ->with('media')
            ->orderByDesc('members_count')
            ->limit(12)
            ->get()
            ->map(fn (Group $group): array => [
                'id'           => $group->id,
                'name'         => $group->name,
                'slug'         => $group->slug,
                'description'  => $group->description,
                'member_count' => $group->members_count,
                'avatar'       => $group->avatar_url,
            ]);

        return Inertia::render('Onboarding/JoinGroup', [
            'publicGroups'     => $publicGroups,
            'joinedGroupCount' => $user->groups()->count(),
        ]);
    }
}
