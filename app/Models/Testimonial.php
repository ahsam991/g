<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'country_flag', 'visa_type', 'quote', 'photo',
        'rating', 'sort_order', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'sort_order' => 'integer', 'rating' => 'integer'];
    }

    public function scopeActive($q)
    {
        return $q->where('active', true)->orderBy('sort_order');
    }
}
