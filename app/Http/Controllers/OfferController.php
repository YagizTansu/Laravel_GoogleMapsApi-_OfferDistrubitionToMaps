<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\CurrencyExchangeRate;
use App\Models\Currency;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function getOffers(Request $request)
    {
        $priceMax = Offer::max('price');
        $priceMin = Offer::min('price');
        $currency = Currency::get();
        $CurrencyExchangeRate = CurrencyExchangeRate::get();
        $defaultCurrency = CurrencyExchangeRate::where('currency_id', '=',1)->latest()->value('selling');

        if (Cache::has('offers')) {
            $offers = Cache::get('offers');
            json_encode($offers);
            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }else{
            $offers = Offer::where('city_id', '=',$request->cityId)->with('currency','currency.currencyExchangeRates')->get();
            json_encode($offers);
            Cache::put('offers', $offers, 1);

            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }
    }
}
