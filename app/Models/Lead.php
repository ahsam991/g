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

class Lead extends Model
{
    use Auditable, HasFactory, ScopedToUser, SoftDeletes;

    public const STATUSES = ['new', 'contacted', 'qualified', 'converted', 'lost'];

    protected $fillable = [
        'uid', 'first_name', 'last_name', 'email', 'phone', 'country',
        'preferred_destination', 'preferred_level', 'preferred_subject', 'notes',
        'status', 'lead_source_id', 'lead_campaign_id', 'assigned_to', 'branch_id',
        'enquiry_id', 'candidate_id', 'ip_address', 'is_duplicate',
        'contacted_at', 'converted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_duplicate' => 'boolean',
            'contacted_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }

    // -- Relationships --------------------------------------------------------

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(LeadCampaign::class, 'lead_campaign_id');
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(WebsiteEnquiry::class, 'enquiry_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    // -- ScopedToUser overrides -----------------------------------------------

    public static function consultantColumn(): string
    {
        return 'assigned_to';
    }

    public static function candidateColumn(): string
    {
        return 'id'; // students don't access leads
    }

    // -- Scopes ---------------------------------------------------------------

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('uid', 'like', "$term%")
                ->orWhere('first_name', 'like', "$term%")
                ->orWhere('last_name', 'like', "$term%")
                ->orWhere('email', 'like', "$term%")
                ->orWhere('phone', 'like', "%$term%");
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    // -- Helpers --------------------------------------------------------------

    public function isConverted(): bool
    {
        return $this->status === 'converted';
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
