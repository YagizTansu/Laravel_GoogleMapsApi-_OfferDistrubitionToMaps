<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getCurrencySymbol(Request $request){
        $currencySymbol = Currency::where('id', '=', $request->currencyId)->get('symbol');
        return response()->json($currencySymbol);
    }

    public function getCurrencySellingValue(Request $request)
    {
        # code...
    }
}
