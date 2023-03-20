<?php

namespace App\Http\Controllers\Warehouse\update;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActQuoModel;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\warehouse_out;
use App\Models\Warehouse\Warehouse_address;
use App\Models\Warehouse\Warehouse_pengiriman;
use App\Models\Warehouse\Warehouse_history;
use App\Models\Warehouse\Warehouse_resi;
use App\Models\WarehouseUpdate\WarehouseIn;
use App\Models\WarehouseUpdate\WarehouseInDetail;
use App\Models\WarehouseUpdate\WarehouseInboundHistory;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use DB;

class WarehouseInboundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('warehouse_update.inbound.index');
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


    public function show($id)
    {
        $main       = Purchase_order::where('po_number', $id)->first();
        $vend       = VendorModel::where('id', $main->id_vendor)->first();
        $product    = Purchase_detail::where('id_po', $main->id)->get();
        $altaddress = Purchase_address::where('id_po', $main->id)->first();
        $getwh      = getWarehouse("id_purchase", $main->id);
        $wh_in      = getWarehouseIn("id_po", $main->id);
        $idwh       = $wh_in == null ? "" : $wh_in->id;
        $method     = $wh_in == null ? "post" : "put";
        $action     = $wh_in == null  ? 'Warehouse\update\WarehouseInboundController@store' : ['Warehouse\update\WarehouseInboundController@update', $id];
        $cust1      = $main->type == "stock purchase" ? getCustomer($main->id_customer) : getCustomer(getQuo($main->id_quo)->id_customer);
        $history    = $wh_in==null ? null: WarehouseInboundHistory::where('id_inbound', $wh_in->id)->orderby('created_at', 'desc')->limit(2)->get();
        if ($main->type == "stock purchase") {
            $cust1      = getCustomer($main->id_customer);
            return view('warehouse_update.inbound.show_inpurchase', [
                'main'     => $main,
                'product'  => $product,
                'vend'     => $vend,
                'cust'     => $cust1,
                'idwh'     => $idwh,
                'wh_in'    => $wh_in,
                'history'  => $history,
                'alamat'   => $altaddress,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
                'method'   => $method,
                'action'   => $action,
            ]);
        }else{
            return view('warehouse_update.inbound.show', [
                'main'     => $main,
                'product'  => $product,
                'vend'     => $vend,
                'cust'     => $cust1,
                'idwh'     => $idwh,
                'wh_in'    => $wh_in,
                'history'  => $history,
                'alamat'   => $altaddress,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
                'method'   => $method,
                'action'   => $action,
            ]);
        }
    }



    public function history (Request $request)
    {
        $history    = WarehouseInboundHistory::where('id_inbound', $request->id)->orderby('created_at', 'desc')->get();
        return view('warehouse_update.inbound.all_history', [
            'act'    => $history,
        ]);
    }


    public function add_note (Request $request)
    {
        return view('warehouse_update.inbound.add_note', [
            'id_in'  => $request->id,
            'method' => "post",
            'action' => 'Warehouse\update\WarehouseInboundController@save_addnote',
        ]);
    }


    public function save_addnote (Request $request)
    {
        $wh_in   = WarehouseIn::where('id', $request->id)->first();
        $look    = Purchase_order::where('id', $wh_in->id_po)->first();
        $hist = [
            'id_inbound'  => $request->id,
            'activity'    => $request->activity,
            'created_by'  => Auth::id(),
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $hist = WarehouseInboundHistory::create($hist);
        return redirect("warehouse/warehouse_inbound/" . $look->po_number)->with('success', $look->po_number . ' penerimaan berhasil di update ');
    }



    public function store(Request $request)
    {
        // dd($request);
        if ($request->type == "stock purchase") {
            $id_cust   = getCustWh($request->id_po)->id_customer;
        } else {
            $id_cust   = getQuo($request->id_quo)->id_customer;
        }
        
        $wh_in   = WarehouseIn::where([['id_quo', $request->id_quo], ['id_po', $request->id_po]])->first();
        $terimas = $request->terima;
        $returns = $request->return;
        $look    = Purchase_order::where('id', $request->id_po)->first();
        $status  = array_sum($request->qty_po) == array_sum($request->qty_terima) ? "in" : "partial";
        $data    = [
            'id_po'       => $request->id_po,
            'type_po'     => $request->type_po,
            'created_by'  => Auth::id(),
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
        ];
        $qry = WarehouseIn::create($data);
        if($request->has('terima')) {
        foreach ($terimas as $terima => $t)
            {
                $index = array_search($t, $request->id_dum);
                $ch    = WarehouseInDetail::where([['id_inbound', $qry->id], ['sku', $request->id_product[$index]],['id_quo', $request->quos[$index]]])->first();
                $detail_terima = [
                    'id_inbound'   => $qry->id,
                    'id_quo'       => $request->quos[$index],
                    'qty_terima'   => $request->qty_terima[$index],
                    'sku'          => $request->id_product[$index],
                    'id_quo'       => $request->quos[$index],
                    'qty_po'       => $request->qty_po[$index],
                    'qty_problem'  => $request->qty_problem[$index],
                    'note_problem' => $request->note_problem[$index],
                    'created_by'   => Auth::id(),
                    'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                $qrys = WarehouseInDetail::create($detail_terima);
                    if($ch==null) {
                        $hist = [
                            'id_inbound'  => $qry->id,
                            'qty_terima'  => $request->qty_terima[$index],
                            'qty_problem' => $request->qty_problem[$index],
                            'activity'    => "Barang telah di terima",
                            'sku'         => $request->id_product[$index],
                            'created_by'  => Auth::id(),
                            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                        }

                if ($status <> 'in') {
                    $log = array(
                        'activity_id_quo'       => $look->id_quo,
                        'activity_id_user'      => Auth::id(),
                        'activity_name'         => "Barang " . getProductDetail($request->id_product[$index])->name . " sudah diterima gudang ",
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
    }
         return redirect("warehouse/warehouse_inbound/" . $look->po_number)->with('success', $look->po_number . ' penerimaan berhasil di update ');
    }


    public function update(Request $request, $id)
    {
        // dd($request);
        $wh_in     = WarehouseIn::where([['id_po', $request->id_po]])->first();
        $look      = Purchase_order::where('id', $request->id_po)->first();
        $wh_in_dtl = WarehouseInDetail::where('id_inbound', $wh_in->id)->get();
        $terimas   = $request->terima;
        $returns   = $request->return;
        $look      = Purchase_order::where('id', $request->id_po)->first();
        $status    = array_sum($request->qty_po) == array_sum($request->qty_terima) ? "in" : "partial";
        if(count($wh_in_dtl)<count($terimas))
        {
                foreach ($terimas as $terima => $t)
                {
                    $index   = array_search($t, $request->id_dum);
                    $ch      = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]], ['id_quo', $request->quos[$index]]])->first();
                    $terima  = array_filter(array_values($request->qty_terima));
                    $problem = array_filter(array_values($request->qty_problem));
                    if($ch==null){
                    $detail_terima = [
                        'id_inbound'  => $wh_in->id,
                        'qty_terima'  => $terima[$index],
                        'sku'         => $request->id_product[$index],
                        'id_quo'      => $request->quos[$index],
                        'qty_po'      => $request->qty_po[$index],
                        'qty_problem' => $request->qty_problem[$index],
                        'note_problem'=> $request->note_problem[$index],
                        'created_by'  => Auth::id(),
                        'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    // dd($request, $detail_terima);
                    $qrys = WarehouseInDetail::create($detail_terima);
                    }else{
                    $detail_terima = [
                        'id_inbound'  => $wh_in->id,
                        'qty_terima'  => $request->qty_terima[$index],
                        'sku'         => $request->id_product[$index],
                        'id_quo'      => $request->quos[$index],
                        'qty_po'      => $request->qty_po[$index],
                        'qty_problem' => $request->qty_problem[$index],
                        'note_problem'=> $request->note_problem[$index],
                        'created_by'  => Auth::id(),
                        'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    $qrys = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]], ['id_quo', $request->quos[$index]]])->update($detail_terima);
                    }
                    if($ch!=null) {
                        if($request->qty_terima[$index]!=$ch->qty_terima || $request->qty_problem[$index]!= $ch->qty_problem){
                        $hist = [
                            'id_inbound' => $wh_in->id,
                            'qty_terima' => $request->qty_terima[$index],
                            'qty_problem'=> $request->qty_problem[$index],
                            'activity'   => "Update penerimaan barang",
                            'sku'        => $request->id_product[$index],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                        }
                    }else{
                        $hist = [
                            'id_inbound' => $wh_in->id,
                            'qty_terima' => $request->qty_terima[$index],
                            'qty_problem'=> $request->qty_problem[$index],
                            'activity'   => "Update penerimaan barang",
                            'sku'        => $request->id_product[$index],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                    }
                if ($status <> 'in') {
                    $log = array(
                        'activity_id_quo'       => $look->id_quo,
                        'activity_id_user'      => Auth::id(),
                        'activity_name'         => "Barang " . getProductDetail($request->id_product[$index])->name . " sudah diterima gudang ",
                        'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                    );
                    ActQuoModel::insert($log);
                }
                }
        }else{
            // dd($request);
                foreach ($terimas as $terima => $t)
                {
                    $index   = array_search($t, $request->id_dum);
                    $ch    = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]], ['id_quo', $request->quos[$index]]])->first();
                    $check = $ch==null ? WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]]])->first() : $ch;
                    $detail_terima = [
                        'id_inbound'  => $wh_in->id,
                        'id_quo'      => $request->quos[$index],
                        'qty_terima'  => $request->qty_terima[$index],
                        'sku'         => $request->id_product[$index],
                        'qty_po'      => $request->qty_po[$index],
                        'qty_problem' => $request->qty_problem[$index],
                        'note_problem'=> $request->note_problem[$index],
                    ];
                    $qrys = $ch==null ? WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]]])->update($detail_terima) : WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]], ['id_quo', $request->quos[$index]]])->update($detail_terima);
                    if($check!=null) {
                        if($request->qty_terima[$index]!=$check->qty_terima || $request->qty_problem[$index]!= $check->qty_problem){
                        $hist = [
                            'id_inbound' => $wh_in->id,
                            'qty_terima' => $request->qty_terima[$index],
                            'qty_problem'=> $request->qty_problem[$index],
                            'activity'   => "Update penerimaan barang",
                            'sku'        => $request->id_product[$index],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                        }
                    }else
                    {
                        $hist = [
                            'id_inbound' => $wh_in->id,
                            'qty_terima' => $request->qty_terima[$index],
                            'qty_problem'=> $request->qty_problem[$index],
                            'activity'   => "Update penerimaan barang",
                            'sku'        => $request->id_product[$index],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                    }

                if ($status <> 'in') {
                    $log = array(
                        'activity_id_quo'       => $look->id_quo,
                        'activity_id_user'      => Auth::id(),
                        'activity_name'         => "Barang " . getProductDetail($request->id_product[$index])->name . " sudah diterima gudang ",
                        'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                    );
                    ActQuoModel::insert($log);
                }
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
        
            return redirect("warehouse/warehouse_inbound/" . $look->po_number)->with('success', $look->po_number . ' penerimaan berhasil di update ');
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