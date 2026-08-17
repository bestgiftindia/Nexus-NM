<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MissingNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "king_planet_id",
        "missing_number_msg",
        "repetitive_number_donation",
        "repetitive_number_medicalIssues",
        "remedies"
    ];

    function scopeMissing($query,$missing){
        return $query->whereKingPlanetId($missing);
    }
}
