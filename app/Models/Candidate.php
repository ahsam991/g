<?php

namespace App\Models;

use App\Support\Auditable;
use App\Support\ScopedToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A prospective or current student. Called "candidate" in the database and
 * code to match the spec and the GC-YYYY-0000 reference format; the interface
 * says "student".
 */
class Candidate extends Model
{
    use Auditable, HasFactory, ScopedToUser, SoftDeletes;

    /**
     * Weighting for the profile completeness figure. Adds to 100.
     * Documents are scored in the documents slice via countable uploads.
     */
    public const COMPLETION_WEIGHTS = [
        'personal' => 20,
        'contact' => 15,
        'academic' => 20,
        'english' => 10,
        'documents' => 35,
    ];

    protected $fillable = [
        'uid', 'first_name', 'last_name', 'email', 'phone', 'alt_phone',
        'date_of_birth', 'gender', 'nationality', 'country_of_residence',
        'passport_number', 'passport_expiry', 'marital_status',
        'preferred_destination', 'preferred_level', 'preferred_subject',
        'preferred_intake', 'budget_per_year', 'budget_currency',
        'assigned_to', 'branch_id', 'status', 'source', 'notes', 'user_id', 'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
            'archived_at' => 'datetime',
            'budget_per_year' => 'decimal:2',
        ];
    }

    // Visibility --------------------------------------------------------

    public function consultantColumn(): string
    {
        return 'assigned_to';
    }

    /** A student's own record is matched by primary key, not a foreign key. */
    public function candidateColumn(): ?string
    {
        return 'id';
    }

    // Relationships -----------------------------------------------------

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function portalUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CandidateAddress::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(Qualification::class);
    }

    public function englishTests(): HasMany
    {
        return $this->hasMany(EnglishTest::class);
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(Sponsor::class);
    }

    public function counsellingSessions(): HasMany
    {
        return $this->hasMany(CounsellingSession::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CandidateAssignment::class);
    }

    public function consents(): HasMany
    {
        return $this->hasMany(GdprConsent::class);
    }

    // Derived values ----------------------------------------------------

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    // Scopes ------------------------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Matches the reference, name, email, phone or passport. Anchored so the
     * indexes are used; a leading wildcard would force a full table scan.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('uid', 'like', "{$term}%")
                ->orWhere('first_name', 'like', "{$term}%")
                ->orWhere('last_name', 'like', "{$term}%")
                ->orWhere('email', 'like', "{$term}%")
                ->orWhere('phone', 'like', "{$term}%")
                ->orWhere('passport_number', 'like', "{$term}%");
        });
    }
}
