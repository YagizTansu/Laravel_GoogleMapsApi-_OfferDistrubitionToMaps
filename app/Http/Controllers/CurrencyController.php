<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getCurrencySymbol(Request $request){
        $currencySymbol = Currency::where('id', '=', $request->currencyId)->get('symbol');
        return response()->json($currencySymbol);
    }

    public function getCurrencySellingValue(Request $request)
    {
        $currencySellingValue =CurrencyExchangeRate::where('currency_id', '=',$request->currencyId)->latest('date')->first('selling');
        return response()->json($currencySellingValue);
    }
}
