<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorChallengeController extends Controller
{
    public function __construct(private readonly TwoFactorService $twoFactor) {}

    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $userId = $request->session()->get('two_factor_user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $this->twoFactor->challenge($user, $request->string('code'))) {
            throw ValidationException::withMessages([
                'code' => 'That code is not valid.',
            ]);
        }

        $request->session()->forget('two_factor_user_id');

        Auth::login($user, (bool) $request->session()->pull('two_factor_remember', false));

        $request->session()->regenerate();
        $user->registerSuccessfulLogin();

        return redirect()->intended(route('dashboard'));
    }
}
