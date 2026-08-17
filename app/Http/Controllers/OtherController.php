<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    function states_lists(Request $request)
    {
        return State::country($request->country)->active()->orderBy('name')->get()->toArray();
    }

    function cities_lists(Request $request)
    {
        return City::state($request->state)->active()->orderBy('name')->get()->toArray();
    }
}
