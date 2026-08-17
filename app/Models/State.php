<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = "states";

    function scopeCountry($query, $country)
    {
        return $query->whereCountryId($country);
    }
    function scopeActive($query)
    {
        return $query->whereStatus(1);
    }
}
