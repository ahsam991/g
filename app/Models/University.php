<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class University extends Model
{
    protected $fillable = [
        'name', 'slug', 'destination_id', 'logo', 'website', 'city',
        'short_description', 'description', 'ranking', 'partner',
        'active', 'featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'featured' => 'boolean', 'partner' => 'boolean', 'sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $u) {
            $u->slug = $u->slug ?: Str::slug($u->name);
        });
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function scopeActive($q)
    {
        return $q->where('active', true)->orderBy('sort_order');
    }

    public function scopePartner($q)
    {
        return $q->where('partner', true)->where('active', true);
    }
}
