<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductLive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
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


    public function find_product(Request $request)
    {

        $data = [];
        // dd($request);
        if($request->has('q')){
            $search = $request->q;
            $data   = ProductLive::select('*')->where([['ocbz_product.sku', 'like', '%' . $search . '%']])
            ->join('ocbz_product_description','ocbz_product.product_id','=','ocbz_product_description.product_id')
             ->orWhere([['ocbz_product_description.name', 'like', '%' . $search . '%']])
                    ->get();
        }
        return response()->json($data);
    }

    public function find_detail(Request $request)
    {

        $data   = [];
        $search = $request->sku;
        $data   = ProductLive::select("ocbz_product.*")
            ->leftJoin('ocbz_product_description as b', 'ocbz_product.product_id', '=', 'b.product_id')
            		->where('sku','LIKE',"%$search%")
                    ->first();
        return response()->json($data);
    }
}
