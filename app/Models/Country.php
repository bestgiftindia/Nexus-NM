<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table ="countries";

    function scopeActive($query){
        return $query->whereStatus(1);
    }
}
