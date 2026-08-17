<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipIntroductionContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
