<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\AddressRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Http\Resources\UserAddressResource;
use App\Models\Country;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/PersonalInformation', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'badges' => [
                'orders' => $request->user()->orders()->count(),
                'reviews' => $request->user()->reviews()->count(),
            ],
            'addresses' => UserAddressResource::collection($request->user()->addresses),
            'countries' => Country::query()->orderBy('name')->get()->setVisible(['id', 'name', 'iso2'])->toArray(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.personal-information.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('index.dashboard');
    }

    public function storeAddress(AddressRequest $request)
    {
        $request->user()->addresses()->create($request->validated());

        return to_route('profile.personal-information.edit');
    }

    public function updateAddress(UserAddress $address, AddressRequest $request)
    {
        $address->update($request->validated());

        return to_route('profile.personal-information.edit');
    }

    public function patchAddress(UserAddress $address, Request $request)
    {
        $request->user()->addresses()->update(['is_main' => false]);
        $address->update(['is_main' => true]);

        return to_route('profile.personal-information.edit');
    }

    public function destroyAddress(UserAddress $address)
    {
        $address->delete();

        return to_route('profile.personal-information.edit');
    }

    public function orders()
    {
        inertia('Profile/Form');
    }

    public function wishlist()
    {
        inertia('Profile/Edit');
    }

    public function wallet(Request $request)
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

        return inertia('Profile/Wallet', [
            'badges' => [
                'orders' => $request->user()->orders()->count(),
                'reviews' => $request->user()->reviews()->count(),
            ],
            'billingDetails' => $billingDetails
        ]);
    }

    public function storeCard(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $options = [
            'name' => $user->full_name,
        ];

        $user->createOrGetStripeCustomer($options);
        $user->addPaymentMethod($request->get('id'));
        $user->updateDefaultPaymentMethod($request->get('id'));

        return redirect()->back();
    }

    public function reviews()
    {
        inertia('Profile/Edit');
    }

    public function messages()
    {
        inertia('Profile/Edit');
    }
}
