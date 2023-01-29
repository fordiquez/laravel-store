<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'good_id',
        'order_id',
        'quantity',
        'unit_price',
    ];
}
