<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtpHistory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'type',
        'expires_at',
        'verified_at',
        'attempts',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];
}
