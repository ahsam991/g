<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Auditable;

    protected $fillable = ['key', 'value', 'type', 'group', 'label'];
}
