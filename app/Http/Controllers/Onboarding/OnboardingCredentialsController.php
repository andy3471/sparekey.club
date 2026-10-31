<?php

declare(strict_types=1);

namespace App\Http\Controllers\Onboarding;

use App\Actions\Groups\JoinPendingInvite;
use App\Enums\OnboardingStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\StoreOnboardingCredentialsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingCredentialsController extends Controller
{
    public function create(Request $request, JoinPendingInvite $joinPendingInvite): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user->onboardingStep() !== OnboardingStep::SetCredentials) {
            return $this->redirectToCurrentStep($user);
        }

        return Inertia::render('Onboarding/SetCredentials', [
            'name'         => $user->name,
            'pendingGroup' => $joinPendingInvite->bannerData(),
        ]);
    }

    public function store(StoreOnboardingCredentialsRequest $request, JoinPendingInvite $joinPendingInvite): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->onboardingStep() === OnboardingStep::SetCredentials, 403);

        $validated = $request->validated();

        $user->update([
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (($redirect = $joinPendingInvite->consume($user)) instanceof RedirectResponse) {
            return $redirect;
        }

        return to_route('onboarding.group');
    }

    private function redirectToCurrentStep(\App\Models\User $user): RedirectResponse
    {
        $step = $user->onboardingStep();

        if ($step->isComplete()) {
            return to_route('games.index');
        }

        return to_route($step->routeName());
    }
}
