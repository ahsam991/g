<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContact extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['candidate_id', 'name', 'relationship', 'phone', 'email', 'address'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
