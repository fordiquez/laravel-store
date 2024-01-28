<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Country;
use App\Models\PromoCode;
use App\Models\State;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function categories()
    {
        return CategoryResource::collection(Category::where('parent_id', null)->get());
    }

    public function countries(Request $request)
    {
        $name = $request->get('name');

        if (str($name)->length() > 1) {
            return response()->json(Country::where('name', 'like', "%$name%")->get());
        }

        return response()->json(['message' => 'Not enough length']);
    }

    public function states(Country $country)
    {
        return response()->json($country->states->setVisible(['id', 'uuid', 'name']));
    }

    public function cities(State $state)
    {
        return response()->json($state->cities->setVisible(['id', 'uuid', 'name']));
    }

    public function verifyPromoCode(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'exists:promo_codes'],
            'total' => ['required', 'numeric'],
        ]);

        if ($promoCode = PromoCode::firstWhere([
            ['key', '=', $data['key']],
            ['starts_at', '<', now()],
            ['expires_at', '>', now()],
        ])) {
            if ($data['total'] > $promoCode->greater_than) {
                return response()->json($promoCode->toArray());
            }

            return response()->json(['message' => 'This promo code is not available']);
        }

        return response()->json(['message' => 'Promo code is not found']);
    }
}
