<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShipController extends Controller
{
    public function show(){
        //$ships = DB::table('ships')->where('user_id','=', Auth::id())->get();
        $ships=Ship::where('user_id','=', Auth::id())->with('currency')->get();
        //dd($ships);
        return view('ships',compact('ships'));
    }
}
