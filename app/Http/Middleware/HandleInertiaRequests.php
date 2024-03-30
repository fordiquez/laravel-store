<?php

namespace App\Http\Middleware;

use App\Http\Resources\CartResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\Cart;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'user' => $request->user(),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'categories' => $request->routeIs('livewire.message') ?: CategoryResource::collection(Category::whereParentId(null)->get()),
            'cart' => new CartResource(Cart::getGoodsAndCartItems()),
            'notification' => $request->session()->get('notification'),
        ];
    }
}
