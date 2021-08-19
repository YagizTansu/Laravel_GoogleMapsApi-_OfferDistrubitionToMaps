<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function getCountries()
    {
        $country = Country::get();
        return response()->json($country);
    }

    public function getCities(Request $request){
        $city = City::where('country_id', '=', $request->countryId)->get();
        return response()->json($city);
    }
}
