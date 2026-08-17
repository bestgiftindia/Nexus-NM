<?php

namespace App\Models\Loshugrid;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoshuGrid extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'email',
        'phone_code',
        'phone'
    ];

    public function phonecode(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }
}
