<?php

namespace App\Models\Child;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildMissingNumberRemedy extends Model
{
    use HasFactory;

     protected $fillable = [
        'missing_number',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
