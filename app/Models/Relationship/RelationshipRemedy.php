<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipRemedy extends Model
{
    use HasFactory;

    protected $fillable = [
        'king_number',
        'title',
        'description',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
