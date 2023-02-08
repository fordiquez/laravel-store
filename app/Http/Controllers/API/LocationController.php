<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function countries(Request $request)
    {
        $name = $request->get('name');

        if (str($name)->length() > 1) {
            return response()->json(Country::where('name', 'like', "%$name%")->get());
        } else {
            return response()->json([
                'message' => 'Not enough length',
            ]);
        }
    }
}
