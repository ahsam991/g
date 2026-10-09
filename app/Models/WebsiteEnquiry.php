<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;

class WebsiteEnquiry extends Model
{
    use Auditable;

    protected $fillable = [
        'type', 'name', 'email', 'phone', 'country', 'subject', 'level',
        'message', 'preferred_date', 'status', 'source', 'ip_address', 'lead_id',
    ];
}
