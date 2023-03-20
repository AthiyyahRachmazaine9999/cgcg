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
use App\Models\Warehouse\Warehouse_pengiriman;
use App\Models\Warehouse\Warehouse_history;
use App\Models\Warehouse\Warehouse_resi;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use DB;

class WarehouseOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouse.index_out');
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
        $look    = Warehouse_order::where('id', $request->id_wo)->first();
        $status  = count($request->siapkirim) == count($request->id_product) ? "all" : "partial";
        $data = [
            'status_kirim' => $status,
            'updated_by'   => Auth::id(),
            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Warehouse_order::where('id', $request->id_wo)->update($data);

        $data = [];
        $terima = $request->siapkirim;

        foreach ($terima as $item => $v) {

            $siap  = array_search($v, $request->siapkirim) !== false ? "yes" : "no";
            $index = array_search($v, $request->id_product);

            $data = [
                'id_product'   => $request->id_product[$index],
                'qty_kirim'    => $request->qty[$index],
                'kirim_status' => $siap,
                'kirim_note'   => $request->kirim_note[$index],
                'kirim_addr'   => $request->address[$index],
                'updated_by'   => Auth::id(),
                'updated_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];

            Warehouse_detail::where('id_product', $v)->update($data);
            if ($status <> 'all') {
                $log = array(
                    'activity_id_quo'       => $request->id_quo,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Barang " . getProductDetail((getProductQuo($request->id_product[$index]))->id_product)->name . " sudah dikirim dari gudang, menunggu resi diinput ",
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                ActQuoModel::insert($log);
            }
        }
        if ($status == 'all') {
            $log = array(
                'activity_id_quo'       => $request->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Semua barang sudah dikirim dari gudang sudah dikirim dari gudang, menunggu resi diinput",
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            ActQuoModel::insert($log);
        }
        return redirect("warehouse/outbound/" . $request->id_wo)->with('success', ' barang sudah dikirim ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $main     = warehouse_out::where('id', $id)->first();
        $product  = QuotationProduct::select('quotation_product.*', 'd.kirim_status', 'd.kirim_addr')
            ->where('quotation_product.id_quo', $main->id_quo)
            ->leftjoin('warehouse_details as d', 'quotation_product.id', '=', 'd.id_product')->groupBy('id_product')->get();
        
        $altaddress = Warehouse_address::where('id_quo', $main->id_quo)->get();
        $method     = "post";
        $action     = 'Warehouse\WarehouseOutController@store';
        if ($main->id_quo == 0) {
            $cust     = getCustomer($main->id_cust);
            return view('warehouse.showOut_purchase', [
                'main'     => $main,
                'product'  => $product,
                'cust'     => $cust,
                'alamat'   => $altaddress,
                'address'  => $this->get_address($main->id_quo, $main->id_cust),
                'cadress'  => [$main->id_cust => getCustomer($main->id_cust)->company],
                'method'   => $method,
                'action'   => $action,
            ]);
        } else {
            $cust       = getCustomer(getQuo($main->id_quo)->id_customer);
            return view('warehouse.show_out', [
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
        dd($request);
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

    public function CetakDO(Request $request)
    {
        $main      = warehouse_out::where('id', $request->id)->first();
        $getpro    = $request->type == "utama" ? $request->alamat . 'x' : $request->alamat;
        $product   = Warehouse_detail::where([
            ['kirim_addr', $getpro],
            ['id_quo', $main->id_quo],
        ])->get();
        $id_alamat = $request->type == "utama" ? getCustomer($request->alamat) : Warehouse_address::where('id', $request->alamat)->first();
        $pdf = PDF::loadview('pdf.warehouse_delivery', [
            'main'    => $main,
            'type'    => $request->type,
            'add'     => $id_alamat,
            'product' => $product,
            'time'    => Carbon::now('GMT+7')->format('d F Y')
        ]);
        return $pdf->stream('MEG - DO.pdf');
    }

    public function ganti_alamat(Request $request)
    {
        $idwo = $request->idwo;
        $det  = $request->det;
        $adr  = Warehouse_address::where('id', $det)->first();

        $names  = $request->type == "new" ? '' : $adr->name;
        $addre  = $request->type == "new" ? '' : $adr->address;
        $method = $request->type == "new" ? "post" : "put";
        $action = $request->type == "new" ? 'Warehouse\WarehouseOutController@save_alamat' : ['Warehouse\WarehouseOutController@update_alamat', $adr->id];
        // dd($view);
        return view('warehouse.attribute.warehouse_tambahalamat', [
            'idwo'   => $idwo,
            'idadd'  => $det,
            'names'  => $names,
            'addre'  => $addre,
            'method' => $method,
            'action' => $action,
        ]);
    }

    public function save_alamat(Request $request)
    {
        $alamat  = [
            'id_wo'      => $request->idwo,
            'id_quo'     => $request->idwo,
            'name'       => $request->name,
            'address'    => $request->address,
            'created_by' => Auth::id()
        ];
        $qry = Warehouse_address::create($alamat);

        $update_wo  = [
            'pengiriman'     => "multiple",
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Warehouse_order::where('id', $request->idwo)->update($update_wo);
        
        $look = warehouse_out::where('id_quo',$request->idwo)->first();
        warehouse_out::where('id', $look->id)->update($update_wo);

        $log = array(
            'activity_id_quo'       => $request->idwo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Menambahkan alamat pengiriman untuk delivery order " .  $request->name . " " . $request->address,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        return redirect("warehouse/outbound/" . $look->id)->with('success', ' Tambah alamat pengiriman lain berhasil');
    }

    public function update_alamat(Request $request)
    {
        $alamat  = [
            'name'       => $request->name,
            'address'    => $request->address,
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Warehouse_address::where('id', $request->idadd)->update($alamat);
        $look = warehouse_out::where('id_quo',$request->idwo)->first();

        $log = array(
            'activity_id_quo'       => $request->idwo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Merubah alamat pengiriman untuk delivery order ".  $request->name . " " . $request->address,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return redirect("warehouse/outbound/" . $look->id)->with('success',' Ubah alamat pengiriman berhasil');
    }

    public function save_resi(Request $request)
    {
        $id_address = $request->type == "main" ? $request->alamat . "x" : $request->alamat;
        $check      = Warehouse_resi::where('id_address', $id_address)->first();
        $kondisi    = $check == null ? "created" : "updated";

        $data    = [
            'id_wo'        => $request->idwo,
            'id_address'   => $id_address,
            'id_forwarder' => $request->kurir,
            'resi'         => $request->resi,
            $kondisi . '_by' => Auth::id(),
            $kondisi . '_at' => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $go = $check == null ? Warehouse_resi::create($data) : Warehouse_resi::where('id', $check->id)->update($data);

        $alamat = $request->type == "main" ? getCustomer($request->alamat)->company : Warehouse_address::where('id', $request->alamat)->first()->name;
        $look   = Warehouse_order::where('id', $request->idwo)->first();
        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Pengiriman ke " . $alamat . " menggunakan " . getForwarder($request->kurir)->company . " dengan Resi nomer " . $request->resi,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return $kondisi . " resi berhasil";
    }

    public function delete_alamat(Request $request)
    {
        $id    = $request->det;
        $name  = Warehouse_address::where('id', $id)->first()->name;
        $todo  = Warehouse_address::where('id', $id)->delete();
        $check = Warehouse_address::where('id_wo', $request->idwo)->count();
        $pengiriman = $check >= 1 ? "multiple" : null;

        $update_wo  = [
            'pengiriman'     => $pengiriman,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Warehouse_order::where('id', $request->idwo)->update($update_wo);
        $look  = Warehouse_order::where('id', $request->idwo)->first();

        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Delete alamat pengiriman untuk delivery order " . $name,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return "warehouse/outbound/" . $look->id;
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

        $menu_count    = warehouse_out::get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = warehouse_out::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = warehouse_out::search($order, $dir, $limit, $start, $search)->get();
            $totalFiltered = warehouse_out::countsearch($search)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $check = SearchActivity($post->id_quo, "resi") == null ? '0000-00-00' : SearchActivity($post->id_quo, "resi")->activity_created_date;
                $kirim = SearchActivity($post->id_quo, "resi") == null ? 'waiting' : 'delivery';
                $data[] = [
                    'id'         => $post->id,
                    'no_wo'      => 'WH/OUT/21/' . $post->id,
                    'id_quo'     => 'SO' . sprintf("%06d", $post->id_quo),
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


    public function finish(Request $request)
    {
        $check   = Warehouse_resi::where([
            ['id_wo', $request->idwo],
            ['id_address', $request->alamat]
        ])->first();
        if ($check == null) {
            $alert = '<div class="alert alert-danger alert-styled-left alert-dismissible">
            <span class="font-weight-semibold">Maaf</span> Anda belum mengisi nomer resi untuk pengiriman ke alamat ini
        </div>';
            return $alert;
        } else {
            return view('warehouse.attribute.warehouse_finish', [
                'check'  => $check,
                'type'   => $request->type,
                'method' => "post",
                'action' => 'Warehouse\WarehouseOutController@save_finish',
            ]);
        }
    }

    public function save_finish(Request $request)
    {
        $look  = Warehouse_detail::where('kirim_addr', $request->id_address)->first();

        $id_quo   = $request->id_quo;
        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Barang " . getProductDetail((getProductQuo($look->id_product))->id_product)->name . " sudah diterima oleh " . $request->penerima . ", " . $request->note,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return redirect("warehouse/outbound/" . $look->id_wo)->with('success', ' sudah diterima');
    }
}
