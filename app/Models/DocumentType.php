<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'is_required', 'max_size_kb',
        'allowed_mimes', 'sort_order', 'active',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'active' => 'boolean',
            'max_size_kb' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }

    public function allowedMimesArray(): array
    {
        return array_map('trim', explode(',', $this->allowed_mimes));
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }
}
