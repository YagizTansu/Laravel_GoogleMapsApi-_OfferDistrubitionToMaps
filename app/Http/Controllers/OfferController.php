<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\CurrencyExchangeRate;
use App\Models\Currency;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        return view('offers');
    }
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

    public function addOffer(Request $request){
        $offer = new Offer;
        $offer->city_id = 5;
        $offer->company_id =3;
        $offer->latitude =$request->latitude;
        $offer->longitude = $request->longitude;
        $offer->radius = $request->radius;
        $offer->currency_id =1;
        $offer->price =1000;

        $offer->save();

        return redirect()->route('offers')->withSuccess('Offer Added Successfuly');
    }

    public function detail($id)
    {
        $offer = Offer::find($id);
        return view('offer_detail', compact('offer'));
    }
    public function getOffersList()
    {
        $offers = Offer::get();
        return view('offers_list', compact('offers'));
    }

}
