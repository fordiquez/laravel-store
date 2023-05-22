<?php

namespace App\Http\Controllers\Main;

use App\Enums\OrderPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Support\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Cart::getCount()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return inertia('Checkout/Index', [
            'deliveries' => Setting::whereGroup('delivery')->get(),
            'payments' => OrderPayment::asSelectArray(),
        ]);
    }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        /** @var User $user */
        $user = $request->user();

        try {
            DB::beginTransaction();
            $order = Order::create(Arr::except($data, 'items'));
            $order->orderItems()->createMany($user->cartItems->setVisible(['good_id', 'quantity', 'unit_price'])->toArray());
            $user->cartItems()->delete();
            DB::commit();
            Setting::sendNotification('success', 'New Order', 'Order has been created');

            return to_route('profile.orders');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            Setting::sendNotification('success', 'New Order', 'Order has been created');

            return redirect()->back();
        }
    }

    public function success()
    {

    }

    public function cancel()
    {

    }
}
