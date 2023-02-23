<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_id',
        'user_id',
        'user_address_id',
        'promo_code_id',
        'delivery_method',
        'payment_method',
        'goods_cost',
        'delivery_cost',
        'total_cost',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userAddress(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Good::class, 'order_items', 'order_id', 'good_id');
    }

    public function orderHistories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }
}
