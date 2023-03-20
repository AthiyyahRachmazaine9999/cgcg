<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActQuoModel;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\warehouse_out;
use App\Models\Warehouse\Warehouse_address;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WarehouseInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouse.index');
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
        if ($request->type == "stock purchase") {
            $id_cust   = getCustWh($request->id_po)->id_customer;
        } else {
            $id_cust   = getQuo($request->id_quo)->id_customer;
        }
        $look    = Purchase_order::where('id', $request->id_po)->first();
        $status  = count($request->terima) == count($request->id_product) ? "in" : "partial";
        $data = [
            'id_quo'      => $request->id_quo,
            'id_purchase' => $request->id_po,
            'id_vendor'   => $request->id_vendor,
            'id_cust'     => $id_cust,
            'status'      => $status,
            'note_in'     => $request->note_in,
            'note_out'    => null,
            'created_by'  => Auth::id(),
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
        ];
        $qry = Warehouse_order::create($data);
        if ($qry) {
            $check = warehouse_out::where('id_quo',$request->id_quo)->first();
            if ($check == null) {
                $data_quo = [
                    'id_quo'      => $request->id_quo,
                    'id_cust'     => $id_cust,
                    'created_by'  => Auth::id(),
                    'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
                ];
                warehouse_out::insert($data_quo);
            }
            $data = [];
            $terima = $request->terima;
            foreach ($terima as $item => $v) {
                $types = array_search($v, $request->type_sesuai) !== false ? "yes" : "no";
                $qtys  = array_search($v, $request->qty_sesuai) !== false ? "yes" : "no";
                $index = array_search($v, $request->id_product);

                $data = [
                    'id_wo'        => $qry->id,
                    'id_quo'       => $request->id_quo,
                    'id_product'   => $request->id_product[$index],
                    'qty'          => $request->qty[$index],
                    'qty_check'    => $qtys,
                    'qty_note'     => $request->qty_error[$index],
                    'status_check' => $types,
                    'status_note'  => $request->type_error[$index],
                    'created_by'   => Auth::id(),
                    'created_at'   => Carbon::now('GMT+7')->toDateTimeString()
                ];
                Warehouse_detail::insert($data);
                if ($status <> 'in') {
                    $log = array(
                        'activity_id_quo'       => $look->id_quo,
                        'activity_id_user'      => Auth::id(),
                        'activity_name'         => "Barang " . getProductDetail((getProductQuo($request->id_product[$index]))->id_product)->name . " sudah diterima gudang ",
                        'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                    );
                    ActQuoModel::insert($log);
                }
            }
            if ($status == 'in') {
                $log = array(
                    'activity_id_quo'       => $look->id_quo,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Semua barang sudah diterima digudang ",
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                ActQuoModel::insert($log);
            }
            return redirect("warehouse/inbound/" . $look->po_number)->with('success', $look->po_number . ' berhasil di ' . $request->type);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $main       = Purchase_order::where('po_number', $id)->first();
        $vend       = VendorModel::where('id', $main->id_vendor)->first();
        $product    = Purchase_detail::where('id_po', $main->id)->get();
        $altaddress = Purchase_address::where('id_po', $main->id)->first();
        $getwh      = getWarehouse("id_purchase", $main->id);
        $idwh       = $getwh == null ? "" : $getwh->id;
        $method     = $getwh == null ? "post" : "put";
        $action     = $getwh == null  ? 'Warehouse\WarehouseInController@store' : ['Warehouse\WarehouseInController@update', $id];
        if ($main->type == "stock purchase") {
            $cust1      = getCustomer($main->id_customer);
            return view('warehouse.showIn_purchase', [
                'main'     => $main,
                'product'  => $product,
                'vend'     => $vend,
                'cust'     => $cust1,
                'idwh'     => $idwh,
                'alamat'   => $altaddress,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
                'method'   => $method,
                'action'   => $action,
            ]);
        } else {
            $cust       = getCustomer(getQuo($main->id_quo)->id_customer);
            return view('warehouse.show', [
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
        $look    = Purchase_order::where('id', $request->id_po)->first();
        $status  = count($request->terima) == count($request->id_product) ? "in" : "partial";
        $data = [
            'status'      => $status,
            'created_by'  => Auth::id(),
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Warehouse_order::where('id', $request->idwh)->update($data);

        $check = warehouse_out::where('id_quo',$request->id_quo)->first();
        if ($check == null) {
            $data_quo = [
                'id_quo'      => $request->id_quo,
                'id_cust'     => getQuo($request->id_quo)->id_customer,
                'created_by'  => Auth::id(),
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
            ];
            warehouse_out::insert($data_quo);
        }
        $data = [];
        $terima = $request->terima;

        foreach ($terima as $item => $v) {

            $types = array_search($v, $request->type_sesuai) !== false ? "yes" : "no";
            $qtys  = array_search($v, $request->qty_sesuai) !== false ? "yes" : "no";
            $index = array_search($v, $request->id_product);

            $doing = Warehouse_detail::where('id_product', $v)->first() == null ? "created" : "updated";

            $data = [
                'id_wo'        => $request->idwh,
                'id_quo'       => $request->id_quo,
                'id_product'   => $request->id_product[$index],
                'qty'          => $request->qty[$index],
                'qty_check'    => $qtys,
                'qty_note'     => $request->qty_error[$index],
                'status_check' => $types,
                'status_note'  => $request->type_error[$index],
                $doing . '_by'   => Auth::id(),
                $doing . '_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Warehouse_detail::where('id_product', $v)->first() == null ? Warehouse_detail::insert($data) : Warehouse_detail::where('id_product', $v)->update($data);
            if ($status <> 'in') {
                $log = array(
                    'activity_id_quo'       => $look->id_quo,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Barang " . getProductDetail((getProductQuo($request->id_product[$index]))->id_product)->name . " sudah diterima gudang ",
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                ActQuoModel::insert($log);
            }
        }
        if ($status == 'in') {
            $log = array(
                'activity_id_quo'       => $look->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Semua barang sudah diterima digudang ",
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            ActQuoModel::insert($log);
        }
        
        return redirect("warehouse/inbound/" . $look->po_number)->with('success', $look->po_number . ' penerimaan berhasil di update ');
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

        $menu_count    = Purchase_order::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Purchase_order::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = Purchase_order::where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Purchase_order::where('po_number', 'like', '%' . $search . '%')
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
                    $position = "Menunggu Purchase";
                }
                $data[] = [
                    'id'         => $post->id,
                    'po_number'  => $post->po_number,
                    'id_quo'     => 'SO' . sprintf("%06d", $post->id_quo),
                    'id_vendor'  => getVendor($post->id_vendor)->vendor_name,
                    'status'     => $post->status,
                    'position'   => $position,
                    'created_at' => $post->updated_at->format('Y-m-d'),
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
