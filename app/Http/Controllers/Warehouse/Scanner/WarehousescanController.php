<?php

namespace App\Http\Controllers\warehouse\scanner;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActQuoModel;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\WarehouseUpdate\WarehouseSN;
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
use App\Models\WarehouseUpdate\WarehouseOut;
use App\Models\WarehouseUpdate\WarehouseOutDetail;
use App\Models\WarehouseUpdate\WarehouseOutHistory;
use App\Models\Inventory\InventoryModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use PDF;
use DB;

class WarehousescanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouse_update.scan.index');
    }
    public function listsn(Request $request)
    {
        return view('warehouse_update.scan.index');
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
        // dd($request);
        $checkinv = InventoryModel::where([['product', $request->sku], ['status', 'order']])->first(); 
        $po       = $checkinv!=null ? Purchase_order::where('po_number', $checkinv->id_purchase)->first() : null;
        $quo_po   = $checkinv!=null ? 'id_po' : 'id_quo';
        $val_qp   = $checkinv!=null ? $po->id : $request->id_quo;
        
        // dd($request, $checkinv, $po, $quo_po, $val_qp);
        foreach ($request->number as $item => $value) {
            $data  = [
                'id_quo'     => $request->id_quo,
                'id_out'     => $request->id_outbound,
                'id_out_det' => $request->id_out_det,
                'sn'         => $request->number[$item],
                'sku'        => $request->sku,
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            WarehouseSN::where([[$quo_po, $val_qp],['sku', $request->sku],
            ['sn', $request->number[$item]]])->update($data);
        }

        $log = array(
            'activity_id_quo'       => $request->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Serial Number untuk barang " .  $request->sku . " - " . getProductDetail($request->sku)->name. 
                                        " sudah di pilih sebanyak" . count($request->number) . " Unit",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
        );
        // dd($log);
        ActQuoModel::insert($log);
        return "oke";
    }

    public function storesn_in(Request $request)
    {
        // dd($request);
        foreach ($request->number as $item => $value) {
            $data  = [
                'id_quo'     => $request->id_quo,
                'id_po'      => $request->id_po,
                'sn'         => $request->number[$item],
                'sku'        => $request->sku,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            WarehouseSN::insert($data);
        }

        $log = array(
            'activity_id_quo'       => $request->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Serial Number untuk barang " .  $request->sku . " - " . getProductDetail($request->sku)->name . " sudah disimpan di system sebanyak " . count($request->number) . " Unit",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        $hist = [
            'id_inbound'  => $request->id_inbound,
            'activity'    => "Serial Number ".$request->sku. " - " . getProductDetail($request->sku)->name . " sudah disimpan di system sebanyak " . count($request->number)." Unit",
            'created_by'  => Auth::id(),
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        return "oke";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // dd($request);
        $product  = WarehouseOutDetail::where([
            ['no_do', $request->no_wh_out],
            ['sku', $request->product],
        ])->first();
        
        // $sku_khusus = $request->product == "SKU123238387" || $request->product == "SKU123236965" ? "SKU123238387" : $request_product;
        $checksn= WarehouseSN::where([
            ['id_out', null],
            ['id_out_det', null],
            ['sku', $request->product],
        ])->orWhere([
            ['sku', $request->product],
            ['id_quo', $request->quo],
            ['id_out', $request->id_out],
            ['id_out_det', $request->id_out_det],
        ])
        ->get();


        $check= WarehouseSN::where([
            ['sku', $request->product],
            ['id_quo', $request->quo],
            ['id_out', $request->id_out],
            ['id_out_det', $request->id_out_det],
        ])->get();

        // dd($product->qty_kirim , $checksn, $check, $request);
        return view('warehouse_update.scan.show', [
            'product' => $product,
            'checksn' => $checksn,
            'check'   => $check,
            'id_quo'  => $request->quo,
            'id_po'   => $request->po,
            'method'  => "post"
        ]);
    }


    public function show_inbound(Request $request)
    {
        // dd($request);
        $id_qp   = $request->quo==0 ? 'id_po' : 'id_quo';
        $val_qp  = $request->quo==0 ? $request->id_po : $request->quo;
        $product = WarehouseInDetail::where([
            ['id_inbound', $request->id_inbound],
            ['sku', $request->product],
            ['id_quo', $request->quo],
        ])->first();

        $checksn = WarehouseSN::where([
            [$id_qp, $val_qp],
            ['sku', $request->product],
        ])->get();
        
        return view('warehouse_update.scan_in.show', [
            'product' => $product,
            'checksn' => $checksn,
            'id_quo'  => $request->quo,
            'id_po'   => $request->id_po,
            'method'  => "post"
        ]);
    }



    public function searchSN(Request $request)
    {
        // dd($request);
        $data = [];

        if ($request->has('s_num')) {
            $search = $request->s_num;
            $data   = WarehouseSN::select("sn", "sku", 'id_quo', "id")
                ->where('sn', 'LIKE', "%$search%")
                ->where('id_quo', $request->id_quo)
                ->where('sku', $request->sku)
                ->first();
        }
        // dd($data);
        return response()->json($data);
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
    public function destroy(Request $request)
    {
        // dd($request);
        if($request->type == "inbound")
        {
            $proses = WarehouseSN::find($request->idsn)->delete();
            return "oke";
        }else{
            $data = [
                'id_out'        => null,
                'id_out_det'    => null,
            ];
            $proses = WarehouseSN::where('id', $request->idsn)->update($data);
            return "oke";
        }
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

        $menu_count    = WarehouseSN::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = WarehouseSN::select('warehouse_serial_number.*',
            'o.no_do as no_do',
            'o.type_alamat as type_alamat','o.id_alamat as id_alamat')
            ->join('warehouse_outbound_detail as o', 'o.id', '=', 'warehouse_serial_number.id_out_det')
            ->offset($start)->limit($limit)
            ->orderby($order, $dir)->get();
        } else {
            $search = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = WarehouseSN::search($order, $dir, $limit, $start, $search)->get();
            $totalFiltered = WarehouseSN::countsearch($search)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                // dd($post);
                $data[] = [
                    'id'         => $post->id,
                    'no_do'      => $post->no_do,
                    'id_quo'     => 'SO' . sprintf("%06d", $post->id_quo),
                    'id_cust'    => getCustomerWarehouse($post->id_alamat,$post->type_alamat),
                    'barang'     => $post->sku,
                    'sn'         => $post->sn,
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


    public function download_excel(Request $request)
    {
        $type      = $request->segment(4);
        $id_quo    = $request->segment(5);
        $id_outbound = $request->segment(6);

        if($type == "format")
        {
            return $this->export_excel($request);
        }
    }


    public function export_excel($request)
    {
        $type        = $request->segment(4);
        $id_quo      = $request->segment(5);
        $id_outbound = $request->segment(6);
        $split       = $request->segment(7);
        $wh_out      = WarehouseOutDetail::select('*','warehouse_outbound_detail.id')->where([['id_outbound', $id_outbound],['warehouse_outbound_detail.id_split', $split]])
        ->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_outbound_detail.id_outbound')
        ->orderBy('sku', 'asc')->get();

        $newarray = array();
        foreach ($wh_out as $value) {
            $newarray[getProductDetail($value->sku)->name] = $value->sku;
        }

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->getStyle('A1:D1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(100);
        $sheet->getColumnDimension('D')->setWidth(25);

        $sheet->setCellValue('A1', 'No. DO');
        $sheet->setCellValue('B1', 'sku');
        $sheet->setCellValue('C1', 'Nama Produk');
        $sheet->setCellValue('D1', 'Serial Number');

        $first_row   = WarehouseOutDetail::select('*','warehouse_outbound_detail.id')->where([['id_outbound', $id_outbound],
        ['warehouse_outbound_detail.id_split', $split]])
        ->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_outbound_detail.id_outbound')
        ->orderBy('sku','asc')->first();


        $l_row    = 2;
        foreach ($wh_out as $qp => $n) 
        {
            $newname = array_search($n->sku, $newarray);
            // dd($wh_out);
            $last_row;
            $sum_row  = WarehouseOutDetail::select('*','warehouse_outbound_detail.id')->where([['id_outbound', $id_outbound],['warehouse_outbound_detail.id_split', $split], ['sku','<', $n->sku]])
                ->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_outbound_detail.id_outbound')
                ->orderBy('sku','asc')->get()->sum('qty_kirim');
            
            $rows     = $first_row->sku == $n->sku ? $l_row : $last_row;
            $last_row = $n->qty_kirim + 1;
            
            for($i = $rows; $i <= $rows + $n->qty_kirim-1; $i++)
            {
                $sheet->setCellValue('A' . $i, $n->no_do);
                $sheet->setCellValue('B' . $i, $n->sku);
                $sheet->setCellValue('C' . $i, $newname);
                $sheet->getStyle('D2:D'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');            
            } 
            $last_row = $i;
        }

        $writer = new Xlsx($excel);
        $writer->save('Excel Format SN.xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Excel Format SN.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xls');
        $writer->save('php://output');
    }

    
    public function upload_sn(Request $request)
    {
        // dd($request);
        $wh_out      = WarehouseOut::join('warehouse_outbound_detail', 'warehouse_outbound.id', '=', 'warehouse_outbound_detail.id_outbound')
                        ->where('id_outbound', $request->id_outbound)->get();
        $outs        = WarehouseOut::where('id_quo', $request->id_quo)->first();

        return view('warehouse_update.attribute.tab_upload',[
            'main'     => $wh_out,
            'out'      => $outs,
            'id_split' => $request->id_split,
            'method'   => "post",
            'action'   => 'Warehouse\Scanner\WarehousescanController@saveUpload',
        ]);

    }


//////////////////////////////// INBOUND EXCEL ////////////////////////
//////////////////////////////////////////////////////////////////////


    public function downloadExcel_inbound(Request $request)
    {
        // dd($request);
        $type      = $request->segment(4);
        $id_quo    = $request->segment(5);
        $id        = $request->segment(6);

        if($type == "format")
        {
            return $this->export_excel_inbound($request);
        }
    }

    public function export_excel_inbound($request)
    {
        // dd($request);
        $type        = $request->segment(4);
        $id_quo      = $request->segment(5);
        $id          = $request->segment(6);
        $wh_in       = WarehouseIn::select('*', 'warehouse_inbound_detail.id')->join('warehouse_inbound_detail', 'warehouse_inbound_detail.id_inbound', '=', 'warehouse_inbound.id')
                       ->where('warehouse_inbound.id', $id) ->orderBy('sku', 'asc')->get();
        $wh_out      = WarehouseIn::select('*')->where([['id_inbound', $id],['warehouse_inbound.id_quo', $id_quo]])
        ->join('warehouse_inbound_detail as o', 'o.id', '=', 'warehouse_inbound.id')
        ->orderBy('sku', 'asc')->get();

        $newarray = array();
        foreach ($wh_in as $value) {
            $newarray[getProductDetail($value->sku)->name] = $value->sku;
        }

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->getStyle('A1:D1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(100);
        $sheet->getColumnDimension('C')->setWidth(25);

        $sheet->setCellValue('A1', 'sku');
        $sheet->setCellValue('B1', 'Nama Produk');
        $sheet->setCellValue('C1', 'Serial Number');

        $first_row   = WarehouseIn::select('*', 'warehouse_inbound_detail.id')->join('warehouse_inbound_detail', 'warehouse_inbound_detail.id_inbound', '=', 'warehouse_inbound.id')
                       ->where('warehouse_inbound.id', $id) ->orderBy('sku', 'asc')->first();
        $l_row       = 2;
        
        foreach ($wh_in as $qp => $n) 
        { 
            $newname = array_search($n->sku, $newarray);
            $last_row;
            $sum_row  = WarehouseIn::select('*','warehouse_inbound_detail.id')
                ->where([['warehouse_inbound.id_quo', $id_quo], ['sku','<', $n->sku]])
                ->join('warehouse_inbound_detail', 'warehouse_inbound_detail.id_quo', '=', 'warehouse_inbound.id')
                ->orderBy('sku','asc')->get()->sum('qty_terima');
            
            $rows     = $first_row->sku == $n->sku ? $l_row : $last_row;
            $last_row = $n->qty_terima + 1;

            for($i = $rows; $i <= $rows + $n->qty_terima-1; $i++)
            {
                $sheet->setCellValue('A' . $i, $n->sku);
                $sheet->setCellValue('B' . $i, $newname);
                $sheet->getStyle('C2:C'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');            
            } 
            $last_row = $i;
        }

        $writer = new Xlsx($excel);
        $writer->save('Excel Format SN.xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Excel Format SN.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xls');
        $writer->save('php://output');
    }

    
    public function uploadSN_inbound(Request $request)
    {
        // dd($request);
        $wh_in      = WarehouseIn::join('warehouse_inbound_detail', 'warehouse_inbound_detail.id_inbound', '=', 'warehouse_inbound.id')
                        ->where([['warehouse_inbound.id', $request->id], ['warehouse_inbound.id_po', $request->id_po]])->get();
        $po         = WarehouseIn::where('id_po', $request->id_po)->first();
        // dd($wh_in, $po, $request);
        return view('warehouse_update.attribute.tab_upload',[
            'main'     => $wh_in,
            'out'      => $po,
            'request'  => $request,
            'method'   => "post",
            'action'   => 'Warehouse\Scanner\WarehousescanController@saveUpload_Inbound',
        ]);

    }


    public function saveUpload_Inbound(Request $request)
    {
        // dd($request);
        $id_quo      = $request->id_quo;
        $id_inbound  = $request->id_inbound;
        $id_po       = $request->id_po;

        $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($request->file);
        $sheetData   = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        
        // dd($sheetData);

        $i         = 1;
        foreach ($sheetData as $sh => $x) {
            if($i != $sh){
            $wh_out      = WarehouseInDetail::select('*','warehouse_inbound_detail.id')->where([['id_inbound', $id_inbound],['sku', $sheetData[$sh]["A"]]])
                ->join('warehouse_inbound as o', 'o.id', '=', 'warehouse_inbound_detail.id_inbound')
                ->first();

                $id_quo     = $request->id_quo;
                $id_in      = $request->id_inbound;
                $sku        = $sheetData[$sh]["A"];
                $sn         = $sheetData[$sh]["C"];

                $wh       = [
                    'id_quo'     => $id_quo,
                    'sku'        => $sku,
                    'sn'         => $sn,
                    'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by' => Auth::id(),
                ];
                $wh  = WarehouseSN::create($wh);
        }
    }
        return redirect()->back()->with('success', 'Import Data Successfully');
    }


    public function saveUpload(Request $request)
    {
        // dd($request);
        $id_quo      = $request->id_quo;
        $id_outbound = $request->id_outbound;
        $id_split    = $request->id_split;

        $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($request->file);
        $sheetData   = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        
        // dd($sheetData);

        $i         = 1;
        foreach ($sheetData as $sh => $x) {
            if($i != $sh){
            $wh_out      = WarehouseOutDetail::select('*','warehouse_outbound_detail.id')->where([['id_outbound', $id_outbound],['sku', $sheetData[$sh]["B"]],['id_split', $id_split]])
                ->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_outbound_detail.id_outbound')
                ->first();

                $id_quo     = $request->id_quo;
                $id_out     = $request->id_outbound;
                $id_out_det = $wh_out->id;
                $sku        = $sheetData[$sh]["B"];
                $sn         = $sheetData[$sh]["D"];

                $wh       = [
                    'id_quo'     => $id_quo,
                    'id_out'     => $id_out,
                    'id_out_det' => $id_out_det,
                    'sku'        => $sku,
                    'sn'         => $sn,
                    'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by' => Auth::id(),
                ];
                $wh  = WarehouseSN::create($wh);
        }
    }
        return redirect()->back()->with('success', 'Import Data Successfully');
    }

    public function downloadsn(Request $request)
    {
        $title        = 'SN-WH.xlsx';
        $objPHPExcel  = new Spreadsheet();
        $sheet        = $objPHPExcel->getActiveSheet();

        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );

        $style_total = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER

            ),

            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );

        $style_row = array(

            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)

            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );

        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'REKAP SN WH/OUT');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'PT MITRA ERA GLOBAL');
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Perkantoran Mangga Dua Square Blok C.22-25');
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Jl. Mangga Dua Square No.22, RW.6, Ancol');
        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Pademangan, Jakarta, 10730');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);

        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Tanggal Cetak :' . date('d F Y'));

        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Di Cetak Oleh :' . getUserEmp(Auth::id())->emp_name);

        $objPHPExcel->getActiveSheet()->SetCellValue('A8', 'NOMER DO');
        $objPHPExcel->getActiveSheet()->SetCellValue('B8', 'SKU');
        $objPHPExcel->getActiveSheet()->SetCellValue('C8', 'PRODUCT');
        $objPHPExcel->getActiveSheet()->SetCellValue('D8', 'SERIAL NUMBER');
        $objPHPExcel->getActiveSheet()->SetCellValue('E8', 'ALAMAT');
        $objPHPExcel->getActiveSheet()->SetCellValue('F8', 'PENERIMA');
        $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($style_total);


        $listsku = WarehouseSN::where('id_out', $request->id)->groupBy('sku')->get();
        $listsn  = WarehouseSN::where('id_out', $request->id)
        ->join('warehouse_outbound_detail as d','warehouse_serial_number.id_out_det','=','d.id')->get();
        
        
        $z        = 9;
        $newarray = array();
        $newadd   = array();

        // store name product 
        foreach ($listsku as $value) {
            $newarray[getProductDetail($value->sku)->name]=$value->sku;
                
        }

        // store address 
        foreach ($listsn as $value) {
                    $alamat  = $value->type_alamat == "utama" ? getCustomer($value->id_alamat)->company.' - '.getCustomer($value->id_alamat)->address : WarehouseAddress($value->id_alamat)->name.' - '.WarehouseAddress($value->id_alamat)->address;
                    $newid   = $value->type_alamat == "utama" ? $value->id_alamat.'x' :  $value->id_alamat;
            $newadd[$alamat] = $newid;
                
        }

        foreach ($listsn as $val) {
            $newname = array_search ($val->sku, $newarray);
            $addres  = array_search ($val->type_alamat == "utama" ? $val->id_alamat.'x' :  $val->id_alamat, $newadd);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $z, $val->no_do);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $z, $val->sku);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $z, $newname);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $z, $val->sn);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $z, $addres);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $z, $val->name);


            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $z)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $z)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $z)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $z)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $z)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $z)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $z . ':F' . $z)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $z)->applyFromArray($style_row);
            $z++;
        }

        // Set orientasi kertas jadi LANDSCAPE
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $objPHPExcel->getActiveSheet(0)->setTitle("Rekap SN WH");
        $objPHPExcel->setActiveSheetIndex(0);


        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        $writer->save('php://output');
    }


}
