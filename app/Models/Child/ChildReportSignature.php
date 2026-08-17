<?php

namespace App\Models\Child;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildReportSignature extends Model
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
