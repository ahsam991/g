<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Auditable, HasFactory, Notifiable;

    /** Failed attempts allowed before the account locks. */
    public const MAX_FAILED_LOGINS = 5;

    /** How long the account stays locked, in minutes. */
    public const LOCKOUT_MINUTES = 15;

    /**
     * Defaults applied to new instances. The database carries the same
     * defaults, but a freshly created model would not know them, and the
     * account guard reads status on every request.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'active',
        'must_change_password' => false,
        'failed_logins' => 0,
    ];

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role_id', 'team_id', 'branch_id', 'candidate_id',
        'status', 'must_change_password',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /** Students this consultant is responsible for. */
    public function assignedCandidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'assigned_to');
    }

    /** Leads this consultant is responsible for. */
    public function assignedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    /** The student record behind a portal account. */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    // Roles -------------------------------------------------------------

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function isConsultant(): bool
    {
        return $this->hasRole(Role::CONSULTANT);
    }

    public function isStudent(): bool
    {
        return $this->hasRole(Role::STUDENT);
    }

    /**
     * Admin implicitly holds every permission. Everyone else is checked against
     * the permissions attached to their role.
     */
    public function hasPermission(string $slug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! $this->role) {
            return false;
        }

        return $this->role
            ->permissions()
            ->where('slug', $slug)
            ->exists();
    }

    // Account state -----------------------------------------------------

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    public function registerFailedLogin(): void
    {
        $this->increment('failed_logins');

        if ($this->failed_logins >= self::MAX_FAILED_LOGINS) {
            $this->forceFill([
                'locked_until' => Carbon::now()->addMinutes(self::LOCKOUT_MINUTES),
                'failed_logins' => 0,
            ])->save();
        }
    }

    public function registerSuccessfulLogin(): void
    {
        $this->forceFill([
            'failed_logins' => 0,
            'locked_until' => null,
            'last_login_at' => Carbon::now(),
        ])->save();
    }

    // Two-factor --------------------------------------------------------

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_secret !== null && $this->two_factor_confirmed_at !== null;
    }

    /**
     * Generates fresh recovery codes, stores them hashed, and returns the
     * plain codes so they can be shown to the user exactly once.
     *
     * @return array<int, string>
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $plain = collect(range(1, $count))
            ->map(fn () => Str::upper(Str::random(5).'-'.Str::random(5)))
            ->all();

        $this->forceFill([
            'two_factor_recovery_codes' => array_map(fn ($code) => Hash::make($code), $plain),
        ])->save();

        return $plain;
    }

    /** Consumes a recovery code. Each code works once. */
    public function useRecoveryCode(string $code): bool
    {
        $stored = $this->two_factor_recovery_codes ?? [];

        foreach ($stored as $index => $hash) {
            if (Hash::check($code, $hash)) {
                unset($stored[$index]);

                $this->forceFill([
                    'two_factor_recovery_codes' => array_values($stored),
                ])->save();

                return true;
            }
        }

        return false;
    }
}
