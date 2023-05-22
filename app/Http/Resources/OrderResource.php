<?php

namespace App\Http\Resources;

use App\Enums\OrderDelivery;
use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'delivery_method' => OrderDelivery::fromValue($this->delivery_method)->description,
            'payment_method' => OrderPayment::fromValue($this->payment_method)->description,
            'goods_cost' => $this->goods_cost,
            'delivery_cost' => $this->delivery_cost,
            'total_cost' => $this->total_cost,
            'status' => OrderStatus::fromValue($this->status)->description,
            'status_history' => $this->status_history,
            'created_at' => $this->created_at->format('d.m.y h:i:s'),
            'updated_at' => $this->updated_at->format('d.m.y h:i:s'),

            'user_id' => $this->user_id,
            'user_address_id' => $this->user_address_id,
            'promo_code_id' => $this->promo_code_id,

            'userAddress' => new UserAddressResource($this->whenLoaded('userAddress')),
            'orderItems' => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}
