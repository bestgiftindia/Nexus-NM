<?php

namespace App\Models\Child;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildProfession extends Model
{
    use HasFactory;

    protected $fillable = [
        'king_number',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
