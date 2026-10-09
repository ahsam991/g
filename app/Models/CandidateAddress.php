<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateAddress extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'type', 'line_1', 'line_2', 'city', 'state', 'postcode', 'country'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
