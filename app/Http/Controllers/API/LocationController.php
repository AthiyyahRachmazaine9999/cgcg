<?php

namespace App\Http\Controllers\API;

use App\Models\Location\Provinsi;
use App\Models\Location\Kota;
use App\Models\Location\Kecamatan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajax_province()
    {
        $data = Provinsi::all();
        echo json_encode($data);
    }

    public function android_province()
    {
        $data = Provinsi::all();
        return response()->json([
            'result'    => $data,
        ]);
    }

    public function ajax_city(Request $request)
    {
        $data = Kota::where('province_id',$request->id)->get();
        echo json_encode($data);
    }

    public function ajax_kecamatan(Request $request)
    {
        $data = Kecamatan::where('kabupaten_id',$request->id)->get();
        echo json_encode($data);
    }
}
