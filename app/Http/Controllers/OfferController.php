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
    public function index(){
        return view('offers');
    }

    public function getOffers(Request $request){
        $priceMax = Offer::max('price');
        $priceMin = Offer::min('price');
        $currency = Currency::get();
        $CurrencyExchangeRate = CurrencyExchangeRate::get();
        $defaultCurrency = CurrencyExchangeRate::where('currency_id', '=',1)->latest()->value('selling');

        $cityId = $request->cityId ;

        /*if (Cache::has($cityId)) {
            $offers = Cache::get($cityId);
            json_encode($offers);
            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            //return response()->json($payLoad);
        }else{
            $offers = Offer::where('city_id', '=',$cityId)->with('company','currency','currency.currencyExchangeRates')->get();
            json_encode($offers);
            Cache::put($cityId , $offers, 1000);

            $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
            //return response()->json($payLoad);
        }*/

        $offers = Offer::where('city_id', '=',$cityId)->with('company','currency','currency.currencyExchangeRates')->get();
        $payLoad = ['offers' => $offers, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency,'currencyExchangeRate'=>$CurrencyExchangeRate];
        return response()->json($payLoad);
    }

    public function addOffer(Request $request){
        $offer = new Offer;
        $offer->city_id = $request->cityId;
        $offer->company_id = Auth::user()->company_id;
        $offer->latitude =$request->latitude;
        $offer->longitude = $request->longitude;
        $offer->radius = $request->radius;
        $offer->currency_id =$request->currencyId;
        $offer->price =$request->offerPrice;

        $offer->save();

        return redirect()->route('offers')->withSuccess('Offer Added Successfuly');
    }

    public function detail($id){
        $offer = Offer::find($id);
        return view('offer_detail', compact('offer'));
    }

    public function offerAddManully(Request $request){
        $offer = new Offer;
        $offer->city_id = $request->city_id;
        $offer->company_id = Auth::user()->company_id;
        $offer->latitude =$request->latitude;
        $offer->longitude = $request->longitude;
        $offer->radius = $request->radius;
        $offer->currency_id =1;
        $offer->price =$request->price;

        $offer->save();
        return redirect()->route('offers');
    }


    public function offerAjax(Request $request){
        //request
        $cityId = $request->cityId;
        $currencyId = $request->currencyId;

        //price min max for circle color
        $priceMax = Offer::where('city_id', '=',$cityId)->max('price');
        $priceMin = Offer::where('city_id', '=',$cityId)->min('price');
        $currencySymbol = Currency::where('id',$currencyId)->value('symbol');

        //for display currency filter
        $currencySellingValue = CurrencyExchangeRate::orderBy('date','desc')->take(3)->get();

        foreach ($currencySellingValue as $key) {
            if ($key->currency_id == $currencyId) {
                $exchangeSellingValue = $key->selling;
            }
        }

        //offers
        $offers = Offer::where('city_id', '=',$cityId)->with('company','currency','currency.currencyExchangeRates')->get();

        //add new attribute desired_price
        $offerAddedAttributes = $offers->map(function ($item, $key) use ($currencyId,$exchangeSellingValue,$currencySellingValue,$currencySymbol) {

            foreach ($currencySellingValue as $key) {
                if ($key->currency_id == $item->currency_id) {
                    $sellingValue = $key->selling;
                }
            }

            $item['desired_price'] = (($item->price)*$sellingValue)/$exchangeSellingValue;
            $item['desired_currencyId'] = $currencyId;
            $item['desired_currency_symbol'] = $currencySymbol;

            return $item;
        });

        $payLoad = ['offers' => $offerAddedAttributes, 'priceMax' => $priceMax, 'priceMin' => $priceMin];
        return $payLoad;

    }
}
