<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipLoveQuote extends Model
{
    use HasFactory;
    protected $fillable = [
        'title'
    ];
}
