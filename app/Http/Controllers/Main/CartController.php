<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Good;
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, Good $good)
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'good_id' => $good->id])->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'good_id' => $good->id,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isGoodExists = false;
            foreach ($cartItems as &$item) {
                if ($item['good_id'] === $good->id) {
                    $item['quantity'] += $quantity;
                    $isGoodExists = true;
                    break;
                }
            }

            if (!$isGoodExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'good_id' => $good->id,
                    'quantity' => $quantity,
                    'price' => $good->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }

    public function update(Request $request, Good $good)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'good_id' => $good->id])->update(['quantity' => $quantity]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['good_id'] === $good->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }

            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }

    public function delete(Request $request, Good $good)
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'good_id' => $good->id])->first()?->delete();
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['good_id'] === $good->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $user = $request->user();
        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $item) {
                unset($item);
            }
            array_splice($cartItems, 0, count($cartItems));
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }
}
