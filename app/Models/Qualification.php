<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qualification extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'level', 'institution', 'subject', 'country', 'grade', 'gpa', 'gpa_scale', 'start_year', 'end_year'];

    protected function casts(): array
    {
        return [
            'gpa' => 'decimal:2',
            'gpa_scale' => 'decimal:2',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
