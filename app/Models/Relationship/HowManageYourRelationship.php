<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowManageYourRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'king_number',
        'queen_number',
        'description',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
