<?php

namespace App\Models\Pronology;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameReport extends Model
{
    use HasFactory;

    protected $table = 'namenumerology';

    protected $fillable = [
        'custId',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'place_of_birth',
        'time_of_birth',
        'mobile_no',
        'email',
        'send_email',
        'company_name',
        'gender',
        'astroMsg',
        'manual_name'
    ];

    public $timestamps = true;
    protected $casts = [
    'manual_name' => 'array',
    ];
 }
