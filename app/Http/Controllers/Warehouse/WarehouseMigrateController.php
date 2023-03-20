<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActQuoModel;
use App\Models\Purchasing\PurchaseMigrateDetail;
use App\Models\Purchasing\PurchaseMigrate;
use App\Models\Purchasing\Purchase_address;
use App\Models\Warehouse\WarehouseMigrateDetail;
use App\Models\Warehouse\WarehouseMigrateOrder;
use App\Models\Warehouse\WarehouseMigrateOut;
use App\Models\Warehouse\Warehouse_address;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WarehouseMigrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouse.migrate.index');
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
        $main       = PurchaseMigrate::where('po_number', $id)->first();
        $vend       = VendorModel::where('id', $main->id_vendor)->first();
        $product    = PurchaseMigrateDetail::where('id_po', $main->id)->get();
        $altaddress = Purchase_address::where('id_po', $main->id)->first();
        $getwh      = getWarehouse("id_purchase", $main->id);
        $idwh       = $getwh == null ? "" : $getwh->id;
        $method     = $getwh == null ? "post" : "put";
        $action     = $getwh == null  ? 'Warehouse\WarehouseInController@store' : ['Warehouse\WarehouseInController@update', $id];

        $cust       = "";
        return view('warehouse.migrate.show', [
            'main'     => $main,
            'product'  => $product,
            'vend'     => $vend,
            'cust'     => $cust,
            'idwh'     => $idwh,
            'alamat'   => $altaddress,
            'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
            'method'   => $method,
            'action'   => $action,
        ]);
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

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'po_number',
            2 => 'id_quo',
            3 => 'id_vendor',
            4 => 'status',
            5 => 'position',
            6 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = PurchaseMigrate::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = PurchaseMigrate::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = PurchaseMigrate::where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = PurchaseMigrate::where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($post->status == "order") {
                    if (is_null(getWarehouse("id_purchase", $post->id))) {
                        $position = "Diproses Vendor";
                    } else {
                        if (getWarehouse("id_purchase", $post->id)->status == 'partial') {
                            $position = "Masuk Sebagian";
                        } else {
                            $position = "Return";
                        }
                    }
                } else {
                    $position = "Done";
                }
                $data[] = [
                    'id'         => $post->id,
                    'po_number'  => $post->po_number,
                    'id_quo'     => $post->id_quo,
                    'id_vendor'  => getVendor($post->id_vendor)->vendor_name,
                    'status'     => $post->status,
                    'position'   => $position,
                    'created_at' => $post->created_at->format('Y-m-d'),
                ];
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
}
