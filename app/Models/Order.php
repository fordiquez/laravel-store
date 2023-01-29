<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'user_id',
        'delivery_method',
        'pay_method',
        'promocode_id',
        'goods_cost',
        'delivery_cost',
        'total_cost',
    ];

    public static array $deliveryMethods = [
        'Courier',
        'Self-delivery from Meest',
        'Self-delivery from Ukrposhta',
        'Self-delivery from Nova Poshta'
    ];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Good::class, 'good_order', 'order_id', 'good_id');
    }

    public function orderHistories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }
}
