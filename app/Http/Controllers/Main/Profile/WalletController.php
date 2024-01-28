<?php

namespace App\Http\Controllers\Main\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        /** @var User $user */
        $user = $request->user()->load('addresses');
        $address = $user->addresses()->firstWhere('is_main', true);

        $line1 = $address ? "$address->street, $address->house" : null;

        $billingDetails = [
            'name' => $user->full_name,
            'email' => $user->email,
            'address' => [
                'line1' => $address?->flat ? "$line1, $address->flat" : $line1,
                'city' => $address?->city->name,
                'state' => $address?->state->name,
                'country' => $address?->country->iso2,
                'postal_code' => $address?->postal_code,
            ],
        ];

        $defaultPaymentMethod = $user->hasDefaultPaymentMethod() ? $user->defaultPaymentMethod()->toArray() : null;
        if ($defaultPaymentMethod) {
            $card = [
                'name' => $defaultPaymentMethod['billing_details']['name'],
                'brand' => $defaultPaymentMethod['card']['brand'],
                'number' => '**** **** **** ' . $defaultPaymentMethod['card']['last4'],
                'exp_month' => $defaultPaymentMethod['card']['exp_month'],
                'exp_year' => $defaultPaymentMethod['card']['exp_year'],
            ];
        }

        return inertia('Profile/Wallet', [
            'badges' => [
                'orders' => $request->user()->orders()->count(),
                'reviews' => $request->user()->reviews()->count(),
            ],
            'billingDetails' => $billingDetails,
            'card' => $card ?? null,
            'hasSocials' => (bool) $user->socials()->count(),
        ]);
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $data = $request->all();

        $user->createOrGetStripeCustomer(['name' => $user->full_name]);
        $user->addPaymentMethod($data['id']);
        $user->updateDefaultPaymentMethod($data['id']);
        $user->update(['trial_ends_at' => Carbon::createFromDate($data['card']['exp_year'], $data['card']['exp_month'], 1)]);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $data = $request->all();

        $user->updateDefaultPaymentMethod($data['id']);
        $user->update(['trial_ends_at' => Carbon::createFromDate($data['card']['exp_year'], $data['card']['exp_month'], 1)]);

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user->socials()->count()) {
            $request->validate([
                'password' => ['required', 'current-password'],
            ]);
        }

        try {
            $user->defaultPaymentMethod()->delete();
            $user->update(['trial_ends_at' => null]);
        } catch (ApiErrorException $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back();
    }
}
