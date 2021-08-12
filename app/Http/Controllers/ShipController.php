<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Ship;
use App\Models\CurrencyExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShipCreateRequest;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

        return redirect()->route('ajax')->withSuccess('Adding Succesful');
    }

    public function detail($id)
    {
        $ship = Ship::find($id);
        return view('ship_detail', compact('ship'));
    }

    public function index(Request $request)
    {
        $ships = Ship::where('user_id', '=', Auth::id())->with('currency')->get();
        return view('ajax', compact('ships'));
    }

    public function ajaxPost(Request $request)
    {
        $ships = Ship::where('user_id', '=', Auth::id())->with('currency','currency.currencyExchangeRates')->get();
        $priceMax = Ship::max('price');
        $priceMin = Ship::min('price');
        $currency = Currency::get();
        $defaultCurrency = CurrencyExchangeRate::where('id', '=',1)->value('selling');
        //$var=$ships->last()->currency->currencyExchangeRate;
        //return response()->json($var);

        $payLoad = ['ships' => $ships, 'priceMax' => $priceMax, 'priceMin' => $priceMin, 'currency' => $currency,'defaultCurrency'=>$defaultCurrency];
        return response()->json($payLoad);
    }

    public function getExchangeRate()
    {
        $currencies = [
            'USD',
            'EUR'
        ];

        $response = Http::get('https://www.tcmb.gov.tr/kurlar/today.xml');

        $xml = simplexml_load_string($response->body());
        foreach ($xml as $row) {
            $currencyCode = (string)$row->attributes()['CurrencyCode'];
            if (in_array($currencyCode, $currencies)) {
                $currencyQuery = Currency::where('code', $currencyCode)->first();

                $currencyExchangeRate = CurrencyExchangeRate::where('currency_id', $currencyQuery->id)->where('date', Carbon::now()->format('Y-m-d'))->first();

                if (empty($currencyExchangeRate)) {
                    CurrencyExchangeRate::create([
                        'currency_id' => $currencyQuery->id,
                        /*'buying' => (float)$row->BanknoteBuying,
                        'selling' => (float)$row->BanknoteSelling,*/
                        'buyying' => (float)$row->ForexBuying,
                        'selling' => (float)$row->ForexSelling,
                        'date' => Carbon::now()->format('Y-m-d')
                    ]);
                } else {
                    CurrencyExchangeRate::where('id', $currencyExchangeRate->id)->update([
                        'buyying' => (float)$row->ForexBuying,
                        'selling' => (float)$row->ForexSelling,
                    ]);
                }
            }
        }

        //$currencyExchangeRate = CurrencyExchangeRate::where('currency_id', 113)->where('date', Carbon::now()->format('Y-m-d'))->first();

        if (empty($currencyExchangeRate)) {
            CurrencyExchangeRate::create([
                'currency_id' => 113,
                'buyying' => 1,
                'selling' => 1,
                'date' => Carbon::now()->format('Y-m-d')
            ]);
        }

        $currencyExchangeRate = CurrencyExchangeRate::with('currency')->where('date', Carbon::now()->format('Y-m-d'))->get();
        $load = ['CurrencyExchangeRate' => $currencyExchangeRate];
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

        return redirect()->route('ajax')->withSuccess('Adding Succesful');
    }
}
