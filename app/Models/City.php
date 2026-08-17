<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table ="cities";

    function scopeState($query, $state)
    {
        return $query->whereStateId($state);
    }
    function scopeActive($query){
        return $query->whereStatus(1);
    }
}
