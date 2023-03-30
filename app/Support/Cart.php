<?php

namespace App\Support;

use App\Models\CartItem;
use App\Models\Good;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCartItemsCount(): int
    {
        $user = auth()->user();

        if ($user) {
            return CartItem::whereUserId($user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce($cartItems, fn($carry, $item) => $carry + $item['quantity'], 0);
        }
    }

    public static function getCartItems(): Collection|array
    {
        $user = auth()->user();
        if ($user) {
            return CartItem::whereUserId($user->id)->get()->map(fn($item) => ['good_id' => $item->good_id, 'quantity' => $item->quantity]);
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems(): array
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    public static function setCookieCartItems($cartItems): void
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
    }

    public static function getCountFromItems($cartItems): int
    {
        return array_reduce($cartItems, fn($carry, $item) => $carry + $item['quantity'], 0);
    }

    public static function addCookieSavedCartItems(): void
    {
        $user = auth()->user();
        $cartItems = self::getCookieCartItems();
        $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('good_id');
        $savedCartItems = [];

        foreach ($cartItems as $cartItem) {
            if (isset($userCartItems[$cartItem['good_id']])) {
                continue;
            }
            $savedCartItems[] = [
                'user_id' => $user->id,
                'good_id' => $cartItem['good_id'],
                'quantity' => $cartItem['quantity']
            ];
        }

        if (!empty($savedCartItems)) {
            CartItem::insert($savedCartItems);
        }
    }

    public static function getGoodsAndCartItems(): array|Collection
    {
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'good_id');
        $goods = Good::whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'good_id');

        return [$goods, $cartItems];
    }
}
