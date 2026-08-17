<?php

namespace App\Models\Child;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildParentStyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'king_number',
        'queen_number',
        'title',
        'description',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
