<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $fillable = [
        'name', 'university_id', 'level', 'duration', 'tuition_fee',
        'requirements', 'description', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
