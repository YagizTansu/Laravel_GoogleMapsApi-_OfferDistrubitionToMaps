<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    public function show()
    {
       // $ships = DB::table('ships')->where('user_id','=', Auth::id())->get();

        $ships=Ship::where('user_id','=', Auth::id())->with('currency')->get();
        //dd($ships);
        return view('trucks',compact('ships'));
    }
}
