<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShipController extends Controller
{
    public function show(){
        $ships = DB::table('ships')->where('user_id','=', Auth::id())->get();
        return view('ships',compact('ships'));
    }
}
