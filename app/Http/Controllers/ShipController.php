<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShipCreateRequest;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShipController extends Controller
{
    public function show(){
        //$ships = DB::table('ships')->where('user_id','=', Auth::id())->get();
        $ships=Ship::where('user_id','=', Auth::id())->with('currency')->get();
        $priceMax=Ship::max('price');
        $priceMin=Ship::min('price');
        $currency = Currency::get();
        return view('ships',compact(['ships','priceMax','priceMin','currency']));
    }

    public function add(ShipCreateRequest $request){

        $ship = new Ship;
        $ship->user_id= Auth::id();
        $ship->name=$request->name;
        $ship->latitude=$request->latitude;
        $ship->longitude=$request->longitude;
        $ship->radius=$request->radius;
        $ship->price=$request->price;
        $ship->currency_id=$request->currency_id;
        $ship->save();

         return redirect()-> route('ships')-> withSuccess('Adding Succesful');
    }

    public function detail($id){
        $ship = Ship::find($id);
        return view('ship_detail',compact('ship'));
    }

    public function index(Request $request){
        $ships=Ship::where('user_id','=', Auth::id())->with('currency')->get();
         return view('ajax', compact('ships'));

    }

    public function ajaxPost(Request $request){
        $ships=Ship::where('user_id','=', Auth::id())->with('currency')->get();
        $priceMax=Ship::max('price');
        $priceMin=Ship::min('price');
        $currency = Currency::get();
        return response()->json(['ships' =>$ships,'priceMax' => $priceMax, 'priceMin' => $priceMin ,'currency' => $currency]);

   }
}
