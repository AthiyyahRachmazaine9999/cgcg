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
use App\Models\Sales\SalesMigrationProduct;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WarehouseOutMigrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouse.migrate.index_out');
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
        $main     = WarehouseMigrateOut::where('id', $id)->first();
        $product  = SalesMigrationProduct::select('quotation_product_old.*', 'd.kirim_status', 'd.kirim_addr')
            ->where('quotation_product_old.id_quo', $main->id_quo)
            ->leftjoin('warehouse_details_old as d', 'quotation_product_old.id', '=', 'd.id_product')->get();
        $altaddress = Warehouse_address::where('id_quo', $main->id_quo)->get();
        $method     = "post";
        $action     = 'Warehouse\WarehouseOutController@store';
        $cust       = getCustomer($main->id_cust);
        return view('warehouse.migrate.show_out', [
            'main'     => $main,
            'product'  => $product,
            'cust'     => $cust,
            'alamat'   => $altaddress,
            'address'  => $this->get_address($main->id_quo, $main->id_cust),
            'cadress'  => [$main->id_cust => getCustomer($main->id_cust)->company],
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
            0 => 'id_quo',
            1 => 'id_cust',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = WarehouseMigrateOut::get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = WarehouseMigrateOut::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search = $request->input('search')['value'];
            $posts         = WarehouseMigrateOut::search($order, $dir, $limit, $start, $search)->get();
            $totalFiltered = WarehouseMigrateOut::countsearch($search)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $check = SearchActivity($post->id_quo, "resi") == null ? '0000-00-00' : SearchActivity($post->id_quo, "resi")->activity_created_date;
                $kirim = SearchActivity($post->id_quo, "resi") == null ? 'waiting' : 'delivery';
                $data[] = [
                    'id'         => $post->id,
                    'no_wo'      => $post->id,
                    'id_quo'     => $post->ref,
                    'id_cust'    => getCustomer($post->id_cust)->company,
                    'barang'     => countproduct($post->id_quo),
                    'status'     => $kirim,
                    'created_at' => $check,
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

    public function get_address($id, $id_cust)
    {
        $arr = array();
        $data = Warehouse_address::where('id_wo', $id)->get();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->name . " - " . $reg->address;
        }
        $arr_cust = [$id_cust . 'x' => getCustomer($id_cust)->company];
        $vals     = $arr_cust + $arr;
        return $vals;
    }
}
