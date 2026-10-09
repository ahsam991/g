<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnglishTest extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'type', 'test_date', 'overall', 'listening', 'reading', 'writing', 'speaking', 'certificate_number'];

    protected function casts(): array
    {
        return [
            'test_date' => 'date',
            'overall' => 'decimal:2',
            'listening' => 'decimal:2',
            'reading' => 'decimal:2',
            'writing' => 'decimal:2',
            'speaking' => 'decimal:2',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
