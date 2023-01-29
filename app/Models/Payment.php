<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'type',
        'session_id',
    ];

    public static array $types = [
        'Payment upon receipt of goods',
        'Pay now',
        'Cashless for legal entities',
        'Payment for legal entities through a current account',
        'Pay online with the social card "Baby package"',
        'Cashless for individuals',
        'Payment for individuals through a current account',
        'PrivatPay',
        'Credit and payment in installments',
        'Issuance of loans in partner banks'
    ];
}
