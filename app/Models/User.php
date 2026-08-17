<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Image;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_code',
        'phone',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'is_publish'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (User $user) {

            if (request()->hasFile('profile')) {

                $oldImage = $user->getOriginal('avatar');

                // Delete old image
                if (!empty($oldImage)) {
                    Image::removeFile('users/', $oldImage);
                }

                // Upload new image
                $imageName = Image::autoheight(
                    'users/',
                    request()->file('profile')
                );

                // Set new image name
                $user->avatar = $imageName;
            }
        });
    }


    function address(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id');
    }

    public function phonecode(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }
}
