f<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\SalesMigrationModel;
use App\Models\Sales\SalesMigrationProduct;
use App\Models\Sales\QuotationReplacement;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\SalesMigrationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\SalesMigrationOtherPrice;
use App\Models\Sales\Customer_pic;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use App\Models\Activity\ActQuoModel;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\Warehouse_address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use Storage;

class SalesMigrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.datamigrate.index', [
            'quo_type' => $this->get_quoType(),
            'status'   => $this->get_quoStatus(),
            'sales'    => getEmpSelect('division_id', '9'),
        ]);
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
        // dd($id);
        $main = SalesMigrationModel::select('quotation_models_old.*', 'q.type_name')
            ->where('quotation_models_old.id', $id)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models_old.quo_type')->first();
        $cust     = CustomerModel::where('id', $main->id_customer)->first();
        $product  = SalesMigrationProduct::where('id_quo', $id)->get();
        $document = SalesMigrationDocument::where('id_quo', $id)->first();
        $invoice  = QuotationInvoice::where('id_quo', $id)->first();
        $price    = SalesMigrationOtherPrice::where('id_quo', $id)->first();
        $check    = SalesMigrationProduct::where('id_quo', $id)->where('id_product', '=', 'new')->first();
        $act      = [];
        $wo        = Warehouse_address::where('id_quo', $id)->get();
        // dd($product);
        if ($main == null) {
            $val = "document";
        } else {
            $val = "document_edit";
        }
        return view('sales.datamigrate.show', [
            'check'    => $check,
            'val'      => $val,
            'wo'       => $wo,
            'main'     => $main,
            'act'      => $act,
            'product'  => $product,
            'price'    => $price,
            'document' => $document,
            'invoice'  => $invoice,
            'cust'     => $cust,
            'cust_pic' => Customer_pic::where('id_customer', $cust->id)->get(),
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
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'id_customer',
            4 => 'id_sales',
            5 => 'quo_order_at',
            6 => 'quo_instatus',
            7 => 'quo_eksstatus',
            8 => 'quo_price',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = SalesMigrationModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = SalesMigrationModel::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = SalesMigrationModel::where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = SalesMigrationModel::where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {

                if ($post->quo_approve_status == null) {
                    $status = $post->quo_type == '1' ? "Pendekatan" : "Negosiasi";
                } else {
                    $status = $post->quo_approve_status;
                }

                $track = SalesMigrationProduct::where('id_quo', $post->id)->get();

                if ($post->quo_eksstatus == null) {
                    $posisi = "Admin";
                } else {
                    $posisi = "Product";
                }

                $data[] = [
                    'id'            => $post->id,
                    'quo_no'        => $post->quo_no,
                    'quo_name'      => $post->quo_name,
                    'id_customer'   => getCustomer($post->id_customer)->company,
                    'id_admin'      => getEmp($post->id_admin)->emp_name,
                    'id_sales'      => getEmp($post->id_sales)->emp_name,
                    'quo_order_at'  => $post->quo_order_at,
                    'updated_at'    => $post->updated_at,
                    'quo_instatus'  => $post->quo_instatus,
                    'quo_eksstatus' => $post->quo_eksstatus,
                    'quo_price'     => $post->quo_price,
                    'quo_type'      => getQuoType($post->quo_type)->type_name,
                    'quo_color'     => getQuoType($post->quo_type)->color,
                    'status'        => $status,
                    'posisi'        => $posisi,
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

    public function filter_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'id_customer',
            4 => 'id_sales',
            5 => 'quo_order_at',
            6 => 'quo_instatus',
            7 => 'quo_eksstatus',
            8 => 'quo_price',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];


        $type   = $request->segment(4);
        $status = $request->segment(5);
        $sales  = $request->segment(6);
        $sdate  = $request->segment(7);
        $edate  = $request->segment(8);

        $menu_count    = SalesMigrationModel::filtersearch($type, $status, $sales, $sdate, $edate);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = SalesMigrationModel::filtersearchlimit($type, $status, $sales, $sdate, $edate, $start, $limit, $order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = SalesMigrationModel::filtersearchfind($type, $status, $sales, $sdate, $edate, $start, $limit, $order, $dir, $search)->get();
            $totalFiltered = count(SalesMigrationModel::filtersearchfind($type, $status, $sales, $sdate, $edate, $start, $limit, $order, $dir, $search)->get());
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {

                if ($post->quo_approve_status == null) {
                    $status = $post->quo_type == '1' ? "Pendekatan" : "Negosiasi";
                } else {
                    $status = $post->quo_approve_status;
                }

                $track = SalesMigrationProduct::where('id_quo', $post->id)->get();

                if ($post->quo_eksstatus == null) {
                    $posisi = "Admin";
                } else {
                    $posisi = "Product";
                }

                $data[] = [
                    'id'            => $post->id,
                    'quo_no'        => $post->quo_no,
                    'quo_name'      => $post->quo_name,
                    'id_customer'   => getCustomer($post->id_customer)->company,
                    'id_admin'      => getEmp($post->id_admin)->emp_name,
                    'id_sales'      => getEmp($post->id_sales)->emp_name,
                    'quo_order_at'  => $post->quo_order_at,
                    'updated_at'    => $post->updated_at,
                    'quo_instatus'  => $post->quo_instatus,
                    'quo_eksstatus' => $post->quo_eksstatus,
                    'quo_price'     => $post->quo_price,
                    'quo_type'      => getQuoType($post->quo_type)->type_name,
                    'quo_color'     => getQuoType($post->quo_type)->color,
                    'status'        => $status,
                    'posisi'        => $posisi,
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

    public function get_quoType()
    {
        $data = Quo_TypeModel::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = ucfirst($reg->type_name);
        }
        return $arr;
    }
    public function get_quoStatus()
    {
        $data = QuotationStatus::where('status_type', 'status')->get();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = ucfirst($reg->status_name);
        }

        return $arr;
    }

    public function ex_quo(Request $request)
    {
        // dd($request->segment(9));
        $status = $request->segment(5) == 0 ? '' : $request->segment(5);
        $type   = $request->segment(4) == 'kosong' ? '' : $request->segment(4);
        $status = $request->segment(5) == '' ? '' : $status;
        $sales  = $request->segment(6) == 'kosong' ? '' : $request->segment(6);
        $sdate  = $request->segment(7) == 'kosong' ? '' : $request->segment(7);
        $edate  = $request->segment(8) == 'kosong' ? '' : $request->segment(8);
        $All    = $request->segment(9) == 'kosong' ? '' : $request->segment(9);

        if ($request->segment(9) == "true") {
            $query = SalesMigrationModel::join('quotation_product_old as qp', 'qp.id_quo', '=', 'quotation_models_old.id')->get();
        } else {
            $query = SalesMigrationModel::filterexport($type, $status, $sales, $sdate, $edate)->get();
        }
        $j = 1;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:I1')->getFont()->setBold(TRUE);
        $sheet->getStyle('I')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(25);

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Type');
        $sheet->setCellValue('C1', 'Nomer');
        $sheet->setCellValue('D1', 'Nama Paket');
        $sheet->setCellValue('E1', 'Customer');
        $sheet->setCellValue('F1', 'Sales');
        $sheet->setCellValue('G1', 'Tanggal Order');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Harga');
        $rows = 2;
        foreach ($query as $qp) {
            $price = $qp['det_quo_harga_order'] * $qp['det_quo_qty'];
            $sheet->setCellValue('A' . $rows, $j++);
            $sheet->setCellValue('B' . $rows, typename($qp['quo_type'])->type_name);
            $sheet->setCellValue('C' . $rows, $qp['quo_no'] == null ? "RFQ" : $qp['quo_no']);
            $sheet->setCellValue('D' . $rows, $qp['quo_name']);
            $sheet->setCellValue('E' . $rows, getCustomer($qp['id_customer'])->company);
            $sheet->setCellValue('F' . $rows, emp_name($qp['id_sales']));
            $sheet->setCellValue('G' . $rows, $qp['quo_order_at']);
            $sheet->setCellValue('H' . $rows, $qp['quo_eksstatus']);
            $sheet->setCellValue('I' . $rows, $qp['det_quo_harga_order'] * $qp['det_quo_qty']);
            $rows++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('Sales Order.xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="SalesOrder.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }



    public function document(Request $request)
    {
        $quo  = SalesMigrationModel::where('id', $request->quo)->first();
        $main = SalesMigrationDocument::where('id_quo', $request->quo)->first();
        // dd($main);
        $create = $main == null ? "" : $main;
        $view   = $main == null ? "document" : "document_edit";

        return view('sales.datamigrate.attribute.' . $view, [
            'view'   => $view,
            'utama'  => $quo,
            'main'   => $create,
            'quo'    => $request->quo,
            'method' => "post",
            'action' => 'Sales\SalesMigrationController@document_save',
        ]);
    }
    

    public function document_save(Request $request)
    {
        // dd($request);
        $id_quo = $request->id_quo;
        $types  = $request->type == "update" ? "updated" : "created";
        $data2  = [
            'id_quo'            => $request->id_quo,
            'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
            'no_sp'             => $request->no_sp,
            'tgl_sp'            => $request->tgl_sp,
            'no_spk'            => $request->no_spk,
            'tgl_spk'           => $request->tgl_spk,
            'no_bast'           => $request->no_bast,
            'tgl_bast'          => $request->tgl_bast,
            'no_spk'            => $request->no_spk,
            'no_fakturpajak'    => $request->no_fakturpajak,
            'tgl_fakturpajak'   => $request->tgl_fakturpajak,
            'no_fakturjual'     => $request->no_fakturjual,
            'tgl_fakturjual'    => $request->tgl_fakturjual,
            $types . '_by'        => Auth::id(),
            'updated_at'        => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $waktu_pelaksanaan  = [
            'quo_deadline' => $request->waktu_pelaksanaan,
            'updated_by'   => Auth::id(),
            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString()
        ];
        SalesMigrationModel::where('id', $request->id_quo)->update($waktu_pelaksanaan);

        $log = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Menambahkan data dokumen",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($data2);
        ActQuoModel::insert($log);
        $type   = $request->type == "update" ? SalesMigrationDocument::where('id_quo', $id_quo)->update($data2) : SalesMigrationDocument::insert($data2);
        return redirect()->back()->with('success', ucwords($request->input('company')) . 'Update Data Document berhasil');
    }


    public function document_upload(Request $request)
    {
        // dd($request);
        // $main   = QuotationDocument::where('id_quo', $request->quo)->first();
        return view('sales.datamigrate.attribute.document_upload', [
            'method'           => "post",
            'quo'              => $request->quo,
            'type'             => $request->type,
            'action'           => 'Sales\SalesMigrationController@saveFile',
        ]);
    }



    public function saveFile(Request $request, $id = 0)
    {
        // dd($request);
        $file = $request->file('file');
        $folder = 'public/documents';
        $path = Storage::putfile($folder, $file);

        if ($request->type == "sp") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_sp' => $path,
            ];
        } elseif ($request->type == "po") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_po' => $path,
            ];
            // dd($path);
        } elseif ($request->type == "spk") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_spk' => $path,
            ];
        } elseif ($request->type == "bast") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_bast' => $path,
            ];
        } elseif ($request->type == "fakturpajak") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_fakturpajak' => $path,
            ];
        } elseif ($request->type == "fakturjual") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_fakturjual' => $path,
            ];
        } else {
            return response()->json("File Error !");
        }
        $qry = SalesMigrationDocument::where('id_quo', $request->id_quo)->update($data);
        return response()->json("success");
    }
    
}
