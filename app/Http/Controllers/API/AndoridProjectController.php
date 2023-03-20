<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Role\UserModel;
use App\Models\Role\Role_cabang;
use App\Models\Andorid\AndroidAbsensi;
use App\Models\Role\Role_address;
use App\Models\Role\Role_division;
use App\Models\HR\EmployeeModel;

class AndoridProjectController extends Controller
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


    public function Login(Request $request)
    {
        
        $pass = Hash::make($request->password);
        $user = UserModel::where([
            ['email', $request->id],
            ['password', $pass]
        ])->first();

        if ($user==null) {
            $success == false;
        }else{
            $success == true;

        }
        return response()->json([
            'success'  => true,
            'comment'  => "Accepted",
        ],202);
    }


    public function CheckIn(Request $request)
    {
        //
        $store = [
            //id_user
            //user_name
            //time_in
            //time_out
            //longitude
            //latitude
            //note
            //creared_at
            //created_by
        ];
        $qry = AndroidAbsensi::create($store);
        return response()->json($request);
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
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}