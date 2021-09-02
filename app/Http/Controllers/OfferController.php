<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\CurrencyExchangeRate;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

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

        $cityId = $request->cityId ;

        if (Cache::has($cityId)) {
            $offers = Cache::get($cityId);
            json_encode($offers);
            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }else{
            $offers = Offer::where('city_id', '=',$cityId)->with('company','currency','currency.currencyExchangeRates')->get();
            json_encode($offers);
            Cache::put($cityId , $offers, 10000);

            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            return response()->json($payLoad);
        }
    }

    public function addOffer(Request $request){
        $offer = new Offer;
        $offer->city_id = $request->cityId;
        $offer->company_id = Auth::user()->company_id;
        $offer->latitude =$request->latitude;
        $offer->longitude = $request->longitude;
        $offer->radius = $request->radius;
        $offer->currency_id =1;
        $offer->price =$request->offerPrice;

        $offer->save();

        return redirect()->route('offers')->withSuccess('Offer Added Successfuly');
    }

    public function detail($id)
    {
        $offer = Offer::find($id);
        return view('offer_detail', compact('offer'));
    }

    public function offerAddManully(Request $request)
    {
        $offer = new Offer;
        $offer->city_id = $request->city_id;
        $offer->company_id = Auth::user()->company_id;
        $offer->latitude =$request->latitude;
        $offer->longitude = $request->longitude;
        $offer->radius = $request->radius;
        $offer->currency_id =1;
        $offer->price =$request->price;

        $offer->save();
    }

}
