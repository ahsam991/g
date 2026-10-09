<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsUsable
{
    /**
     * Pushes a signed-in user out if their account was suspended or locked
     * after the session began, and forces a password change when one is due.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $user->isActive() || $user->isLocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'This account is not available. Contact an administrator.',
            ]);
        }

        if ($user->must_change_password && ! $request->routeIs('password.*', 'logout', 'profile.*')) {
            return redirect()->route('password.change')
                ->with('status', 'Please choose a new password before continuing.');
        }

        return $next($request);
    }
}
