<?php

namespace App\Support;

use App\Models\CartItem;
use App\Models\Good;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCount(): int
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->sum('quantity');
        }

        return array_reduce(self::getCookieCartItems(), fn ($carry, $item) => $carry + $item['quantity'], 0);
    }

    public static function getCartItems(): Collection|array
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->get()->map(fn (CartItem $item) => ['good_id' => $item->good_id, 'quantity' => $item->quantity]);
        }

        return self::getCookieCartItems();
    }

    public static function getCookieCartItems(): array
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    public static function setCookieCartItems(array $cartItems): void
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
    }

    public static function getCountFromItems(array $cartItems): int
    {
        return array_reduce($cartItems, fn (int $carry, array $item) => $carry + $item['quantity'], 0);
    }

    public static function saveCookieCartItems(): void
    {
        $user = auth()->user();
        $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('good_id');
        $savedCartItems = [];

        foreach (self::getCookieCartItems() as $cartItem) {
            if (isset($userCartItems[$cartItem['good_id']])) {
                $userCartItems[$cartItem['good_id']]->update(['quantity' => $cartItem['quantity']]);

                continue;
            }

            $savedCartItems[] = [
                'user_id' => $user->id,
                'good_id' => $cartItem['good_id'],
                'quantity' => $cartItem['quantity'],
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
