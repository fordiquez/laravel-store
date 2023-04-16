<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
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

    public function states(Country $country)
    {
        return response()->json($country->states->setVisible(['id', 'uuid', 'name']));
    }

    public function cities(State $state)
    {
        return response()->json($state->cities->setVisible(['id', 'uuid', 'name']));
    }
}
