<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title', 'icon_svg', 'short_description', 'description',
        'sort_order', 'active', 'featured',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'featured' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopeActive($q)
    {
        return $q->where('active', true)->orderBy('sort_order');
    }

    public function scopeFeatured($q)
    {
        return $q->where('featured', true)->where('active', true)->orderBy('sort_order');
    }
}
