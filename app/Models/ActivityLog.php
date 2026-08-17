<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module_name',
        'action',
        'record_id',
        'old_data',
        'new_data',
        'url',
        'method',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_data'=>'array',
        'new_data'=>'array'
    ];
}
