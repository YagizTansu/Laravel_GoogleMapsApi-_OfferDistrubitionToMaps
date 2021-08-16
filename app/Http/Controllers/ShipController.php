<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipCreateRequest;
use Illuminate\Support\Facades\Http;
use App\Models\CurrencyExchangeRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Ship;
use App\Services\ExchangeRateService;

class ShipController extends Controller
{
    public function show()
    {
        //$ships = DB::table('ships')->where('user_id','=', Auth::id())->get();
        $ships = Ship::where('user_id', '=', Auth::id())->with('currency')->get();
        $priceMax = Ship::max('price');
        $priceMin = Ship::min('price');
        $currency = Currency::get();
        return view('ships', compact(['ships', 'priceMax', 'priceMin', 'currency']));
    }

    public function add(ShipCreateRequest $request)
    {
        $ship = new Ship;
        $ship->user_id = Auth::id();
        $ship->name = $request->name;
        $ship->latitude = $request->latitude;
        $ship->longitude = $request->longitude;
        $ship->radius = $request->radius;
        $ship->price = $request->price;
        $ship->currency_id = $request->currency_id;
        $ship->save();

        return redirect()->route('distribution')->withSuccess('Adding Succesful');
    }

    public function detail($id)
    {
        $ship = Ship::find($id);
        return view('ship_detail', compact('ship'));
    }

    public function index(Request $request)
    {
        $ships = Ship::where('user_id', '=', Auth::id())->with('currency')->get();
        return view('distribution', compact('ships'));
    }

    public function ajaxPost(){
        $ships = Ship::where('user_id', '=', Auth::id())->with('currency','currency.currencyExchangeRates')->get();
        $priceMax = Ship::max('price');
        $priceMin = Ship::min('price');
        $currency = Currency::get();
        $defaultCurrency = CurrencyExchangeRate::where('currency_id', '=',1)->latest()->value('selling');

        $payLoad = ['ships' => $ships, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency];
        return response()->json($payLoad);
    }

    public function getExchangeRate()
    {
        $service = new ExchangeRateService();
        $load = $service->getExchangeRate();
        return response()->json($load);
    }

    public function addCircle(Request $request){
        $ship = new Ship;
        $ship->user_id =Auth::id();
        $ship->name = "";
        $ship->latitude =$request->latitude;
        $ship->longitude = $request->longitude;
        $ship->radius = $request->radius;
        //$ship->price =0;
        $ship->currency_id =1;
        $ship->save();

        return redirect()->route('distribution')->withSuccess('Adding Succesful');
    }
}
