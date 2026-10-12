<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Destination extends Model
{
    protected $fillable = [
        'name', 'slug', 'flag', 'image', 'short_description', 'description',
        'gradient_classes', 'sort_order', 'active', 'featured',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'featured' => 'boolean', 'sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $d) {
            $d->slug = $d->slug ?: Str::slug($d->name);
        });
    }

    public function universities(): HasMany
    {
        return $this->hasMany(University::class);
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
