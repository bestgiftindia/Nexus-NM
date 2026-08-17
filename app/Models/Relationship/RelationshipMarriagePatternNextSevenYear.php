<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipMarriagePatternNextSevenYear extends Model
{
    use HasFactory;
    protected $fillable = [
        'personal_year',
        'title',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
