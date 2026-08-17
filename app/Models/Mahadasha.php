<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahadasha extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "king_planet_id",
        "message"
    ];

    protected $casts = [
        'message' => 'array'
    ];

    function scopeKing($query, $missing)
    {
        return $query->whereKingPlanetId($missing);
    }

    public function kingPlanet()
    {
        return $this->belongsTo(Planet::class, 'king_planet_id');
    }
}
