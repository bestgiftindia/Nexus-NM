<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'mobile_number',
        'email',
        'gender',
        'location',
        'tob'
    ];

     public function phonecode(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }
}
