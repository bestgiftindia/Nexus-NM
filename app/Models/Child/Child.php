<?php

namespace App\Models\Child;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Login\LoginService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Country;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'login_user_id',
        'first_name',
        'middle_name',
        'last_name',
        'phone_code',
        'dob',
        'time_of_birth',
        'birth_location',
        'mobile_number',
        'email',
        'gender',
        'guardian_name',
        'guardian_relation',
        'suggested_names'
    ];

    protected $casts = [
        'suggested_names' => 'array'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $loginService = app(LoginService::class);
                $loginUser = $loginService->findLoginUserService();
                $model->login_user_id = $loginUser['account_id'] ?? NULL;
            }
        });
    }

     public function phonecode(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }

}
