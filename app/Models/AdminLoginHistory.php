<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminLoginHistory extends Model
{
    use SoftDeletes;
    protected $table = 'login_histories';
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'logged_in_at',
        'logged_out_at',
        'session_id',
        'is_active',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    function scopeUser($query, $userId)
    {
        return $query->whereUserId($userId);
    }
}
