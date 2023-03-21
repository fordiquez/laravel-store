<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['good_id', 'order_id', 'quantity', 'unit_price'];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'float',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }
}
