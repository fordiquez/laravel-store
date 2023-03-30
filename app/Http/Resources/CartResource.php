<?php

namespace App\Http\Resources;

use App\Models\Good;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        list($goods, $cartItems) = $this->resource;

        return [
            'count' => Cart::getCartItemsCount(),
            'total' => $goods->reduce(fn (?float $carry, Good $good) => $carry + $good->price * $cartItems[$good->id]['quantity']),
            'items' => GoodResource::collection($goods)
        ];
    }
}
