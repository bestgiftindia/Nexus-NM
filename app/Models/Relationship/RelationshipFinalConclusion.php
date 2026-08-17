<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipFinalConclusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
