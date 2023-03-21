<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'good_id', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
    ];
}
