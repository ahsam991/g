<?php

namespace App\Http\Requests\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->string('email'))->first();

        if ($user && $user->isLocked()) {
            $this->recordAttempt($user, false, 'locked');

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $user->locked_until->diffInSeconds(now()),
                ]),
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            $user?->registerFailedLogin();
            $this->recordAttempt($user, false, 'bad-credentials');

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $authenticated = Auth::user();

        // A suspended account must not get a session, even with the right password.
        if (! $authenticated->isActive()) {
            Auth::logout();
            $this->recordAttempt($authenticated, false, 'inactive');

            throw ValidationException::withMessages([
                'email' => 'This account is not available. Contact an administrator.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // With two-factor on, the password alone must not create a session.
        if ($authenticated->hasTwoFactorEnabled()) {
            Auth::logout();

            $this->session()->put('two_factor_user_id', $authenticated->id);
            $this->session()->put('two_factor_remember', $this->boolean('remember'));

            $this->recordAttempt($authenticated, false, 'awaiting-2fa');

            return;
        }

        $authenticated->registerSuccessfulLogin();
        $this->recordAttempt($authenticated, true, null);
    }

    /** Writes one row per sign-in attempt, successful or not. */
    protected function recordAttempt(?User $user, bool $successful, ?string $reason): void
    {
        LoginHistory::create([
            'user_id' => $user?->id,
            'email' => (string) $this->string('email'),
            'successful' => $successful,
            'ip_address' => $this->ip(),
            'user_agent' => substr((string) $this->userAgent(), 0, 512),
            'reason' => $reason,
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
