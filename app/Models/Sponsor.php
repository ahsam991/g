<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sponsor extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'name', 'relationship', 'occupation', 'annual_income', 'income_currency', 'funding_type', 'evidence_notes'];

    protected function casts(): array
    {
        return [
            'annual_income' => 'decimal:2',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
