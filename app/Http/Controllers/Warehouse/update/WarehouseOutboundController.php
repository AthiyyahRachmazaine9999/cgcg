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
use App\Models\WarehouseUpdate\warehouseIn;
use App\Models\WarehouseUpdate\WarehouseInDetail;
use App\Models\WarehouseUpdate\WarehouseInboundHistory;
use App\Models\WarehouseUpdate\WarehouseOut;
use App\Models\WarehouseUpdate\WarehouseOutDetail;
use App\Models\WarehouseUpdate\WarehouseOutHistory;
use App\Models\WarehouseUpdate\WarehouseDoc;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Storage;
use PDF;
use DB;

class WarehouseOutboundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('warehouse_update.outbound.index');
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
        $main      = WarehouseOut::where('id', $id)->first();
        // dd($main, $id);
        $product   = QuotationProduct::select('quotation_product.*', 'warehouse_outbound.*')
            ->where('quotation_product.id_quo', $main->id_quo)
            ->join('warehouse_outbound', 'warehouse_outbound.id_quo', '=', 'quotation_product.id_quo')
            ->get();
        $terima    = QuotationProduct::select('quotation_product.*', 'd.*')
            ->where('quotation_product.id_quo', $main->id_quo)
            ->leftjoin('warehouse_details as d', 'quotation_product.id', '=', 'd.id_product')->get()->sum('qty');
        $kirim     = QuotationProduct::select('quotation_product.*', 'd.*')
            ->where('quotation_product.id_quo', $main->id_quo)
            ->leftjoin('warehouse_details as d', 'quotation_product.id', '=', 'd.id_product')->get()->sum('qty_kirim');
        $max_krm   = Warehouse_pengiriman::where('id_quo', $main->id_quo)->get()->sum('qty_kirim');
        $pengiriman = QuotationProduct::select('quotation_product.*', 'd.kirim_addr', 'd.id_wh_out', 'd.qty_asal', 'd.qty_kirim')
            ->where('quotation_product.id_quo', $main->id_quo)
            ->leftjoin('warehouse_pengiriman as d', 'quotation_product.id', '=', 'd.id_product')->get();
        $head       = WarehouseOutDetail::where('id_outbound', $id)->groupBy('no_do')->get();
        $altaddress = Warehouse_address::where('id_quo', $main->id_quo)->get();
        $method     = "post";
        $history    = WarehouseOutHistory::where('id_outbound', $id)->orderBy('id', 'desc')->limit(5)->get();
        $action     = 'Warehouse\WarehouseOutController@store';
        if ($main->id_quo == 0) {
            return view('warehouse_update.outbound.show_outpurchase', [
                'main'       => $main,
                'product'    => $product,
                'cust'       => $cust,
                'alamat'     => $altaddress,
                'pengiriman' => $pengiriman,
                'head'       => $head,
                'history'    => $history,
                'address'    => $this->get_address($main->id_quo, getQuo($main->id_quo)->id_customer),
                'cadress'    => [$main->id_cust => getCustomer(getQuo($main->id_quo)->id_customer)->company],
                'method'     => $method,
                'action'     => $action,
            ]);
        } else {
            $cust       = getCustomer(getQuo($main->id_quo)->id_customer);
            return view('warehouse_update.outbound.show', [
                'main'       => $main,
                'product'    => $product,
                'cust'       => $cust,
                'pengiriman' => $pengiriman,
                'head'       => $head,
                'history'    => $history,
                'alamat'     => $altaddress,
                'address'    => $this->get_address($main->id_quo, getQuo($main->id_quo)->id_customer),
                'cadress'    => [$main->id_cust => getCustomer(getQuo($main->id_quo)->id_customer)->company],
                'method'     => $method,
                'action'     => $action,
            ]);
        }
    }


    public function editbarang_pengiriman(Request $request)
    {
        $main   = WarehouseOutDetail::where([['warehouse_outbound_detail.no_do', $request->no_wh_out], ['id_outbound', $request->id_wh_out]])->get();
        $wh_out = WarehouseOut::select('quotation_models.*', 'warehouse_outbound.*', 'warehouse_outbound.id as id_outbounds')->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->where('warehouse_outbound.id', $request->id_wh_out)->first();
        $first  = WarehouseOutDetail::where([['no_do', $request->no_wh_out], ['id_outbound', $request->id_wh_out]])->orderBy('id', 'desc')->first();

        return view('warehouse_update.attribute.edit_modal_cetak', [
            'main'     => $main,
            'no_do'    => $request->no_wh_out,
            'first'    => $first,
            'wh_out'   => $wh_out,
            'add'      => $first->type_alamat == "utama" ? getCustomer($first->id_alamat)->company . ' - ' . getCustomer($first->id_alamat)->address : WarehouseAddress($first->id_alamat)->name . ' - ' . WarehouseAddress($first->id_alamat)->address,
            'address'  => $this->get_address($wh_out->id_quo, getQuo($wh_out->id_quo)->id_customer),
            'vaddress' => [$wh_out->id_customer => getCustomer(getQuo($wh_out->id_quo)->id_customer)->company],
            'method'   => "post",
        ]);
    }

    public function add_row(Request $request)
    {
        // dd($request);
        $n_equ   = $request->n_equ;
        return view('warehouse_update.attribute.add_row', [
            'n_equ'   => $n_equ,
            'product' => $this->getProduct($request->quo),
        ]);
    }

    public function remove_row(Request $request)
    {
        // dd($request);
        $dtl = WarehouseOutDetail::where('id', $request->id)->first();
        $history_out = [
            'id_outbound' => $dtl->id_outbound,
            'no_do'       => $dtl->no_do,
            'activity'    => "Menghapus barang " . getProductDetail($dtl->sku)->name . " dari pengiriman",
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        WarehouseOutHistory::create($history_out);
        DB::table('warehouse_outbound_detail')->where('id', $request->id)->delete();

        return response()->json(
            [
                'success'   => true,
                'n_equ'     => $request->id,
            ]
        );
    }


    public function history_outbound(Request $request)
    {
        $history    = WarehouseOutHistory::where('id_outbound', $request->id)->orderBy('id', 'desc')->get();
        return view('warehouse_update.outbound.all_history', [
            'act'    => $history,
        ]);
    }


    public function add_notes(Request $request)
    {
        $wh_out = WarehouseOut::where('id', $request->id)->first();
        return view('warehouse_update.outbound.add_note', [
            'id_out' => $request->id,
            'method' => "post",
            'action' => 'Warehouse\update\WarehouseOutboundController@save_addnote',
        ]);
    }


    public function save_addnote(Request $request)
    {
        // dd($request);
        $wh = WarehouseOut::where('id', $request->id)->first();
        $history_out = [
            'id_outbound' => $request->id,
            'no_do'       => $wh->no_do,
            'activity'    => $request->activity,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        WarehouseOutHistory::create($history_out);
        return redirect("warehouse/warehouse_outbound/" . $request->id)->with('success', 'History Created Successfully');
    }


    public function DO_cetak(Request $request)
    {
        $main   = WarehouseOutDetail::where([['warehouse_outbound_detail.no_do', $request->no_wh_out], ['id_outbound', $request->id_wh_out]])->get();
        $wh_out = WarehouseOut::select('quotation_models.*', 'warehouse_outbound.*', 'warehouse_outbound.id as id_outbounds')->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')->where('warehouse_outbound.id', $request->id_wh_out)->first();
        $first  = WarehouseOutDetail::where([['no_do', $request->no_wh_out], ['id_outbound', $request->id_wh_out]])->orderBy('id', 'desc')->first();
        $view   = $request->type == "no_up" ? "editno_modal_cetak" : "tab_modal_cetak";

        return view('warehouse_update.attribute.' . $view, [
            'main'     => $main,
            'no_do'    => $request->no_wh_out,
            'first'    => $first,
            'wh_out'   => $wh_out,
            'add'      => $first->type_alamat == "utama" ? getCustomer($first->id_alamat)->company . ' - ' . getCustomer($first->id_alamat)->address : WarehouseAddress($first->id_alamat)->name . ' - ' . WarehouseAddress($first->id_alamat)->address,
            'address'  => $this->get_address($wh_out->id_quo, getQuo($wh_out->id_quo)->id_customer),
            'vaddress' => [$wh_out->id_customer => getCustomer(getQuo($wh_out->id_quo)->id_customer)->company],
            'method'   => "post",
        ]);
    }



    public function DO_delete(Request $request)
    {
        $wh = WarehouseOut::where('id', $request->id_wh_out)->first();
        $log = array(
            'activity_id_quo'       => $wh->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Menghapus No. DO " . $request->no_wh_out . " dengan keterangan " . $request->value,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $history_out = [
            'id_outbound' => $request->id_wh_out,
            'no_do'       => $request->no_wh_out,
            'activity'    => "Menghapus No. DO " . $request->no_wh_out . " dengan keterangan " . $request->value,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        WarehouseOutHistory::create($history_out);

        $wh_hist = [
            'id_quo'        => $wh->id_quo,
            'id_wo'         => $request->id_wh_out,
            'no_wh_out'     => $request->no_wh_out,
            'activity_name' => "Menghapus No. DO " . $request->no_wh_out . " dengan keterangan " . $request->value,
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'    => Auth::id(),
        ];
        $act_wh = Warehouse_history::create($wh_hist);

        DB::table('warehouse_outbound_detail')
            ->where([['warehouse_outbound_detail.no_do', $request->no_wh_out], ['id_outbound', $request->id_wh_out]])
            ->delete();

        return response()->json(
            [
                'success'   => true,
                'message'   => 'berhasil'
            ]
        );
    }



    public function update_pengiriman(Request $request)
    {
        // dd($request);
        $data       = WarehouseOutDetail::where('no_do', $request->no_do)->get();
        $first      = WarehouseOutDetail::where('no_do', $request->no_do)->first();
        $qtys       = $request->qty_update;
        $exp        = explode('x', $request->alamat_kirim);
        $count_addr = count($exp);

        if ($request->type_cetak == "ups") {

            if ($request->has('id_product_add')) {
                $act_name = ", Menambah barang pada pengiriman";
            } else {
                $act_name = "";
            }

            if ($request->update_tgl != $first->tgl_kirim) {
                $activity = ", Merubah tanggal kirim dari " . $first->tgl_kirim . " menjadi " . $request->update_tgl;
            } else {
                $activity = "";
            }

            $log = array(
                'activity_id_quo'       => $request->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Update Pengiriman Barang no. DO " . $request->no_do . $act_name . $activity,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            ActQuoModel::insert($log);

            $history_out = [
                'id_outbound' => $request->id_outbound,
                'no_do'       => $request->no_do,
                'activity'    => "Update Pengiriman Barang " . $act_name . $activity,
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'  => Auth::id(),
            ];
            WarehouseOutHistory::create($history_out);

            foreach ($qtys as $qty => $val) {
                $out_dtl = [
                    'sku'           => $request->id_product[$qty],
                    'qty_kirim'     => $request->qty_update[$qty],
                    'id_alamat'     => $count_addr == 2 ? $exp[0] : $request->alamat_kirim,
                    'type_alamat'   => $count_addr == 2 ? 'utama' : 'other',
                    'tgl_kirim'     => Carbon::parse($request->update_tgl)->format('Y/m/d'),
                    'name'          => $request->up_nama_penerima,
                    'note'          => $request->keterangan[$qty],
                    'created_by'    => Auth::id(),
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                // dd($out_dtl);
                $qrys = WarehouseOutDetail::where('id', $request->id_detail[$qty])->update($out_dtl);
            }

            if ($request->has('id_product_add')) {
                $qty_ups = $request->qty_update_add;
                $wh = WarehouseOutDetail::where([['id_outbound', $request->id_outbound], ['no_do', $request->no_do]])->first();
                foreach ($qty_ups as $qty => $val) {
                    $data_dtl = [
                        'id_outbound'   => $request->id_outbound,
                        'no_do'         => $request->no_do,
                        'id_split'      => $wh->id_split,
                        'type_do'       => $wh->type_do,
                        'sku'           => $request->id_product_add[$qty],
                        'qty_kirim'     => $request->qty_update_add[$qty],
                        'id_alamat'     => $count_addr == 2 ? $exp[0] : $request->alamat_kirim,
                        'type_alamat'   => $count_addr == 2 ? 'utama' : 'other',
                        'tgl_kirim'     => Carbon::parse($request->update_tgl)->format('Y/m/d'),
                        'name'          => $request->up_nama_penerima,
                        'note'          => $request->keterangan_add[$qty],
                        'created_by'    => Auth::id(),
                        'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    // dd($data_dtl);
                    $qry2 = WarehouseOutDetail::create($data_dtl);
                }
            }

            $wh_hist = [
                'id_quo'        => $request->id_quo,
                'id_wo'         => $request->id_outbound,
                'no_wh_out'     => $request->no_do,
                'activity_name' => "Update Pengiriman Barang No. DO: " . $request->no_do,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
            ];
            $act_wh = Warehouse_history::create($wh_hist);
        }
        return $this->Cetakdelivery_update($request);
    }


    public function Cetakdelivery_update($request)
    {
        // dd($request);
        $exp        = explode('x', $request->alamat_kirim);
        $exp        = explode('x', $request->alamat_kirim);
        $count_addr = $request->type_cetak == "ups" ? count($exp) : WarehouseOutDetail::where([['no_do', $request->no_do], ['id_outbound', $request->id_outbound]])->first()->type_alamat;
        $data       = WarehouseOutDetail::where([['no_do', $request->no_do], ['id_outbound', $request->id_outbound]])->get();
        $first      = WarehouseOutDetail::where([['no_do', $request->no_do], ['id_outbound', $request->id_outbound]])->first();

        $pdf = PDF::loadview('pdf.warehouse_update_delivery', [
            'main'      => $request,
            'data'      => $data,
            'type'      => $first->type_alamat,
            'namapic'   => $request->type_cetak == "ups" ? $request->up_nama_penerima : $request->nama_penerima,
            'no_do'     => $request->no_do,
            'add'       => $request->alamat_kirim == null ? $request->db_address : $request->alamat_kirim,
            'qty_kirim' => $request->type_cetak == "ups" ? $request->qty_update : $request->qty_noup,
            'time'      => $request->type_cetak == "ups" ? $request->update_tgl : $request->tgl,
        ]);

        return $pdf->stream('MEG - DO.pdf');
    }



    // public function update_pengiriman(Request $request)
    // {
    //     $data       = WarehouseOutDetail::where('no_do', $request->no_do)->get();
    //     $qtys       = $request->qty_update;
    //     $exp        = explode('x', $request->alamat_kirim);
    //     $count_addr = count($exp);
    //     if ($request->type_cetak == "ups") {
    //         foreach ($qtys as $qty => $val) {
    //             $out_dtl = [
    //                 'sku'           => $request->id_product[$qty],
    //                 'qty_kirim'     => $request->qty_update[$qty],
    //                 'id_alamat'     => $count_addr == 2 ? $exp[0] : $request->alamat_kirim,
    //                 'type_alamat'   => $count_addr == 2 ? 'utama' : 'other',
    //                 'tgl_kirim'     => Carbon::parse($request->tgl_kirim)->format('Y/m/d'),
    //                 'name'          => $request->up_nama_penerima,
    //                 'note'          => $request->keterangan[$qty],
    //                 'created_by'    => Auth::id(),
    //                 'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
    //             ];
    //             // dd($out_dtl);
    //             $qrys = WarehouseOutDetail::where('id', $request->id_detail[$qty])->update($out_dtl);
    //         }
    //         $history_out = [
    //             'id_outbound' => $request->id_outbound,
    //             'no_do'       => $request->no_do,
    //             'activity'    => "Update pengieiman Barang No. DO " . $request->no_do,
    //             'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
    //             'created_by'  => Auth::id(),
    //         ];
    //         WarehouseOutHistory::create($history_out);
    //         $wh_hist = [
    //             'id_quo'        => $request->id_quo,
    //             'id_wo'         => $request->id_outbound,
    //             'no_wh_out'     => $request->no_do,
    //             'activity_name' => "Update Pengiriman Barang No. DO: " . $request->no_do,
    //             'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
    //             'created_by'    => Auth::id(),
    //         ];
    //         $act_wh = Warehouse_history::create($wh_hist);

    //         $log = array(
    //             'activity_id_quo'       => $request->id_quo,
    //             'activity_id_user'      => Auth::id(),
    //             'activity_name'         => "Merubah tanggal kirim ".Carbon::parse($request->tgl_kirim)->format('Y/m/d')." untuk nomer DO" . $request->no_do,
    //             'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
    //         );
    //         // dd($log);
    //         ActQuoModel::insert($log);
    //     }
    //     return $this->Cetakdelivery_update($request);
    // }


    // public function Cetakdelivery_update($request)
    // {
    //     // dd($request);
    //     $exp        = explode('x', $request->alamat_kirim);
    //     $exp        = explode('x', $request->alamat_kirim);
    //     $count_addr = $request->type_cetak == "ups" ? count($exp) : WarehouseOutDetail::where([['no_do', $request->no_do], ['id_outbound', $request->id_outbound]])->first()->type_alamat;
    //     // $add        = $request->type_cetak=="ups" ? count($exp) : $request->db_address;

    //     $pdf = PDF::loadview('pdf.warehouse_update_delivery', [
    //         'main'      => $request,
    //         'type'      => $count_addr == 2 ? "utama" : "other",
    //         'namapic'   => $request->type_cetak == "ups" ? $request->up_nama_penerima : $request->nama_penerima,
    //         'no_do'     => $request->no_do,
    //         'add'       => $count_addr == 2 ? $exp[0] : $request->db_address,
    //         'qty_kirim' => $request->type_cetak == "ups" ? $request->qty_update : $request->qty_noup,
    //         'time'      => $request->type_cetak == "ups" ? $request->update_tgl : $request->tgl,
    //     ]);

    //     return $pdf->stream('MEG - DO.pdf');
    // }



    public function history(Request $request)
    {
        $history    = WarehouseInboundHistory::where('id_inbound', $request->id_inbound)->orderby('created_at', 'desc')->get();
        return view('warehouse_update.inbound.all_history', [
            'act'    => $history,
        ]);
    }


    public function ganti_alamat(Request $request)
    {
        // dd($request);
        $idwo = $request->idwo;
        $det  = $request->det;
        $adr  = Warehouse_address::where('id', $det)->first();

        $names  = $request->type == "new" ? '' : $adr->name;
        $addre  = $request->type == "new" ? '' : $adr->address;
        $method = $request->type == "new" ? "post" : "put";
        $action = $request->type == "new" ? 'Warehouse\update\WarehouseOutboundController@save_alamat' : ['Warehouse\update\WarehouseOutboundController@update_alamat', $adr->id];
        // dd($view);
        return view('warehouse_update.attribute.warehouse_tambahalamat', [
            'request' => $request,
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

        $look = WarehouseOut::where('id_quo', $request->idwo)->first();

        $log = array(
            'activity_id_quo'       => $request->idwo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Menambahkan alamat pengiriman untuk delivery order " .  $request->name . " " . $request->address,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        return redirect("warehouse/warehouse_outbound/" . $look->id)->with('success', ' Tambah alamat pengiriman lain berhasil');
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
        $look = WarehouseOut::where('id_quo', $request->idwo)->first();
        $log = array(
            'activity_id_quo'       => $request->idwo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Merubah alamat pengiriman untuk delivery order " .  $request->name . " " . $request->address,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return redirect("warehouse/warehouse_outbound/" . $look->id)->with('success', ' Ubah alamat pengiriman berhasil');
    }


    public function save_resi(Request $request)
    {
        // dd($request);
        $id_address = $request->type == "main" ? $request->alamat . "x" : $request->alamat;
        $check      = Warehouse_resi::where([['id_address', $id_address], ['id_wo', $request->idwo]])->first();
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
        $look   = WarehouseOut::where('id', $request->idwo)->first();
        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Pengiriman ke " . $alamat . " menggunakan " . getForwarder($request->kurir)->company . " dengan Resi nomer " . $request->resi,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $up_status = [
            'status'  => 'terkirim',
        ];
        WarehouseOut::where('id', $request->idwo)->update($up_status);

        return $kondisi . " resi berhasil";
    }



    public function upload_resi(Request $request)
    {
        $check   = Warehouse_resi::where([
            ['id_wo', $request->idwo],
            ['id_address', $request->alamat]
        ])->first();
        
        return view('warehouse_update.attribute.upload_resi', [
            'check'  => $check,
            'type'   => $request->type,
            'idwo'   => $request->idwo,
            'id_resi'=> $request->id_resi,
            'method' => "post",
            'action' => 'Warehouse\update\WarehouseOutboundController@saveUploadResi',
        ]);
    }    

    
    public function saveUploadResi(Request $request)
    {
        // dd($request);
        $data = [
            'file' => !empty($request->file('files')) ? Storage::disk('public')->putFileAs('resi', $request->file('files'), $request->file('files')->getClientOriginalName()) : null,
         ];
         
        $qry = Warehouse_resi::where('id', $request->id_resi)->update($data);
        return redirect("warehouse/warehouse_outbound/" . $request->idwo)->with('success', 'File Di Upload');
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
                'action' => 'Warehouse\update\WarehouseOutboundController@save_finish',
            ]);
        }
    }

    public function save_finish(Request $request)
    {
        $look  = Warehouse_detail::where('kirim_addr', $request->id_address)->first();
        $look_dtl  = WarehouseOutDetail::where('id_alamat', $request->id_address)->first();
        $wo_first = WarehouseOut::where('id', $look_dtl->id_outbound)->first();
        $id_quo   = $request->id_quo;
        $log = array(
            'activity_id_quo'       => $wo_first->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Barang " . getProductDetail($look_dtl->sku)->name . " sudah diterima oleh " . $request->penerima . ", " . $request->note,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return redirect("warehouse/outbound/" . $look_dtl->id_wo)->with('success', ' sudah diterima');
    }



    public function delete_alamat(Request $request)
    {
        $id    = $request->det;
        $name  = Warehouse_address::where('id', $id)->first()->name;
        $todo  = Warehouse_address::where('id', $id)->delete();
        $look  = Warehouse_order::where('id', $request->idwo)->first();

        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Delete alamat pengiriman untuk delivery order " . $name,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return "warehouse/warehouse_outbound/" . $look->id;
    }

    public function view_do(Request $request)
    {
        $main = WarehouseOut::where('warehouse_outbound.id', $request->id_wo)
            ->join('quotation_models', 'warehouse_outbound.id_quo', '=', 'quotation_models.id')->first();
        $wadd      = Warehouse_address::where('id_wo', $request->id_wo)->get();
        $caddr     = [$main->id_customer => getCustomer(getQuo($main->id_quo)->id_customer)->company];
        $wo_detail = WarehouseOutDetail::select('*', DB::raw('sum(qty_kirim) as count'))->where([['id_outbound', $request->id_wo]])->groupBy('sku')->get();
        if ($request->has('siapkirim')) {
            $status = count($request->siapkirim) == count($request->id_product) ? "all" : "partial";
        } else if ($wo_detail != null) {
            $status = "rekap";
        }

        $views   = $status == "rekap" ? 'warehouse_update.attribute.addRekap' : 'warehouse_update.attribute.addPenerima';
        $action  = $status == "rekap" ? 'Warehouse\update\WarehouseOutboundController@store_rekap' : 'Warehouse\update\WarehouseOutboundController@store';
        return view($views, [
            'address'      => $this->get_address($main->id_quo, getQuo($main->id_quo)->id_customer),
            'caddr'        => $caddr,
            'request'      => $request,
            'wo_dtl'       => $wo_detail,
            'status_kirim' => $status,
            'method'       => "post",
            'action'       => $action,
        ]);
    }


    public function showDO_detail(Request $request)
    {
        $krm_details = WarehouseOutDetail::select('warehouse_outbound_detail.*', 'o.id_quo as quo')
            ->where([
                ['warehouse_outbound_detail.no_do', $request->no_wh_out],
                ['id_outbound', $request->num]
            ])->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_outbound_detail.id_outbound')->get();
        $wh_out = WarehouseOut::where('id', $request->num)->first();
        return view('warehouse_update.attribute.tab_history_detail', [
            'main'      => $krm_details,
            'num'       => $request->num,
            'wh_out'    => $wh_out,
            'no_wh_out' => $request->no_wh_out,
            'id_split'  => $request->id_split,
        ]);
    }



    public function store(Request $request)
    {
        $look       = WarehouseOut::where('id', $request->id_wo)->first();
        $sku        = $request->id_product;
        $exp        = explode('x', $request->address);
        $count_addr = count($exp);
        $w_dtl      = WarehouseOutDetail::where('id_outbound', $request->id_wo)->orderby('id', 'desc')->first();
        // dd($request, $count_addr);

        if ($request->type_do == "rekap") {
            $type_dos = "Rekap";
        } else if ($request->type_do == "lainnya") {
            $type_dos = $w_dtl == null ? "utama" : "split";
        }

        ///// NO DO /////
        if ($w_dtl == null) {
            $no_do = 'WH/OUT/' . Carbon::now()->format('y') . '/' . sprintf('%06d', $request->id_wo);
        } else {
            $no_do = 'WH/OUT/' . Carbon::now()->format('y') . '/' . sprintf('%06d', $request->id_wo) . '/' . ($w_dtl->id_split + 1);
        }

        foreach ($sku as $skus => $s) {
            $out_dtl = [
                'id_outbound'   => $request->id_wo,
                'sku'           => $request->id_product[$skus],
                'qty_kirim'     => $request->qty_kirim[$skus],
                'type_alamat'   => $count_addr == 2 ? "utama" : "other",
                'type_do'       => $type_dos,
                'id_split'      => $w_dtl == null ? 1 : ($w_dtl->id_split + 1),
                'id_alamat'     => $count_addr == 2 ? $exp[0] : $request->address,
                'tgl_kirim'     => Carbon::parse($request->tgl_kirim)->format('Y/m/d'),
                'name'          => $request->nama_penerima,
                'note'          => $request->note[$skus],
                'no_do'         => $no_do,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qrys = WarehouseOutDetail::create($out_dtl);

            if ($look->no_do == null) {
                $data = [
                    'no_do' => 'WH/OUT/' . Carbon::now()->format('y') . '/' . sprintf('%06d', $request->id_wo),
                ];
                $ups = WarehouseOut::where('id', $request->id_wo)->update($data);
            }

            if ($request->status_kirim <> 'all') {
                $log = array(
                    'activity_id_quo'       => $request->id_quo,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Barang " . getProductDetail($request->id_product[$skus])->name . " sudah dikirim dari gudang, menunggu resi diinput ",
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                ActQuoModel::insert($log);
            }
        }
        if ($request->status_kirim == 'all') {
            $log = array(
                'activity_id_quo'       => $request->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Semua barang sudah dikirim dari gudang, menunggu resi diinput",
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            ActQuoModel::insert($log);

            $up_status = [
                'status' => "Terkirim",
            ];
            WarehouseOut::where('id', $request->id_wo)->update($up_status);
        }

        $history_out = [
            'id_outbound' => $request->id_wo,
            'no_do'       => $no_do,
            'activity'    => "Mengirim Barang dengan No. DO " . $no_do,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        WarehouseOutHistory::create($history_out);

        $wh_hist = [
            'id_quo'        => $request->id_quo,
            'id_wo'         => $request->id_wo,
            'no_wh_out'     => $no_do,
            'activity_name' => "Kirim Barang dengan No. DO: " . $no_do,
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'    => Auth::id(),
        ];
        $act_wh = Warehouse_history::create($wh_hist);

        if ($qrys) {
            return $this->cetakDO($request, $no_do);
        }
    }


    public function store_rekap(Request $request)
    {
        $look       = WarehouseOut::where('id', $request->id_wo)->first();
        $wo_detail = WarehouseOutDetail::select('*', DB::raw('sum(qty_kirim) as count'))->where([['id_outbound', $request->id_wo]])->groupBy('sku')->get();
        $exp        = explode('x', $request->address);
        $count_addr = count($exp);
        if ($request->status_kirim == 'rekap') {
            $log = array(
                'activity_id_quo'       => $request->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Rekap Pengiriman " . $look->no_do,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            ActQuoModel::insert($log);
        }

        $history_out = [
            'id_outbound' => $request->id_wo,
            'no_do'       => $look->no_do,
            'activity'    => "Rekap Pengiriman " . $look->no_do,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        WarehouseOutHistory::create($history_out);

        $wh_hist = [
            'id_quo'        => $request->id_quo,
            'id_wo'         => $request->id_wo,
            'no_wh_out'     => $look->no_do,
            'activity_name' => "Rekap Pengiriman " . $look->no_do,
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'    => Auth::id(),
        ];
        $act_wh     = Warehouse_history::create($wh_hist);
        $pdf        = PDF::loadview('pdf.warehouse_out_delivery', [
            'main'      => $request,
            'type'      => $count_addr == 2 ? "utama" : "other",
            'add'       => $count_addr == 2 ? $exp[0] : $request->address,
            'product'   => $wo_detail,
            'look'      => $look,
        ]);
        return $pdf->stream('MEG - DO.pdf');
    }



    public function cetakDO($request, $no_do)
    {
        $exp        = explode('x', $request->address);
        $count_addr = count($exp);
        $pdf        = PDF::loadview('pdf.warehouse_out_delivery', [
            'main'      => $request,
            'type'      => $count_addr == 2 ? "utama" : "other",
            'namapic'   => $request->nama_penerima,
            'no_do'     => $no_do,
            'add'       => $count_addr == 2 ? $exp[0] : $request->address,
            'qty_kirim' => $request->qty_kirim,
            'product'   => $request->id_product,
            'time'      => $request->tgl_kirim,
        ]);
        return $pdf->stream('MEG - DO.pdf');
    }


    public function update(Request $request, $id)
    {
        $wh_in     = WarehouseIn::where([['id_quo', $request->id_quo], ['id_po', $request->id_po]])->first();
        $look      = Purchase_order::where('id', $request->id_po)->first();
        $wh_in_dtl = WarehouseInDetail::where('id_inbound', $wh_in->id)->get();
        $terimas   = $request->terima;
        $returns   = $request->return;
        $look      = Purchase_order::where('id', $request->id_po)->first();
        $status    = array_sum($request->qty_po) == array_sum($request->qty_terima) ? "in" : "partial";

        if (count($wh_in_dtl) < count($terimas)) {
            foreach ($terimas as $terima => $t) {
                $index = array_search($t, $request->id_product);
                $ch    = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]]])->first();
                if ($ch == null) {
                    $detail_terima = [
                        'id_inbound'  => $wh_in->id,
                        'qty_terima'  => $request->qty_terima[$index],
                        'sku'         => $request->id_product[$index],
                        'id_quo'      => $request->id_quo,
                        'qty_po'      => $request->qty_po[$index],
                        'qty_problem' => $request->qty_problem[$index],
                        'note_problem' => $request->note_problem[$index],
                        'created_by'  => Auth::id(),
                        'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    $qrys = WarehouseInDetail::create($detail_terima);
                } else {
                    $detail_terima = [
                        'id_inbound'  => $wh_in->id,
                        'qty_terima'  => $request->qty_terima[$index],
                        'sku'         => $request->id_product[$index],
                        'id_quo'      => $request->id_quo,
                        'qty_po'      => $request->qty_po[$index],
                        'qty_problem' => $request->qty_problem[$index],
                        'note_problem' => $request->note_problem[$index],
                        'created_by'  => Auth::id(),
                        'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    $qrys = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]]])->update($detail_terima);
                }
                if ($ch != null) {
                    if ($request->qty_terima[$index] != $ch->qty_terima || $request->qty_problem[$index] != $ch->qty_problem) {
                        $hist = [
                            'id_inbound' => $wh_in->id,
                            'qty_terima' => $request->qty_terima[$index],
                            'qty_problem' => $request->qty_problem[$index],
                            'activity'   => "Update penerimaan barang",
                            'sku'        => $request->id_product[$index],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                    }
                } else {
                    $hist = [
                        'id_inbound' => $wh_in->id,
                        'qty_terima' => $request->qty_terima[$index],
                        'qty_problem' => $request->qty_problem[$index],
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
        } else {
            foreach ($terimas as $terima => $t) {
                $index = array_search($t, $request->id_product);
                $ch    = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]]])->first();
                $detail_terima = [
                    'id_inbound'  => $wh_in->id,
                    'qty_terima'  => $request->qty_terima[$index],
                    'sku'         => $request->id_product[$index],
                    'qty_po'      => $request->qty_po[$index],
                    'qty_problem' => $request->qty_problem[$index],
                    'note_problem' => $request->note_problem[$index],
                ];
                $qrys = WarehouseInDetail::where([['id_inbound', $wh_in->id], ['sku', $request->id_product[$index]]])->update($detail_terima);
                if ($ch != null) {
                    if ($request->qty_terima[$index] != $ch->qty_terima || $request->qty_problem[$index] != $ch->qty_problem) {
                        $hist = [
                            'id_inbound' => $wh_in->id,
                            'qty_terima' => $request->qty_terima[$index],
                            'qty_problem' => $request->qty_problem[$index],
                            'activity'   => "Update penerimaan barang",
                            'sku'        => $request->id_product[$index],
                            'created_by' => Auth::id(),
                            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        $hist = WarehouseInboundHistory::create($hist);
                    }
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
            0 => 'id_quo',
            1 => 'id_cust',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = WarehouseOut::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = WarehouseOut::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
            $condition = "all";
        } else {
            $search = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = WarehouseOut::search($order, $dir, $limit, $start, $search)->get();
            $totalFiltered = WarehouseOut::countsearch($search)->count();
            $condition     = "search";
        }
        // dd($posts);

        $data      = $this->dataNstatus($posts, $condition);
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function filter_data(Request $request)
    {
        $columns = array(
            0 => 'id_quo',
            1 => 'id_cust',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $sdate  = $request->segment(4);
        $edate  = $request->segment(5);
        $icus   = $request->segment(6);

        $menu_count    = WarehouseOut::filtersearch($sdate, $edate, $icus);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = WarehouseOut::filtersearchlimit($sdate, $edate, $icus, $start, $limit, $order, $dir)->get();
        } else {
            $search = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }

            $search        = $request->input('search')['value'];
            $posts         = WarehouseOut::filtersearchfind($sdate, $edate, $icus, $start, $limit, $order, $dir, $search)->get();
            $totalFiltered = count(WarehouseOut::filtersearchfind($sdate, $edate, $icus, $start, $limit, $order, $dir, $search)->get());
        }
        // dd($posts);

        $data      = $this->dataNstatus($posts, "search");
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }



    public function dataNstatus($posts, $condition)
    {
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                // dd($post);
                $getresi   = GetWarehouseResi('id_wo', $post->id);
                $getwhdet  = GetWHdet($post->id)->first();

                $getdet    = $getwhdet == null ? "0000-00-00" : $getwhdet->tgl_kirim;
                $check     = $getresi == null ? '0000-00-00' : $getresi->created_date;
                $kirim     = $getresi == null ? 'waiting' : 'delivery';
                $tgl_kirim = $condition == 'all' ? $getdet : $post->tgl_kirim;
                $idku      = $condition == 'all' ? $post->id : $post->idku;
                $data[]          = [
                    'id'         => $idku,
                    'no_wo'      => $post->no_do,
                    'id_quo'     => 'SO' . sprintf("%06d", $post->id_quo),
                    'id_cust'    => getCustomer(getQuo($post->id_quo)->id_customer)->company,
                    'barang'     => countproduct($post->id_quo),
                    'status'     => $kirim,
                    'tgl_kirim'  => $tgl_kirim,
                    'created_at' => $check,
                ];
            }
        }
        return $data;
    }



    public function ex_quo(Request $request)
    {
        // dd($request);
        $start_date = $request->segment(4);
        $end_date   = $request->segment(5);
        $id_cust    = $request->segment(6);
        $type       = $request->segment(7);

        // dd($type, $request);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:I1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(100);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(100);
        $sheet->getColumnDimension('H')->setWidth(80);
        $sheet->getColumnDimension('I')->setWidth(25);

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'No.DO');
        $sheet->setCellValue('C1', 'No.SKU');
        $sheet->setCellValue('D1', 'Nama Produk');
        $sheet->setCellValue('E1', 'Qty Kirim');
        $sheet->setCellValue('F1', 'Tanggal Kirim');
        $sheet->setCellValue('G1', 'Alamat');
        $sheet->setCellValue('H1', 'Nama Penerima');
        $sheet->setCellValue('I1', 'Note');
        $rows = 2;
        $j = 1;

        $query = WarehouseOut::filterexport($start_date, $end_date, $id_cust, $type)->get();
        foreach ($query as $qp) {
            // dd($qp);
            $sheet->setCellValue('A' . $rows, $j++);
            $sheet->setCellValue('B' . $rows, $qp['no_do']);
            $sheet->setCellValue('C' . $rows, $qp['sku']);
            $sheet->setCellValue('D' . $rows, getProductDetail($qp['sku'])->name);
            $sheet->setCellValue('E' . $rows, $qp['qty_kirim']);
            $sheet->setCellValue('F' . $rows, Carbon::parse($qp['tgl_kirim'])->format('d F Y'));
            $sheet->setCellValue('G' . $rows, getCustomer(getQuo($qp['id_quo'])->id_customer)->company);
            $sheet->setCellValue('H' . $rows, $qp['name']);
            $sheet->setCellValue('I' . $rows, $qp['note']);
            $rows++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('Doc - Outbound Download.xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Doc - Outbound Download.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }



    public function get_address($id, $id_cust)
    {
        // dd($id, $id_cust);
        $arr = array();
        $data = Warehouse_address::where('id_wo', $id)->get();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->name . " - " . $reg->address;
        }
        $arr_cust = [$id_cust . 'x' => getCustomer($id_cust)->company];
        $vals     = $arr_cust + $arr;
        return $vals;
    }

    public function getProduct($id_quo)
    {
        // dd($id, $id_cust);
        $arr  = array();
        $data = QuotationProduct::select('*')
            ->where('quotation_product.id_quo', $id_quo)
            ->get();
        foreach ($data as $reg) {
            $arr[$reg->id_product] = getProductDetail($reg->id_product)->name;
        }
        return $arr;
    }

    public function upload_modal(Request $request)
    {
        $main   = WarehouseDoc::where('no_do', $request->no_do)->first();
        return view('warehouse_update.attribute.document_upload', [
            'method'    => "post",
            'main'      => $main,
            'id_wh_out' => $request->id_wh_out,
            'no_do'     => $request->no_do,
            'type'      => $main == null ? "create" : "update",
            'action'    => 'Warehouse\update\WarehouseOutboundController@upload_process',
        ]);
    }

    public function upload_process(Request $request)
    {
        // dd($request);
        $file   = $request->file('file');
        $folder = 'public/documents/warehouse';
        $path   = Storage::putfile($folder, $file);

        $types  = $request->type == "update" ? "updated" : "created";
        $data2  = [
            'id_outbound'  => $request->id_wh_out,
            'no_do'        => $request->no_do,
            'do_balik_doc' => $path,
            $types . '_by'        => Auth::id(),
            $types . '_at'        => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $main    = WarehouseOut::where('id', $request->id_wh_out)->first();
        $message = "Mengupload DO Balikan " . $request->no_do;
        $log     = array(
            'activity_id_quo'       => $main->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $message,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($data2);
        ActQuoModel::insert($log);

        $history_out = [
            'id_outbound' => $request->id_wh_out,
            'no_do'       => $request->no_do,
            'activity'    => $message,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        WarehouseOutHistory::create($history_out);

        $type   = $request->type == "update" ? WarehouseDoc::where('no_do', $request->no_do)->update($data2) : WarehouseDoc::insert($data2);
        return "true";
    }
}
