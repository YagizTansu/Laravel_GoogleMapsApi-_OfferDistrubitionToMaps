<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Ship;
use Illuminate\Support\Facades\Cache;
use App\Models\CurrencyExchangeRate;
use App\Models\Currency;

class CacheController extends Controller
{
    public function getCacheFile()
    {
        //$ships = Ship::where('user_id', '=', Auth::id())->with('currency','currency.currencyExchangeRates')->get();
        $priceMax = Ship::max('price');
        $priceMin = Ship::min('price');
        $currency = Currency::get();
        $CurrencyExchangeRate = CurrencyExchangeRate::get();
        $defaultCurrency = CurrencyExchangeRate::where('currency_id', '=',1)->latest()->value('selling');

        if (Cache::has('ships')) {
            $ships = Cache::get('ships');
            json_encode($ships);
            $payLoad = ['ships' => $ships, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }else{
            $ships = Ship::where('id', '<',1000)->where('user_id', '=', Auth::id())->with('currency','currency.currencyExchangeRates')->get();
            json_encode($ships);
            Cache::put('ships', $ships, 60);

            $payLoad = ['ships' => $ships, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }

//$ships = Ship::where('user_id', '=', Auth::id())->with('currency')->get();
//   return Cache::remember('ships',120,function()
//   {
//       return Ship::all();
//   });
    }
}
