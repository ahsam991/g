<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GdprConsent extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'consent_type', 'given', 'policy_version', 'ip_address', 'given_at'];

    protected function casts(): array
    {
        return [
            'given' => 'boolean',
            'given_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
