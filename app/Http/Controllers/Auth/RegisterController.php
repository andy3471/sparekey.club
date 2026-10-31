<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Groups\JoinPendingInvite;
use App\Enums\LinkedAccountProvider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/games';

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('throttle:10,1')->only('register');
    }

    public function showRegistrationForm(JoinPendingInvite $joinPendingInvite): Response
    {
        return Inertia::render('Auth/Register', [
            'providers'    => LinkedAccountProvider::enabledForFrontend(),
            'pendingGroup' => $joinPendingInvite->bannerData(),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:50'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
