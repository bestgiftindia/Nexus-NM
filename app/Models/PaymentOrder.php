<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    protected $fillable = [
        'txnid',
        'name',
        'email',
        'mobile',
        'dob',
        'gender',
        'amount',
        'status',
        'mihpayid',
        'bank_ref_num',
        'payu_response',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'amount' => 'decimal:2',
            'payu_response' => 'array',
        ];
    }
}
