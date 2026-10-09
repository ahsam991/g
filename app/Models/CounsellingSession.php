<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounsellingSession extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'user_id', 'held_at', 'channel', 'summary', 'next_action'];

    protected function casts(): array
    {
        return [
            'held_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
