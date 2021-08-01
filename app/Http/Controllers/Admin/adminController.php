<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;
use App\Http\Requests\ShipCreateRequest;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ships = Ship::get();
        return view('Admin.Ships.ships',compact('ships'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.Ships.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShipCreateRequest $request)
    {
        $ship = new Ship;
        $ship->name=$request->name;
        $ship->latitude=$request->latitude;
        $ship->longitude=$request->longitude;
        $ship->radius=$request->radius;

        $ship->save();

         return redirect()-> route('ships.index')-> withSuccess('Adding Succesful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ship = Ship::find($id) ?? abort(404,'ship do not found');
        return view('Admin.Ships.edit',compact('ship'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShipCreateRequest $request, $id)
    {
        Ship::Where('id',$id)->update($request->except(['_method','_token']));
        return redirect()->route('ships.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('ships')->where('id', '=', $id)->delete();
        return redirect()->route('ships.index')-> withSuccess('Delete Succesful');
    }
}
