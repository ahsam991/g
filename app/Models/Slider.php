<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'cta_text', 'cta_link', 'cta2_text', 'cta2_link',
        'image', 'badge_text', 'sort_order', 'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order');
    }
}
