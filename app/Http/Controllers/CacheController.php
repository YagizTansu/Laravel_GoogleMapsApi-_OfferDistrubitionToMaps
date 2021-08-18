<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\CurrencyExchangeRate;
use App\Models\Currency;
use App\Models\Offer;

class CacheController extends Controller
{
    public function getCacheFile()
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
            $offers = Offer::where('id', '<',200)->where('company_id', '=', 1)->with('currency','currency.currencyExchangeRates')->get();
            json_encode($offers);
            Cache::put('offers', $offers, 60);

            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }

    }
}
