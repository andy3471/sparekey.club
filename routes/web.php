<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\HandleProviderCallbackController;
use App\Http\Controllers\Auth\RedirectToProviderController;
use App\Http\Controllers\Auth\UnlinkAccountController;
use App\Http\Controllers\Games\GameController;
use App\Http\Controllers\Groups\AcceptGroupInviteController;
use App\Http\Controllers\Groups\ConfirmGroupInviteController;
use App\Http\Controllers\Groups\GroupController;
use App\Http\Controllers\Groups\JoinGroupController;
use App\Http\Controllers\Groups\LeaveGroupController;
use App\Http\Controllers\Groups\RegenerateGroupInviteCodeController;
use App\Http\Controllers\Groups\RemoveGroupMemberController;
use App\Http\Controllers\Groups\SwitchGroupController;
use App\Http\Controllers\Keys\ClaimKeyController;
use App\Http\Controllers\Keys\KeyController;
use App\Http\Controllers\Keys\ListClaimedKeysController;
use App\Http\Controllers\Keys\ListSharedKeysController;
use App\Http\Controllers\Keys\SubmitKeyFeedbackController;
use App\Http\Controllers\Onboarding\OnboardingCredentialsController;
use App\Http\Controllers\Onboarding\ShowOnboardingGroupController;
use App\Http\Controllers\Onboarding\SkipOnboardingGroupController;
use App\Http\Controllers\Search\AutocompleteSearchController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\WelcomeController;

Route::get('/', WelcomeController::class)->name('welcome');

Auth::routes();

Route::get('auth/{provider}/redirect', RedirectToProviderController::class)
    ->name('auth.provider.redirect');

Route::get('auth/{provider}/callback', HandleProviderCallbackController::class)
    ->name('auth.provider.callback');

Route::get('invite/{code}', AcceptGroupInviteController::class)->name('groups.join-via-code');
Route::post('invite/{code}', ConfirmGroupInviteController::class)
    ->middleware(['auth', 'throttle:10,1'])
    ->name('groups.join-via-code.accept');

Route::middleware(['auth'])->prefix('onboarding')->name('onboarding.')->group(function (): void {
    Route::get('credentials', [OnboardingCredentialsController::class, 'create'])->name('credentials');
    Route::post('credentials', [OnboardingCredentialsController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('credentials.store');
    Route::get('group', ShowOnboardingGroupController::class)->name('group');
    Route::post('group/skip', SkipOnboardingGroupController::class)->name('group.skip');
});

Route::middleware(['auth', 'onboarding'])->group(function (): void {
    Route::delete('linked-accounts/{provider}', UnlinkAccountController::class)
        ->name('linked-accounts.destroy');

    Route::get('games', [GameController::class, 'index'])->name('games.index');
    Route::get('games/{igdb_id}', [GameController::class, 'show'])->name('games.show');

    Route::get('search', SearchController::class)
        ->middleware('throttle:30,1')
        ->name('search.index');

    Route::post('keys/{key}/claim', ClaimKeyController::class)
        ->middleware('throttle:10,1')
        ->name('keys.claim');
    Route::post('keys/{key}/feedback', SubmitKeyFeedbackController::class)->name('keys.feedback');
    Route::get('keys/claimed', ListClaimedKeysController::class)->name('keys.claimed.index');
    Route::get('keys/shared', ListSharedKeysController::class)->name('keys.shared.index');
    Route::get('keys/create', [KeyController::class, 'create'])->name('keys.create');
    Route::get('keys/{key}', [KeyController::class, 'show'])->name('keys.show');
    Route::post('keys', [KeyController::class, 'store'])->middleware('throttle:20,1')->name('keys.store');
    Route::delete('keys/{key}', [KeyController::class, 'destroy'])->name('keys.destroy');

    Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);

    Route::get('autocomplete', AutocompleteSearchController::class)
        ->middleware('throttle:30,1')
        ->name('autocomplete');

    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/join', JoinGroupController::class)->name('groups.join');
    Route::post('groups/{group}/leave', LeaveGroupController::class)->name('groups.leave');
    Route::delete('groups/{group}/members/{user}', RemoveGroupMemberController::class)->name('groups.members.remove');
    Route::post('groups/{group}/regenerate-invite-code', RegenerateGroupInviteCodeController::class)->name('groups.regenerate-invite-code');
    Route::post('groups/switch', SwitchGroupController::class)->name('groups.switch');
});
