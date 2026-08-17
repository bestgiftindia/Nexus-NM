<?php

namespace App\Models\Child;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildKingQueenPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'king_number',
        'queen_number',
        'content',
        'behaviour',
        'strength',
        'weakness',
    ];

    protected $casts = [
        'content'   => 'array',
        'behaviour' => 'array',
        'strength'  => 'array',
        'weakness'  => 'array',
    ];
}
