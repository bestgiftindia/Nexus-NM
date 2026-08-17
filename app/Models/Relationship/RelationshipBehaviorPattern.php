<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipBehaviorPattern extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'number',
        'planet',
        'title',
        'core_energy',
        'hidden_traits'
    ];

    protected $casts = [
        'hidden_traits' => 'array'
    ];
}
