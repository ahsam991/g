<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorSetupController extends Controller
{
    public function __construct(private readonly TwoFactorService $twoFactor) {}

    /** Shows the secret to enrol. Held in the session until confirmed. */
    public function show(Request $request): View
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return view('auth.two-factor-enabled', [
                'recoveryCodes' => session('recovery_codes', []),
            ]);
        }

        $secret = $request->session()->get('two_factor_pending_secret')
            ?? $this->twoFactor->generateSecret();

        $request->session()->put('two_factor_pending_secret', $secret);

        return view('auth.two-factor-setup', [
            'secret' => $secret,
            'uri' => $this->twoFactor->provisioningUri($user, $secret),
        ]);
    }

    /** Confirms enrolment and issues the recovery codes once. */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $secret = $request->session()->get('two_factor_pending_secret');

        if (! $secret || ! $this->twoFactor->verify($secret, $request->string('code'))) {
            throw ValidationException::withMessages([
                'code' => 'That code is not valid. Check the clock on your phone and try again.',
            ]);
        }

        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $codes = $user->generateRecoveryCodes();

        $request->session()->forget('two_factor_pending_secret');

        return redirect()->route('two-factor.show')->with('recovery_codes', $codes);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('profile.edit')->with('status', 'Two-factor authentication turned off.');
    }
}
