<?php

namespace App\Http\Controllers\Main;

use App\Support\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoodResource;
use App\Models\CartItem;
use App\Models\Good;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        list($goods, $cartItems) = Cart::getGoodsAndCartItems();
        $total = 0;
        foreach ($goods as $good) {
            $total += $good->price * $cartItems[$good->id]['quantity'];
        }

        return response()->json([
            'cartItems' => $cartItems,
            'goods' => GoodResource::collection($goods),
            'total' => $total,
        ]);
    }

    public function count() {
        return response([
            'count' => Cart::getCartItemsCount()
        ]);
    }

    public function store(Request $request, Good $good) {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'good_id' => $good->id])->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->update();
            } else {
                $data = [
                    'user_id' => $user->id,
                    'good_id' => $good->id,
                    'quantity' => $quantity
                ];
                CartItem::create($data);
            }
            return response([
                'count' => Cart::getCartItemsCount()
            ]);
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
                    'price' => $good->price
                ];
            }
            Cart::setCookieCartItems($cartItems);

            return response([
                'count' => Cart::getCountFromItems($cartItems),
                'cartItems' => $cartItems
            ]);
        }
    }

    public function remove(Request $request, Good $good) {
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'good_id' => $good->id])->first();
            $cartItem?->update();

            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['good_id'] === $good->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);

            return response([
                'count' => Cart::getCountFromItems($cartItems)
            ]);
        }
    }

    public function updateQuantity(Request $request, Good $good) {
        $quantity = (int)$request->post('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'good_id' => $good->id])->update(['quantity' => $quantity]);
            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['good_id'] === $good->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);

            return response([
                'count' => Cart::getCountFromItems($cartItems)
            ]);
        }
    }

    public function bulkDelete(Request $request) {
        $user = $request->user();
        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $item) {
                unset($item);
            }
            array_splice($cartItems, 0, count($cartItems));
            Cart::setCookieCartItems($cartItems);

            return response([
                'count' => Cart::getCountFromItems($cartItems)
            ]);
        }
    }
}
