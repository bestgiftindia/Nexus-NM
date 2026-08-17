<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LuckyUnluckyNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'king_number',
        'queen_number',
        'lucky_numbers',
        'unlucky_numbers',
        'neutral_number',
        'lucky_color',
        'unlucky_color'
    ];

    protected $casts = [
        'lucky_numbers'   => 'array',
        'unlucky_numbers' => 'array',
        'neutral_number'  => 'array',
        'lucky_color'     => 'array',
        'unlucky_color'   => 'array'
    ];

    public function kingPlanet()
    {
        return $this->belongsTo(Planet::class, 'king_planet_id');
    }

    public function queenPlanet()
    {
        return $this->belongsTo(Planet::class, 'queen_planet_id');
    }


    function scopeKing($query, $kingNumber)
    {
        return $query->where('king_planet_id', $kingNumber);
    }

    function scopeQueen($query, $queenNumber)
    {
        return $query->where('queen_planet_id', $queenNumber);
    }
}
