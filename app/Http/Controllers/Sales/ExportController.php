<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeModel;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\SalesMigrationModel;
use App\Models\Sales\SalesMigrationProduct;
use App\Models\Warehouse\WarehouseMigrateOut;
use App\Models\Warehouse\WarehouseMigrateDetail;
use App\Models\Warehouse\warehouse_out;
use App\Models\Activity\ActQuoModel;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Sales\Customer_pic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Storage;
use PDF;

class ExportController extends Controller
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

    public function generate(Request $request)
    {
        $id = request()->segment(4);
        $type = request()->segment(5);
        $session = $request->session()->get('division_id');

        if ($type == 'precalc') {
            $this->precall_excel($id);
        } else if ($type == 'so') {
            return $this->generate_salesorder($id);
        } else if ($type == 'invoice') {
            return $this->generate_invoice($id);
        }
    }

    public function generateMigrate(Request $request)
    {
        $id      = request()->segment(4);
        $type    = request()->segment(5);
        $session = $request->session()->get('division_id');

        if ($type == 'precalc') {
            $this->precall_excel_migrate($id);
        } else if ($type == 'so') {
            return $this->generate_salesorder_migrate($id);
        } else if ($type == 'invoice') {
            return $this->generate_invoice_migrate($id);
        }
    }



    public function additional_note(Request $request)
    {
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $request->quo)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();

        return view('sales.quotation.attribute.modal_noteSO', [
            'req'      => $request,
            'main'     => $main,
            'title'    => sprintf("%06d", $request->quo),
            'method'   => "GET",
            'action'   => "Sales\ExportController@save_note"
        ]);
    }


    public function save_note(Request $request)
    {
        // dd($request);
        if ($request->has('note_salesorder')) {
            $data = [
                'note_salesorder' => $request->note_salesorder,
            ];
            $quo = QuotationModel::where('id', $request->id_quo)->update($data);
        }
        return $this->generate_salesorder($request->id_quo);
    }



    public function generate_salesorder($id)
    {
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $id)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();
        $cust    = CustomerModel::where('id', $main->id_customer)->first();
        $product = QuotationProduct::where('id_quo', $id)->get();
        $title   = sprintf("%06d", $main->id);

        $pdf = PDF::loadview('pdf.sales_order', [
            'main'    => $main,
            'cust'    => $cust,
            'product' => $product,
            'title'   => $title,

        ]);
        return $pdf->stream('MEG - SO' . $title . '.pdf');
    }

    public function invoice_costum(Request $request)
    {
        if ($request->dbs == 'lama') {
            $main = SalesMigrationModel::where('id', $request->id)->first();
        } else {
            $main = QuotationModel::where('id', $request->id)->first();
        }
        $user = EmployeeModel::all();
        return view('sales.quotation.attribute.invoice_custom', [
            'main'   => $main,
            'user'   => $user,
            'dbs'    => $request->dbs,
            'method' => "post",
            'action' => 'Sales\ExportController@generate_invoice',
        ]);
    }


    public function generate_invoice(Request $request)
    {
        if ($request->dbs == 'lama') {

            $main = SalesMigrationModel::select('quotation_models_old.*', 'q.type_name')
                ->where('quotation_models_old.id', $request->id)
                ->join('quotation_type as q', 'q.id', '=', 'quotation_models_old.quo_type')->first();
            $cust    = CustomerModel::where('id', $main->id_customer)->first();
            $product = SalesMigrationProduct::where('id_quo', $request->id)->get();
            $title   = $main->ref;
            $donum   = WarehouseMigrateOut::where('id_quo', $main->id)->first();

            $check = QuotationInvoice::where([
                ['id_quo', $main->id],
                ['type', 'lama']
            ])->first();
            $data = [
                'quo_inv_date' => $request->date,
                'updated_by'   => Auth::id(),
                'updated_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];
            SalesMigrationModel::where('id', $request->id)->update($data);
            $time    = $main->quo_inv_date == null ? Carbon::now('GMT+7')->format('d F Y') : date("d F Y", strtotime($request->date));

            if ($check == null) {
                $month_number = date('m', strtotime($request->date));
                $getmax       = QuotationInvoice::where('month', $month_number)->max('month_id') + 1;
                $number       = "INV/" . date("y") . "/" . $this->integerToRoman($month_number) . "/" . $getmax;

                $datai   = [
                    'id_quo'         => $main->id,
                    'month'          => date('m'),
                    'month_id'       => $month_number,
                    'type'           => "lama",
                    'digit'          => $request->digit,
                    'no_invoice'     => $number,
                    'tgl_invoice'    => $request->date,
                    'tgl_jatuhtempo' => $request->tempo,
                    'sign_by'        => $request->user,
                    'created_by'     => Auth::id(),
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                QuotationInvoice::insert($datai);
            } else {
                $number = $check->no_invoice;
                $datai  = [
                    'sign_by'        => $request->user,
                    'digit'          => $request->digit,
                    'tgl_jatuhtempo' => $request->tempo,
                    'updated_by'     => Auth::id(),
                    'updated_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                QuotationInvoice::where([
                    ['id_quo', $main->id],
                    ['type', 'lama']
                ])->update($datai);
            }

            $do  = WarehouseMigrateDetail::where('id_quo', $main->id)->first();
            $pdf = PDF::loadview('pdf.finance_invoice_migrate', [
                'main'    => $main,
                'cust'    => $cust,
                'product' => $product,
                'title'   => $title,
                'number'  => $number,
                'do'      => $do->do_number,
                'time'    => $time,
                'inv'     => $check,
                'digit'   => $request->digit,
                'tempo'   => date("d F Y", strtotime($request->tempo))

            ]);
        } else {

            $main = QuotationModel::select('quotation_models.*', 'q.type_name')
                ->where('quotation_models.id', $request->id)
                ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();
            $cust    = CustomerModel::where('id', $main->id_customer)->first();
            $product = QuotationProduct::where('id_quo', $request->id)->get();
            $title   = sprintf("%06d", $main->id);
            $donum   = warehouse_out::where('id_quo', $main->id)->first();

            $check = QuotationInvoice::where([
                ['id_quo', $main->id],
                ['type', null]
            ])->first();
            $data = [
                'quo_inv_date' => $request->date,
                'updated_by'   => Auth::id(),
                'updated_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];
            QuotationModel::where('id', $request->id)->update($data);
            $time    = $main->quo_inv_date == null ? Carbon::now('GMT+7')->format('d F Y') : date("d F Y", strtotime($request->date));

            if ($check == null) {
                $month_number = date('m', strtotime($request->date));
                $getmax       = QuotationInvoice::where('month', $month_number)->max('month_id') + 1;
                $number       = "INV/" . date("y") . "/" . $this->integerToRoman($month_number) . "/" . $getmax;

                $datai   = [
                    'id_quo'         => $main->id,
                    'month'          => $month_number,
                    'month_id'       => $getmax,
                    'digit'          => $request->digit,
                    'no_invoice'     => $number,
                    'tgl_invoice'    => $request->date,
                    'tgl_jatuhtempo' => $request->tempo,
                    'sign_by'        => $request->user,
                    'created_by'     => Auth::id(),
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                QuotationInvoice::insert($datai);

                $log = array(
                    'activity_id_quo'       => $main->id,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Invoice sudah dicetak",
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                // dd($data2);
                ActQuoModel::insert($log);
            } else {
                $month_number = date('m', strtotime($request->date));
                $getmax       = QuotationInvoice::where('month', $month_number)->max('month_id') + 1;

                if ($month_number == $check->month) {
                    $number = $check->no_invoice;
                } else {
                    $number = "INV/" . date("y") . "/" . $this->integerToRoman($month_number) . "/" . $getmax;
                }
                $datai        = [
                    'month'       => $month_number,
                    'month_id'    => $getmax,
                    'no_invoice'  => $number,
                    'sign_by'     => $request->user,
                    'digit'       => $request->digit,
                    'updated_by'  => Auth::id(),
                    'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                // dd($datai);
                QuotationInvoice::where('id_quo', $request->id)->update($datai);
            }

            $id_khusus = array('242', '243');

            if (in_array($main->id, $id_khusus)) {

                $pdf = PDF::loadview('pdf.finance_invoice_khusus', [
                    'main'    => $main,
                    'cust'    => $cust,
                    'product' => $product,
                    'title'   => $title,
                    'number'  => $number,
                    'time'    => $time,
                    'inv'     => $check,
                    'digit'   => $request->digit,

                ]);
            } else {
                $wh  = warehouse_out::where('id_quo', $request->id)->first();
                $pdf = PDF::loadview('pdf.finance_invoice', [
                    'main'    => $main,
                    'cust'    => $cust,
                    'product' => $product,
                    'title'   => $title,
                    'number'  => $number,
                    'do'      => 'WH/OUT/' . date('y', strtotime($wh->created_at)) . '/' . $donum->id,
                    'time'    => $time,
                    'inv'     => $check,
                    'digit'   => $request->digit,
                    'tempo'   => date("d F Y", strtotime($request->tempo))

                ]);
            }
        }


        return $pdf->download('MEG - INVOICE' . $title . '.pdf');
    }

    public function integerToRoman($integer)
    {
        $integer = intval($integer);
        $result  = '';
        $lookup  = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        );
        foreach ($lookup as $roman => $value) {
            $matches = intval($integer / $value);
            $result .= str_repeat($roman, $matches);
            $integer = $integer % $value;
        }
        return $result;
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

    public function precall_excel($id)
    {
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $id)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();
        $product      = QuotationProduct::where('id_quo', $id)->get();
        $total_ongkir = QuotationProduct::sumongkir($id);
        $price        = QuotationOtherPrice::where('id_quo', $id)->first();
        $invoice      = QuotationInvoice::where('id_quo', $id)->first();
        $cust         = CustomerModel::where('id', $main->id_customer)->first();
        $pic          = Customer_pic::where('id_customer', $cust->id)->first();
        $orang        = $pic == null ? "No Data" : $pic->name;
        $title        = 'Precalc-' . $main->quo_no . '.xlsx';

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        // setting file //
        $excel->getProperties()->setCreator('HomeMaleser')
            ->setLastModifiedBy('HomeMaleser')
            ->setTitle('Precalc-' . $main->quo_no)
            ->setSubject('Precalc-' . $main->quo_no)
            ->setDescription('Precalc-' . $main->quo_no)
            ->setKeywords('Precalc-' . $main->quo_no);

        // end setting file //

        // styling //

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

        $style_col2 = array(
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

        $style_abu = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'e3e1e1')
            )
        );

        $style_note = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_BOTTOM      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
        );
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
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

        $precalc = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            )
        );

        $style_barang = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER

            ),

            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );
        // end styling //

        // header 

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "PT Mitra Era Global");
        $excel->getActiveSheet()->mergeCells('A1:M3');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
        $excel->getActiveSheet()->mergeCells('A4:M4');
        $excel->getActiveSheet()->getRowDimension(4)->setRowHeight(25);
        $excel->getActiveSheet()->mergeCells('A5:M5');
        $excel->getActiveSheet()->getRowDimension(5)->setRowHeight(25);

        $excel->setActiveSheetIndex(0)->setCellValue('A6', "Satuan Kerja");
        $excel->getActiveSheet()->mergeCells('A6:B6');
        $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C6', $cust->company);
        $excel->getActiveSheet()->getStyle('C6:D' . $excel->getActiveSheet()->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->mergeCells('C6:D6');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('A7', "Customer PIC");
        $excel->getActiveSheet()->mergeCells('A7:B7');
        $excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C7', $orang);
        $excel->getActiveSheet()->mergeCells('C7:D7');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('A8', "Opportunity Owner");
        $excel->getActiveSheet()->mergeCells('A8:B8');
        $excel->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C8', getEmp($main->id_sales)->emp_name);
        $excel->getActiveSheet()->mergeCells('C8:D8');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('E6', "Project Name");
        $excel->getActiveSheet()->mergeCells('E6:G6');
        $excel->getActiveSheet()->getStyle('E6')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('H6', $main->quo_name);
        $excel->getActiveSheet()->mergeCells('H6:N6');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('E7', "Instansi/Company Name:");
        $excel->getActiveSheet()->mergeCells('E7:G7');
        $excel->getActiveSheet()->getStyle('E7')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('H7', $cust->company);
        $excel->getActiveSheet()->mergeCells('H7:J7');
        $excel->setActiveSheetIndex(0)->setCellValue('K7', "ORDER NO:");
        $excel->getActiveSheet()->getStyle('K7')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('L7', $main->quo_no);
        $excel->getActiveSheet()->mergeCells('L7:N7');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('E8', "Opp Owner Head:");
        $excel->getActiveSheet()->mergeCells('E8:G8');
        $excel->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('H8', getEmp('1')->emp_name);
        $excel->getActiveSheet()->mergeCells('H8:N8');

        $excel->getActiveSheet()->mergeCells('A9:N9');
        $excel->getActiveSheet()->getRowDimension(9)->setRowHeight(10);

        $excel->setActiveSheetIndex(0)->setCellValue('A10', "PRE-CALCULATION");
        $excel->getActiveSheet()->mergeCells('A10:N10');
        $excel->getActiveSheet()->getStyle('A10')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A10')->getFont()->setSize(22);
        $excel->getActiveSheet()->getStyle('A10:N10')->applyFromArray($precalc);
        $excel->getActiveSheet()->getRowDimension(10)->setRowHeight(20);

        $excel->getActiveSheet()->mergeCells('A11:N11');
        $excel->getActiveSheet()->getRowDimension(11)->setRowHeight(10);

        $excel->getActiveSheet()->getStyle('A1:N5')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A6:N6')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A7:N7')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A8:N8')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A9:N11')->applyFromArray($style_row);

        for ($x = 6; $x <= 8; $x++) {

            $excel->getActiveSheet()->getStyle('A' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('D' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('E' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('F' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('G' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('H' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('I' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('J' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('K' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('L' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('M' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('N' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getRowDimension($x)->setRowHeight(30);
        }
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(41);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);

        // end header 

        // ======================================== ROW BARANG ====================================//

        // Head Table

        $excel->setActiveSheetIndex(0)->setCellValue('A13', "No");
        $excel->getActiveSheet()->mergeCells('A13:A14');
        $excel->getActiveSheet()->getStyle('A13')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('B13', "SKU");
        $excel->getActiveSheet()->mergeCells('B13:B14');
        $excel->getActiveSheet()->getStyle('B14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C13', "Description");
        $excel->getActiveSheet()->mergeCells('C13:C14');
        $excel->getActiveSheet()->getStyle('C14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('D13', "Item Type (HW/SW)");
        $excel->getActiveSheet()->mergeCells('D13:D14');
        $excel->getActiveSheet()->getStyle('D14')->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('E13', "Vendor");
        $excel->getActiveSheet()->mergeCells('E13:E14');
        $excel->getActiveSheet()->getStyle('E14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('F13', "QTY");
        $excel->getActiveSheet()->mergeCells('F13:F14');
        $excel->getActiveSheet()->getStyle('F14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('G13', "CCY");
        $excel->getActiveSheet()->mergeCells('G13:G14');
        $excel->getActiveSheet()->getStyle('G14')->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('H13', "Unit Live");
        $excel->getActiveSheet()->mergeCells('H13:H14');
        $excel->getActiveSheet()->getStyle('H14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('I13', "Unit Price");
        $excel->getActiveSheet()->mergeCells('I13:I14');
        $excel->getActiveSheet()->getStyle('I14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('J13', "LLC / Modal");
        $excel->getActiveSheet()->mergeCells('J13:J14');
        $excel->getActiveSheet()->getStyle('J14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('K13', "Contract Price");
        $excel->getActiveSheet()->mergeCells('K13:K14');
        $excel->getActiveSheet()->getStyle('K14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('L13', "COGS");
        $excel->getActiveSheet()->mergeCells('L13:L14');
        $excel->getActiveSheet()->getStyle('L14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('M13', "Margin");
        $excel->getActiveSheet()->mergeCells('M13:M14');
        $excel->getActiveSheet()->getStyle('M14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N13', "Est. Avail Date");
        $excel->getActiveSheet()->mergeCells('N13:N14');
        $excel->getActiveSheet()->getStyle('N14')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A13:N14')->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->getRowDimension(15)->setRowHeight(10);

        $excel->getActiveSheet()->getStyle('A13:N14')->applyFromArray($style_row);
        for ($y = 13; $y <= 14; $y++) {

            $excel->getActiveSheet()->getStyle('A' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('K' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('L' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('M' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('N' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getRowDimension($y)->setRowHeight(25);
        }

        $z   = 16;
        $av  = 1;
        $z_n = 16;
        foreach ($product as $key => $ad) {


            $cstock      = StockCheck($ad->id_product, $main->id);
            $vendor_name = $cstock['condition'] == 'yes' ? $cstock['vendor'] : getVendor($ad->id_vendor)->vendor_name;

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $z, $av++);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $z, $ad->id_product);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $z, getProductDetail($ad->id_product)->name);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $z, "Hardware");
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $z, $vendor_name);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $z, $ad->det_quo_qty);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $z, "IDR");
            $unit_price = (float)$ad->det_quo_harga_order;
            $live_price = (float)$ad->det_quo_harga_order;

            $excel->setActiveSheetIndex(0)->setCellValue('H' . $z, $live_price);
            $excel->getActiveSheet()->getStyle('H' . $z)->getNumberFormat()->setFormatCode('#,##0');
            $modal = round($ad->det_quo_harga_modal);

            $excel->setActiveSheetIndex(0)->setCellValue('I' . $z, $unit_price);
            $excel->getActiveSheet()->getStyle('I' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('J' . $z, $modal);
            $excel->getActiveSheet()->getStyle('J' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('K' . $z, '=I' . $z . '*F' . $z . '');
            $excel->getActiveSheet()->getStyle('K' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('L' . $z, '=J' . $z . '*F' . $z . '');
            $excel->getActiveSheet()->getStyle('L' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $z, '=K' . $z . '-L' . $z . '');
            $excel->getActiveSheet()->getStyle('M' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('N' . $z, $ad->det_quo_status_vendor);




            $excel->getActiveSheet()->getStyle('A' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('B' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('C' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('D' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('E' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('F' . $z)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $z)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('I' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('J' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('K' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('L' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('M' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('N' . $z)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('A' . $z . ':N' . $z)->getAlignment()->setWrapText(true);
            $z++;
        }

        $size = count($product);
        switch ($size) {
            case in_array($size, range(1, 3)):
                $tambah = 3;
                break;
            case in_array($size, range(4, 5)):
                $tambah = 2;
                break;

            default:
                $tambah = 1;
                break;
        }
        $num_rows = $excel->getActiveSheet()->getHighestRow();
        $excel->getActiveSheet()->insertNewRowBefore($num_rows + 1, $tambah);
        $nr  = $excel->getActiveSheet()->getHighestRow() + 1;
        $nr1 = $excel->getActiveSheet()->getHighestRow() + 2;
        $nr2 = $excel->getActiveSheet()->getHighestRow() + 3;

        $excel->getActiveSheet()->mergeCells('A' . $nr . ':I' . $nr);
        $excel->getActiveSheet()->mergeCells('A' . $nr1 . ':I' . $nr1);
        $excel->getActiveSheet()->mergeCells('A' . $nr2 . ':I' . $nr2);

        $excel->setActiveSheetIndex(0)->setCellValue('J' . $nr, "Grand Total");
        $excel->setActiveSheetIndex(0)->setCellValue('K' . $nr, '=SUM(K' . $z_n . ':K' . $num_rows . ')');
        $excel->getActiveSheet()->getStyle('K' . $nr)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $nr, '=SUM(L' . $z_n . ':L' . $num_rows . ')');
        $excel->getActiveSheet()->getStyle('L' . $nr)->getNumberFormat()->setFormatCode('#,##0');

        $time_inv = $invoice == null ? '0000-00-00' : $invoice->tgl_invoice;
        $dates    = $main->quo_type == '1' ? $main->created_at : $main->quo_order_at;
        $getppn   = GetPPN($time_inv, $dates);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $nr1, "VAT");
        $excel->setActiveSheetIndex(0)->setCellValue('K' . $nr1, '=K' . $nr . '*' . $getppn . '%');
        $excel->getActiveSheet()->getStyle('K' . $nr1)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $nr1, '=L' . $nr . '*' . $getppn . '%');
        $excel->getActiveSheet()->getStyle('L' . $nr1)->getNumberFormat()->setFormatCode('#,##0');

        $excel->setActiveSheetIndex(0)->setCellValue('J' . $nr2, "INVOICE");
        $excel->setActiveSheetIndex(0)->setCellValue('K' . $nr2, '=SUM(K' . $nr . ':K' . $nr1 . ')');
        $excel->getActiveSheet()->getStyle('K' . $nr2)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $nr2, '=SUM(L' . $nr . ':L' . $nr1 . ')');
        $excel->getActiveSheet()->getStyle('L' . $nr2)->getNumberFormat()->setFormatCode('#,##0');

        $excel->setActiveSheetIndex(0)->setCellValue('M' . $nr, '=SUM(M' . $z_n . ':M' . $num_rows . ')');
        $excel->getActiveSheet()->getStyle('M' . $nr)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $nr, "");

        //SET ROW
        $excel->getActiveSheet()->getStyle('A' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('K' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('L' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('M' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('N' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getRowDimension($nr)->setRowHeight(25);


        $excel->getActiveSheet()->getStyle('A' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('K' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('L' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('M' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('N' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getRowDimension($nr2)->setRowHeight(25);


        $excel->getActiveSheet()->getStyle('A' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('K' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('L' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('M' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('N' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getRowDimension($nr1)->setRowHeight(25);

        //// ================================ TERM OF PAYMENT ==============================

        $next_row  = $nr + 4;
        $next_row2 = $next_row + 1;
        $next_row3 = $next_row2 + 1;
        $next_row4 = $next_row3 + 1;
        $next_row5 = $next_row4 + 1;
        $next_row6 = $next_row5 + 1;
        //NOTE 
        $excel->getActiveSheet()->mergeCells('A' . $next_row . ':B' . $next_row);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $next_row, "Term of payment from CUSTOMER");

        $excel->getActiveSheet()->getStyle('A' . $next_row)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $next_row, ":");

        $excel->getActiveSheet()->mergeCells('A' . $next_row2 . ':B' . $next_row2);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $next_row2, "Term of payment from VENDOR");
        $excel->getActiveSheet()->getStyle('A' . $next_row2)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('C' . $next_row2, ":");
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $next_row3, "NOTE");
        $excel->getActiveSheet()->getStyle('A' . $next_row3)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('A' . $next_row3 . ':B' . $next_row4);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $next_row3, ":");

        for ($a = $next_row; $a <= $next_row4; $a++) {
            $excel->getActiveSheet()->getStyle('A' . $a)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B' . $a)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C' . $a)->applyFromArray($style_col2);
        }

        ///==================================END TOP =======================================

        //// ================================ PROJECT COSTING ==============================

        $excel->getActiveSheet()->mergeCells('J' . $next_row . ':N' . $next_row);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row, "Project Costing: (list all possible COGS items here)");
        $excel->getActiveSheet()->getStyle('J' . $next_row)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('J' . $next_row2 . ':K' . $next_row2);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row2, "");
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $next_row2, "Estimasi");
        $excel->getActiveSheet()->getStyle('L' . $next_row2)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('M' . $next_row2, "By Customer");
        $excel->getActiveSheet()->getStyle('M' . $next_row2)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row2, "By MEG");
        $excel->getActiveSheet()->getStyle('N' . $next_row2)->getFont()->setBold(TRUE);
        $selisih_ongkir = $total_ongkir - 0;

        // ==== ONGKIR ==== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row3 . ':K' . $next_row3);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row3, "Ongkos Kirim");
        $excel->getActiveSheet()->getStyle('J' . $next_row3)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('L' . $next_row3, $price->ongkir_customer);
        $excel->getActiveSheet()->getStyle('L' . $next_row3)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('M' . $next_row3, $price->ongkir_customer);
        $excel->getActiveSheet()->getStyle('M' . $next_row3)->getNumberFormat()->setFormatCode('#,##0');

        $excel->setActiveSheetIndex(0)->setCellValue('M' . $next_row3, '=L' . $next_row3 . '-M' . $next_row3);
        $excel->getActiveSheet()->getStyle('L' . $next_row3)->getNumberFormat()->setFormatCode('#,##0');

        // ==== IF ===== //
        $price_if    = $price->price_if_type == 'percen' ? '=N' . ($next_row4 + 8) . '*' . $price->price_if . '/100' : $price->price_if;

        $excel->getActiveSheet()->mergeCells('J' . $next_row4 . ':K' . $next_row4);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row4, "IF");
        $excel->getActiveSheet()->getStyle('J' . $next_row4)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row4, $price_if);
        $excel->getActiveSheet()->getStyle('N' . $next_row4)->getNumberFormat()->setFormatCode('#,##0');


        // ===== LAIN2 ===== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row5 . ':K' . $next_row5);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row5, "LAIN LAIN");
        $excel->getActiveSheet()->getStyle('J' . $next_row5)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row5, $price->price_other);
        $excel->getActiveSheet()->getStyle('N' . $next_row5)->getNumberFormat()->setFormatCode('#,##0');


        // ===== COF ===== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row6 . ':K' . $next_row6);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row6, "COF");
        $excel->getActiveSheet()->getStyle('J' . $next_row6)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row6, '=M' . $nr . '*1/100');
        $excel->getActiveSheet()->getStyle('N' . $next_row6)->getNumberFormat()->setFormatCode('#,##0');


        for ($d = $next_row6 + 1; $d <= $next_row6 + 2; $d++) {
            $excel->getActiveSheet()->mergeCells('J' . $d . ':K' . $d);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $d, "");
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $d, "");
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $d, "");
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $d, "");
        }

        $row_total_biaya = $d;

        $excel->getActiveSheet()->mergeCells('J' . $row_total_biaya . ':K' . $row_total_biaya);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $row_total_biaya, "");
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $row_total_biaya, "");
        $excel->setActiveSheetIndex(0)->setCellValue('M' . $row_total_biaya, "Total Biaya");
        $excel->getActiveSheet()->getStyle('M' . $row_total_biaya)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $row_total_biaya, '=SUM(N' . $next_row3 . ':N' . $next_row6 . ')');
        $excel->getActiveSheet()->getStyle('N' . $row_total_biaya)->getNumberFormat()->setFormatCode('#,##0');
        $c  = $row_total_biaya + 1;
        $ce = $row_total_biaya + 2;
        $c8 = $row_total_biaya + 8;
        // ========================= HITUNGAN PAJAK ============================== //
        $ce1 = $ce + 1;
        $ce2 = $ce + 2;
        $ce3 = $ce + 3;
        $ce4 = $ce + 4;

        $excel->getActiveSheet()->mergeCells('J' . $c . ':N' . $c);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $c, "HITUNGAN PAJAK");
        $excel->getActiveSheet()->getStyle('J' . $c)->applyFromArray($style_abu);

        if ($main->quo_type == '5' or $main->quo_type=='9') {
            // ========================= HITUNGAN B2B ============================== //
            $excel->getActiveSheet()->mergeCells('J' . $ce . ':K' . $ce);
            $excel->getActiveSheet()->mergeCells('J' . $ce1 . ':K' . $ce1);

            $excel->getActiveSheet()->mergeCells('J' . $ce2 . ':K' . $ce2);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce2, "Selisih Pajak");
            $excel->getActiveSheet()->getStyle('J' . $ce2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce2, '=K' . $nr1 . '-L' . $nr1);
            $excel->getActiveSheet()->getStyle('N' . $ce2)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce3 . ':K' . $ce3);
            $excel->getActiveSheet()->mergeCells('J' . $ce4 . ':K' . $ce4);

            // ========================= END HITUNGAN B2B ============================== //
        } else {
            $excel->getActiveSheet()->mergeCells('J' . $ce . ':K' . $ce);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce, "PPH");
            $excel->getActiveSheet()->getStyle('J' . $ce)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce, '=K' . $nr . '*1.5%');
            $excel->getActiveSheet()->getStyle('N' . $ce)->getNumberFormat()->setFormatCode('#,##0');


            $excel->getActiveSheet()->mergeCells('J' . $ce1 . ':K' . $ce1);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce1, "SP2D");
            $excel->getActiveSheet()->getStyle('J' . $ce1)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce1, '=K' . $nr . '-N' . $ce);
            $excel->getActiveSheet()->getStyle('N' . $ce1)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce2 . ':K' . $ce2);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce2, "PPN RESTITUSI");
            $excel->getActiveSheet()->getStyle('J' . $ce2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce2, '=L' . $nr1 . '*80%');
            $excel->getActiveSheet()->getStyle('N' . $ce2)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce3 . ':K' . $ce3);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce3, "MARGIN DIMUKA");
            $excel->getActiveSheet()->getStyle('J' . $ce3)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce3, '=N' . $ce1 . '-L' . $nr . '-N' . $row_total_biaya);
            $excel->getActiveSheet()->getStyle('N' . $ce3)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce4 . ':K' . $ce4);
        }
        // ========================= POTONGAN KOMISI ============================== //
        $pk1 = $ce3 + 2;
        $pk2 = $ce3 + 3;
        $pk3 = $ce3 + 4;
        $pk4 = $ce3 + 5;

        $fm1 = $pk3 + 2;
        $fm2 = $pk3 + 3;
        $fm3 = $pk3 + 4;
        $fm4 = $pk3 + 5;
        $fm5 = $pk3 + 6;
        $fm6 = $pk3 + 7;
        $fm7 = $pk3 + 8;

        if (Session::get('division_id') == '7') {
            $excel->getActiveSheet()->mergeCells('J' . $pk1 . ':N' . $pk1);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk1, "POTONGAN KOMISI");
            $excel->getActiveSheet()->getStyle('J' . $pk1)->applyFromArray($style_abu);
            if ($main->quo_type == '5' or $main->quo_type=='9') {
                $excel->getActiveSheet()->mergeCells('J' . $pk2 . ':K' . $pk2);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk2, "KOMISI SALES");
                $excel->getActiveSheet()->getStyle('J' . $pk2)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk2, '=2.20/100');
                $excel->getActiveSheet()->getStyle('L' . $pk2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk2, '=L' . $pk2 . '*N' . $fm1);
                $excel->getActiveSheet()->getStyle('N' . $pk2)->getNumberFormat()->setFormatCode('#,##0');

                $excel->getActiveSheet()->mergeCells('J' . $pk3 . ':K' . $pk3);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk3, "KOMISI LAINNYA");
                $excel->getActiveSheet()->getStyle('J' . $pk3)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk3, '=2.60/100');
                $excel->getActiveSheet()->getStyle('L' . $pk3)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk3, '=L' . $pk3 . '*N' . $fm1);
                $excel->getActiveSheet()->getStyle('N' . $pk3)->getNumberFormat()->setFormatCode('#,##0');
            } else {
                $excel->getActiveSheet()->mergeCells('J' . $pk2 . ':K' . $pk2);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk2, "KOMISI SALES");
                $excel->getActiveSheet()->getStyle('J' . $pk2)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk2, '=2.20/100');
                $excel->getActiveSheet()->getStyle('L' . $pk2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk2, '=L' . $pk2 . '*N' . $fm3);
                $excel->getActiveSheet()->getStyle('N' . $pk2)->getNumberFormat()->setFormatCode('#,##0');

                $excel->getActiveSheet()->mergeCells('J' . $pk3 . ':K' . $pk3);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk3, "KOMISI LAINNYA");
                $excel->getActiveSheet()->getStyle('J' . $pk3)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk3, '=2.60/100');
                $excel->getActiveSheet()->getStyle('L' . $pk3)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk3, '=L' . $pk3 . '*N' . $fm3);
                $excel->getActiveSheet()->getStyle('N' . $pk3)->getNumberFormat()->setFormatCode('#,##0');
            }

            $excel->getActiveSheet()->mergeCells('J' . $pk4 . ':K' . $pk4);
        }


        // ========================= FINAL MARGIN ============================== //

        // Generate By System
        if (Session::get('division_id') == '7') {
            if ($main->quo_type == '5' or $main->quo_type=='9') {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm4);
            } else {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm6);
            }
        } else {
            if ($main->quo_type == '5' or $main->quo_type=='9') {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm2);
            } else {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm4);
            }
        }
        
        if ($main->quo_type == '5' or $main->quo_type=='9') {
            // ========================= HITUNGAN B2B ============================== //

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm1, "Subtotal Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm1)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm1, '=K' . $nr2 . '-L' . $nr2 . '-N' . $row_total_biaya . '-N' . $ce2);
            $excel->getActiveSheet()->getStyle('N' . $fm1)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm2, "SP Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm2, '=N' . $fm1 . '/K' . $nr2);
            $excel->getActiveSheet()->getStyle('N' . $fm2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $batas_akhir = $fm2;
            if (Session::get('division_id') == '7') {

                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm3, "Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm3)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm3, '=N' . $fm1 . '-N' . $pk2 . '-N' . $pk3);
                $excel->getActiveSheet()->getStyle('N' . $fm3)->getNumberFormat()->setFormatCode('#,##0');

                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm4, "% Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm4)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm4, '=N' . $fm3 . '/K' . $nr2);
                $excel->getActiveSheet()->getStyle('N' . $fm4)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $batas_akhir = $fm4;
            }
            // ========================= END HITUNGAN B2B ============================== //
        } else {

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm1, "Subtotal Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm1)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm1, '=N' . $ce1 . '-L' . $nr2 . '+N' . $ce2);
            $excel->getActiveSheet()->getStyle('N' . $fm1)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm2, "SP Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm2, '=N' . $fm1 . '/K' . $nr2);
            $excel->getActiveSheet()->getStyle('N' . $fm2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm3, "Net Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm3)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm3, '=N' . $fm1 . '-N' . $row_total_biaya);
            $excel->getActiveSheet()->getStyle('N' . $fm3)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm4, "% Net Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm4)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm4, '=N' . $fm3 . '/K' . $nr2);
            $excel->getActiveSheet()->getStyle('N' . $fm4)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $batas_akhir = $fm4;
            if (Session::get('division_id') == '7') {
                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm5, "Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm5)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm5, '=N' . $fm3 . '-N' . $pk2 . '-N' . $pk3);
                $excel->getActiveSheet()->getStyle('N' . $fm5)->getNumberFormat()->setFormatCode('#,##0');

                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm6, "% Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm6)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm6, '=N' . $fm5 . '/K' . $nr2);
                $excel->getActiveSheet()->getStyle('N' . $fm6)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

                $batas_akhir = $fm6;
            }
        }
        for ($f = $next_row; $f <= $batas_akhir; $f++) {
            $excel->getActiveSheet()->getStyle('J' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('K' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('L' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('M' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('N' . $f)->applyFromArray($style_col2);
        }

        //// ================================ END PROJECT COSTING ==============================

        $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $netm       = $excel->getActiveSheet()->getCell('N' . $fm4)->getCalculatedValue();
        $combine    = $main->id . '-' .base64_encode(Carbon::now('GMT+7')->toDateTimeString().'-'.$netm);
        $strings    = "Nomer PO ".$main->quo_no . ' dicetak pada ' .Carbon::now('GMT+7')->toDateTimeString().' dengan margin '.($netm*100);
        $folder     = '/public/qrcode/';
        $folder2    = 'public/qrcode/';
        QrCode::format('png')->generate($strings, base_path().$folder.$combine.'.png');
        $objDrawing->setName('Precall');
        $objDrawing->setDescription('Precall');
        // DD(base_path($folder2 . $combine . '.png'));
        $objDrawing->setPath(base_path($folder2 . $combine . '.png'));
        $objDrawing->setOffsetX(125);
        $objDrawing->setOffsetY(10);
        $objDrawing->setCoordinates('M1');
        $objDrawing->setHeight(100);
        $objDrawing->setWidth(100);
        $objDrawing->setWorksheet($excel->getActiveSheet());

        // $excel->setActiveSheetIndex(0)->setCellValue('J' . $fm1, "\nGenerate By System :" . Carbon::now('GMT+7')->toDateTimeString());

        $excel->getActiveSheet()->getStyle('J' . $fm1)->applyFromArray($style_note);

        //// ================================ APPROVAL ==============================

        $ra_1 = $next_row4 + 2;
        $ra_2 = $ra_1 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_1 . ':A' . $ra_2);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_1, "Jangka Waktu Pelaksanaan");
        $excel->getActiveSheet()->getStyle('A' . $ra_1)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_1 . ':C' . $ra_2);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_1, "Transfer 7 hari setelah terima barang ");

        $ra_3 = $ra_2 + 1;
        $ra_4 = $ra_3 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_3 . ':A' . $ra_4);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_3, "Opportunity Associate Tender");
        $excel->getActiveSheet()->getStyle('A' . $ra_3)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_3 . ':B' . $ra_4);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_3, '');
        $excel->getActiveSheet()->getStyle('B' . $ra_3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_3 . ':C' . $ra_4);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_3, "");

        $ra_5 = $ra_4 + 1;
        $ra_6 = $ra_5 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_5 . ':A' . $ra_6);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_5, "Opportunity Spv Sales ");
        $excel->getActiveSheet()->getStyle('A' . $ra_5)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_5 . ':B' . $ra_6);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_5, '');
        $excel->getActiveSheet()->getStyle('B' . $ra_5)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_5 . ':C' . $ra_6);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_5, "");

        $ra_7 = $ra_6 + 1;
        $ra_8 = $ra_7 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_7 . ':A' . $ra_8);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_7, "Opportunity Sales Manager");
        $excel->getActiveSheet()->getStyle('A' . $ra_7)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_7 . ':B' . $ra_8);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_7, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_7)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_7 . ':C' . $ra_8);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_7, "");

        $ra_9   = $ra_8 + 1;
        $ra_10  = $ra_9 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_9 . ':A' . $ra_10);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_9, "Business Unit Finance Manager");
        $excel->getActiveSheet()->getStyle('A' . $ra_9)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_9 . ':B' . $ra_10);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_9, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_9)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('C' . $ra_9 . ':C' . $ra_10);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_9, "");

        $ra_11  = $ra_10 + 1;
        $ra_12  = $ra_11 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_11 . ':A' . $ra_12);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_11, "Director");
        $excel->getActiveSheet()->getStyle('A' . $ra_11)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_11 . ':B' . $ra_12);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_11, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_11)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_11 . ':C' . $ra_12);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_11, "");

        $ra_13  = $ra_12 + 1;
        $ra_14  = $ra_13 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_13 . ':A' . $ra_14);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_13, "Owner Head");
        $excel->getActiveSheet()->getStyle('A' . $ra_13)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_13 . ':B' . $ra_14);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_13, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_13)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_13 . ':C' . $ra_14);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_13, "");

        $excel->getActiveSheet()->getStyle('A' . $ra_1 . ':C' . $ra_14)->getAlignment()->setWrapText(true);

        for ($xy = $ra_1; $xy <= $ra_14; $xy++) {
            $excel->getActiveSheet()->getStyle('A' . $xy)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B' . $xy)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C' . $xy)->applyFromArray($style_col2);
        }

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Precalc Paket");
        $excel->setActiveSheetIndex(0);


        $writer = new Xlsx($excel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        $writer->save('php://output');
    }
    public function precall_excel_migrate($id)
    {
        $main = SalesMigrationModel::select('quotation_models_old.*', 'q.type_name')
            ->where('quotation_models_old.id', $id)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models_old.quo_type')->first();
        $product      = SalesMigrationProduct::where('id_quo', $id)->get();
        $total_ongkir = SalesMigrationProduct::sumongkir($id);
        $price        = QuotationOtherPrice::where('id_quo', $id)->first();
        $invoice      = QuotationInvoice::where('id_quo', $id)->first();
        $cust         = CustomerModel::where('id', $main->id_customer)->first();
        $pic          = Customer_pic::where('id_customer', $cust->id)->first();
        $orang        = $pic == null ? "No Data" : $pic->name;
        $title        = 'Precalc-' . $main->quo_no . '.xlsx';

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        // setting file //
        $excel->getProperties()->setCreator('HomeMaleser')
            ->setLastModifiedBy('HomeMaleser')
            ->setTitle('Precalc-' . $main->quo_no)
            ->setSubject('Precalc-' . $main->quo_no)
            ->setDescription('Precalc-' . $main->quo_no)
            ->setKeywords('Precalc-' . $main->quo_no);

        // end setting file //

        // styling //

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

        $style_col2 = array(
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

        $style_abu = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'e3e1e1')
            )
        );

        $style_note = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_BOTTOM      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
        );
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
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

        $precalc = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            )
        );

        $style_barang = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER

            ),

            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );
        // end styling //

        // header 

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "PT Mitra Era Global");
        $excel->getActiveSheet()->mergeCells('A1:M3');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
        $excel->getActiveSheet()->mergeCells('A4:M4');
        $excel->getActiveSheet()->getRowDimension(4)->setRowHeight(10);
        $excel->getActiveSheet()->mergeCells('A5:M5');
        $excel->getActiveSheet()->getRowDimension(5)->setRowHeight(10);

        $excel->setActiveSheetIndex(0)->setCellValue('A6', "Satuan Kerja");
        $excel->getActiveSheet()->mergeCells('A6:B6');
        $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C6', $cust->company);
        $excel->getActiveSheet()->getStyle('C6:D' . $excel->getActiveSheet()->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->mergeCells('C6:D6');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('A7', "Customer PIC");
        $excel->getActiveSheet()->mergeCells('A7:B7');
        $excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C7', $orang);
        $excel->getActiveSheet()->mergeCells('C7:D7');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('A8', "Opportunity Owner");
        $excel->getActiveSheet()->mergeCells('A8:B8');
        $excel->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C8', getEmp($main->id_sales)->emp_name);
        $excel->getActiveSheet()->mergeCells('C8:D8');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('E6', "Project Name");
        $excel->getActiveSheet()->mergeCells('E6:G6');
        $excel->getActiveSheet()->getStyle('E6')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('H6', $main->quo_name);
        $excel->getActiveSheet()->mergeCells('H6:N6');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('E7', "Instansi/Company Name:");
        $excel->getActiveSheet()->mergeCells('E7:G7');
        $excel->getActiveSheet()->getStyle('E7')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('H7', $cust->company);
        $excel->getActiveSheet()->mergeCells('H7:J7');
        $excel->setActiveSheetIndex(0)->setCellValue('K7', "ORDER NO:");
        $excel->getActiveSheet()->getStyle('K7')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('L7', $main->quo_no);
        $excel->getActiveSheet()->mergeCells('L7:N7');
        //===========================================================================================

        $excel->setActiveSheetIndex(0)->setCellValue('E8', "Opp Owner Head:");
        $excel->getActiveSheet()->mergeCells('E8:G8');
        $excel->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('H8', getEmp('1')->emp_name);
        $excel->getActiveSheet()->mergeCells('H8:N8');

        $excel->getActiveSheet()->mergeCells('A9:N9');
        $excel->getActiveSheet()->getRowDimension(9)->setRowHeight(10);

        $excel->setActiveSheetIndex(0)->setCellValue('A10', "PRE-CALCULATION");
        $excel->getActiveSheet()->mergeCells('A10:N10');
        $excel->getActiveSheet()->getStyle('A10')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A10')->getFont()->setSize(22);
        $excel->getActiveSheet()->getStyle('A10:N10')->applyFromArray($precalc);
        $excel->getActiveSheet()->getRowDimension(10)->setRowHeight(20);

        $excel->getActiveSheet()->mergeCells('A11:N11');
        $excel->getActiveSheet()->getRowDimension(11)->setRowHeight(10);

        $excel->getActiveSheet()->getStyle('A1:N5')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A6:N6')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A7:N7')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A8:N8')->applyFromArray($style_row);
        $excel->getActiveSheet()->getStyle('A9:N11')->applyFromArray($style_row);

        for ($x = 6; $x <= 8; $x++) {

            $excel->getActiveSheet()->getStyle('A' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('D' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('E' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('F' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('G' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('H' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('I' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('J' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('K' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('L' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('M' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('N' . $x)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getRowDimension($x)->setRowHeight(30);
        }
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(41);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);

        // end header 

        // ======================================== ROW BARANG ====================================//

        // Head Table

        $excel->setActiveSheetIndex(0)->setCellValue('A13', "No");
        $excel->getActiveSheet()->mergeCells('A13:A14');
        $excel->getActiveSheet()->getStyle('A13')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('B13', "SKU");
        $excel->getActiveSheet()->mergeCells('B13:B14');
        $excel->getActiveSheet()->getStyle('B14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C13', "Description");
        $excel->getActiveSheet()->mergeCells('C13:C14');
        $excel->getActiveSheet()->getStyle('C14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('D13', "Item Type (HW/SW)");
        $excel->getActiveSheet()->mergeCells('D13:D14');
        $excel->getActiveSheet()->getStyle('D14')->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('E13', "Vendor");
        $excel->getActiveSheet()->mergeCells('E13:E14');
        $excel->getActiveSheet()->getStyle('E14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('F13', "QTY");
        $excel->getActiveSheet()->mergeCells('F13:F14');
        $excel->getActiveSheet()->getStyle('F14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('G13', "CCY");
        $excel->getActiveSheet()->mergeCells('G13:G14');
        $excel->getActiveSheet()->getStyle('G14')->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('H13', "Unit Live");
        $excel->getActiveSheet()->mergeCells('H13:H14');
        $excel->getActiveSheet()->getStyle('H14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('I13', "Unit Price");
        $excel->getActiveSheet()->mergeCells('I13:I14');
        $excel->getActiveSheet()->getStyle('I14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('J13', "LLC / Modal");
        $excel->getActiveSheet()->mergeCells('J13:J14');
        $excel->getActiveSheet()->getStyle('J14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('K13', "Contract Price");
        $excel->getActiveSheet()->mergeCells('K13:K14');
        $excel->getActiveSheet()->getStyle('K14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('L13', "COGS");
        $excel->getActiveSheet()->mergeCells('L13:L14');
        $excel->getActiveSheet()->getStyle('L14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('M13', "Margin");
        $excel->getActiveSheet()->mergeCells('M13:M14');
        $excel->getActiveSheet()->getStyle('M14')->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N13', "Est. Avail Date");
        $excel->getActiveSheet()->mergeCells('N13:N14');
        $excel->getActiveSheet()->getStyle('N14')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A13:N14')->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->getRowDimension(15)->setRowHeight(10);

        $excel->getActiveSheet()->getStyle('A13:N14')->applyFromArray($style_row);
        for ($y = 13; $y <= 14; $y++) {

            $excel->getActiveSheet()->getStyle('A' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('K' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('L' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('M' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('N' . $y)->applyFromArray($style_col);
            $excel->getActiveSheet()->getRowDimension($y)->setRowHeight(25);
        }

        $z   = 16;
        $av  = 1;
        $z_n = 16;
        foreach ($product as $key => $ad) {


            // $cstock      = StockCheck($ad->id_product, $main->id);
            $vendor_name = $ad->id_vendor == null ? "" : getVendor($ad->id_vendor)->vendor_name;

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $z, $av++);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $z, $ad->id_product);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $z, $ad->id_product);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $z, "Hardware");
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $z, $vendor_name);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $z, $ad->det_quo_qty);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $z, "IDR");
            $unit_price = (float)$ad->det_quo_harga_order;
            $live_price = (float)$ad->det_quo_harga_order;

            $excel->setActiveSheetIndex(0)->setCellValue('H' . $z, $live_price);
            $excel->getActiveSheet()->getStyle('H' . $z)->getNumberFormat()->setFormatCode('#,##0');
            $modal = round($ad->det_quo_harga_modal);

            $excel->setActiveSheetIndex(0)->setCellValue('I' . $z, $unit_price);
            $excel->getActiveSheet()->getStyle('I' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('J' . $z, $modal);
            $excel->getActiveSheet()->getStyle('J' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('K' . $z, '=I' . $z . '*F' . $z . '');
            $excel->getActiveSheet()->getStyle('K' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('L' . $z, '=J' . $z . '*F' . $z . '');
            $excel->getActiveSheet()->getStyle('L' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $z, '=K' . $z . '-L' . $z . '');
            $excel->getActiveSheet()->getStyle('M' . $z)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('N' . $z, $ad->det_quo_status_vendor);




            $excel->getActiveSheet()->getStyle('A' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('B' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('C' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('D' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('E' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('F' . $z)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $z)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('I' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('J' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('K' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('L' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('M' . $z)->applyFromArray($style_barang);
            $excel->getActiveSheet()->getStyle('N' . $z)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('A' . $z . ':N' . $z)->getAlignment()->setWrapText(true);
            $z++;
        }

        $size = count($product);
        switch ($size) {
            case in_array($size, range(1, 3)):
                $tambah = 3;
                break;
            case in_array($size, range(4, 5)):
                $tambah = 2;
                break;

            default:
                $tambah = 1;
                break;
        }
        $num_rows = $excel->getActiveSheet()->getHighestRow();
        $excel->getActiveSheet()->insertNewRowBefore($num_rows + 1, $tambah);
        $nr  = $excel->getActiveSheet()->getHighestRow() + 1;
        $nr1 = $excel->getActiveSheet()->getHighestRow() + 2;
        $nr2 = $excel->getActiveSheet()->getHighestRow() + 3;

        $excel->getActiveSheet()->mergeCells('A' . $nr . ':I' . $nr);
        $excel->getActiveSheet()->mergeCells('A' . $nr1 . ':I' . $nr1);
        $excel->getActiveSheet()->mergeCells('A' . $nr2 . ':I' . $nr2);

        $excel->setActiveSheetIndex(0)->setCellValue('J' . $nr, "Grand Total");
        $excel->setActiveSheetIndex(0)->setCellValue('K' . $nr, '=SUM(K' . $z_n . ':K' . $num_rows . ')');
        $excel->getActiveSheet()->getStyle('K' . $nr)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $nr, '=SUM(L' . $z_n . ':L' . $num_rows . ')');
        $excel->getActiveSheet()->getStyle('L' . $nr)->getNumberFormat()->setFormatCode('#,##0');

        $time_inv = $invoice == null ? '0000-00-00' : $invoice->tgl_invoice;
        $getppn = GetPPN($time_inv, $main->quo_order_at);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $nr1, "VAT");
        $excel->setActiveSheetIndex(0)->setCellValue('K' . $nr1, '=K' . $nr . '*' . $getppn . '%');
        $excel->getActiveSheet()->getStyle('K' . $nr1)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $nr1, '=L' . $nr . '*' . $getppn . '%');
        $excel->getActiveSheet()->getStyle('L' . $nr1)->getNumberFormat()->setFormatCode('#,##0');

        $excel->setActiveSheetIndex(0)->setCellValue('J' . $nr2, "INVOICE");
        $excel->setActiveSheetIndex(0)->setCellValue('K' . $nr2, '=SUM(K' . $nr . ':K' . $nr1 . ')');
        $excel->getActiveSheet()->getStyle('K' . $nr2)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $nr2, '=SUM(L' . $nr . ':L' . $nr1 . ')');
        $excel->getActiveSheet()->getStyle('L' . $nr2)->getNumberFormat()->setFormatCode('#,##0');

        $excel->setActiveSheetIndex(0)->setCellValue('M' . $nr, '=SUM(M' . $z_n . ':M' . $num_rows . ')');
        $excel->getActiveSheet()->getStyle('M' . $nr)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $nr, "");

        //SET ROW
        $excel->getActiveSheet()->getStyle('A' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('K' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('L' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('M' . $nr)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('N' . $nr)->applyFromArray($style_col);
        $excel->getActiveSheet()->getRowDimension($nr)->setRowHeight(25);


        $excel->getActiveSheet()->getStyle('A' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I' . $nr2)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('K' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('L' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('M' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('N' . $nr2)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getRowDimension($nr2)->setRowHeight(25);


        $excel->getActiveSheet()->getStyle('A' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I' . $nr1)->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('K' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('L' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('M' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getStyle('N' . $nr1)->applyFromArray($style_barang);
        $excel->getActiveSheet()->getRowDimension($nr1)->setRowHeight(25);

        //// ================================ TERM OF PAYMENT ==============================

        $next_row  = $nr + 4;
        $next_row2 = $next_row + 1;
        $next_row3 = $next_row2 + 1;
        $next_row4 = $next_row3 + 1;
        $next_row5 = $next_row4 + 1;
        $next_row6 = $next_row5 + 1;
        //NOTE 
        $excel->getActiveSheet()->mergeCells('A' . $next_row . ':B' . $next_row);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $next_row, "Term of payment from CUSTOMER");

        $excel->getActiveSheet()->getStyle('A' . $next_row)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $next_row, ":");

        $excel->getActiveSheet()->mergeCells('A' . $next_row2 . ':B' . $next_row2);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $next_row2, "Term of payment from VENDOR");
        $excel->getActiveSheet()->getStyle('A' . $next_row2)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('C' . $next_row2, ":");
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $next_row3, "NOTE");
        $excel->getActiveSheet()->getStyle('A' . $next_row3)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('A' . $next_row3 . ':B' . $next_row4);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $next_row3, ":");

        for ($a = $next_row; $a <= $next_row4; $a++) {
            $excel->getActiveSheet()->getStyle('A' . $a)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B' . $a)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C' . $a)->applyFromArray($style_col2);
        }

        ///==================================END TOP =======================================

        //// ================================ PROJECT COSTING ==============================

        $excel->getActiveSheet()->mergeCells('J' . $next_row . ':N' . $next_row);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row, "Project Costing: (list all possible COGS items here)");
        $excel->getActiveSheet()->getStyle('J' . $next_row)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('J' . $next_row2 . ':K' . $next_row2);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row2, "");
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $next_row2, "Estimasi");
        $excel->getActiveSheet()->getStyle('L' . $next_row2)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('M' . $next_row2, "By Customer");
        $excel->getActiveSheet()->getStyle('M' . $next_row2)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row2, "By MEG");
        $excel->getActiveSheet()->getStyle('N' . $next_row2)->getFont()->setBold(TRUE);
        $selisih_ongkir = $total_ongkir - 0;

        // ==== ONGKIR ==== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row3 . ':K' . $next_row3);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row3, "Ongkos Kirim");
        $excel->getActiveSheet()->getStyle('J' . $next_row3)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('L' . $next_row3, $total_ongkir);
        $excel->getActiveSheet()->getStyle('L' . $next_row3)->getNumberFormat()->setFormatCode('#,##0');
        $excel->setActiveSheetIndex(0)->setCellValue('M' . $next_row3, $total_ongkir);
        $excel->getActiveSheet()->getStyle('M' . $next_row3)->getNumberFormat()->setFormatCode('#,##0');

        $excel->setActiveSheetIndex(0)->setCellValue('M' . $next_row3, '=L' . $next_row3 . '-M' . $next_row3);
        $excel->getActiveSheet()->getStyle('L' . $next_row3)->getNumberFormat()->setFormatCode('#,##0');

        // ==== IF ===== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row4 . ':K' . $next_row4);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row4, "IF");
        $excel->getActiveSheet()->getStyle('J' . $next_row4)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row4, 0);
        $excel->getActiveSheet()->getStyle('N' . $next_row4)->getNumberFormat()->setFormatCode('#,##0');


        // ===== LAIN2 ===== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row5 . ':K' . $next_row5);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row5, "LAIN LAIN");
        $excel->getActiveSheet()->getStyle('J' . $next_row5)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row5, 0);
        $excel->getActiveSheet()->getStyle('N' . $next_row5)->getNumberFormat()->setFormatCode('#,##0');


        // ===== COF ===== //

        $excel->getActiveSheet()->mergeCells('J' . $next_row6 . ':K' . $next_row6);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $next_row6, "COF");
        $excel->getActiveSheet()->getStyle('J' . $next_row6)->getFont()->setBold(TRUE);

        $excel->setActiveSheetIndex(0)->setCellValue('N' . $next_row6, '=M' . $nr . '*1/100');
        $excel->getActiveSheet()->getStyle('N' . $next_row6)->getNumberFormat()->setFormatCode('#,##0');


        for ($d = $next_row6 + 1; $d <= $next_row6 + 2; $d++) {
            $excel->getActiveSheet()->mergeCells('J' . $d . ':K' . $d);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $d, "");
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $d, "");
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $d, "");
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $d, "");
        }

        $row_total_biaya = $d;

        $excel->getActiveSheet()->mergeCells('J' . $row_total_biaya . ':K' . $row_total_biaya);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $row_total_biaya, "");
        $excel->setActiveSheetIndex(0)->setCellValue('L' . $row_total_biaya, "");
        $excel->setActiveSheetIndex(0)->setCellValue('M' . $row_total_biaya, "Total Biaya");
        $excel->getActiveSheet()->getStyle('M' . $row_total_biaya)->getFont()->setBold(TRUE);
        $excel->setActiveSheetIndex(0)->setCellValue('N' . $row_total_biaya, '=SUM(N' . $next_row3 . ':N' . $next_row6 . ')');
        $excel->getActiveSheet()->getStyle('N' . $row_total_biaya)->getNumberFormat()->setFormatCode('#,##0');
        $c  = $row_total_biaya + 1;
        $ce = $row_total_biaya + 2;
        $c8 = $row_total_biaya + 8;
        // ========================= HITUNGAN PAJAK ============================== //
        $ce1 = $ce + 1;
        $ce2 = $ce + 2;
        $ce3 = $ce + 3;
        $ce4 = $ce + 4;

        $excel->getActiveSheet()->mergeCells('J' . $c . ':N' . $c);
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $c, "HITUNGAN PAJAK");
        $excel->getActiveSheet()->getStyle('J' . $c)->applyFromArray($style_abu);

        if ($main->quo_type == '5' or $main->quo_type=='9') {
            // ========================= HITUNGAN B2B ============================== //
            $excel->getActiveSheet()->mergeCells('J' . $ce . ':K' . $ce);
            $excel->getActiveSheet()->mergeCells('J' . $ce1 . ':K' . $ce1);

            $excel->getActiveSheet()->mergeCells('J' . $ce2 . ':K' . $ce2);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce2, "Selisih Pajak");
            $excel->getActiveSheet()->getStyle('J' . $ce2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce2, '=K' . $nr1 . '-L' . $nr1);
            $excel->getActiveSheet()->getStyle('N' . $ce2)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce3 . ':K' . $ce3);
            $excel->getActiveSheet()->mergeCells('J' . $ce4 . ':K' . $ce4);

            // ========================= END HITUNGAN B2B ============================== //
        } else {
            $excel->getActiveSheet()->mergeCells('J' . $ce . ':K' . $ce);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce, "PPH");
            $excel->getActiveSheet()->getStyle('J' . $ce)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce, '=K' . $nr . '*1.5%');
            $excel->getActiveSheet()->getStyle('N' . $ce)->getNumberFormat()->setFormatCode('#,##0');


            $excel->getActiveSheet()->mergeCells('J' . $ce1 . ':K' . $ce1);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce1, "SP2D");
            $excel->getActiveSheet()->getStyle('J' . $ce1)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce1, '=K' . $nr . '-N' . $ce);
            $excel->getActiveSheet()->getStyle('N' . $ce1)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce2 . ':K' . $ce2);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce2, "PPN RESTITUSI");
            $excel->getActiveSheet()->getStyle('J' . $ce2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce2, '=L' . $nr1 . '*80%');
            $excel->getActiveSheet()->getStyle('N' . $ce2)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce3 . ':K' . $ce3);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $ce3, "MARGIN DIMUKA");
            $excel->getActiveSheet()->getStyle('J' . $ce3)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $ce3, '=N' . $ce1 . '-L' . $nr . '-N' . $row_total_biaya);
            $excel->getActiveSheet()->getStyle('N' . $ce3)->getNumberFormat()->setFormatCode('#,##0');

            $excel->getActiveSheet()->mergeCells('J' . $ce4 . ':K' . $ce4);
        }
        // ========================= POTONGAN KOMISI ============================== //
        $pk1 = $ce3 + 2;
        $pk2 = $ce3 + 3;
        $pk3 = $ce3 + 4;
        $pk4 = $ce3 + 5;

        $fm1 = $pk3 + 2;
        $fm2 = $pk3 + 3;
        $fm3 = $pk3 + 4;
        $fm4 = $pk3 + 5;
        $fm5 = $pk3 + 6;
        $fm6 = $pk3 + 7;
        $fm7 = $pk3 + 8;

        if (Session::get('division_id') == '7') {
            $excel->getActiveSheet()->mergeCells('J' . $pk1 . ':N' . $pk1);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk1, "POTONGAN KOMISI");
            $excel->getActiveSheet()->getStyle('J' . $pk1)->applyFromArray($style_abu);
            if ($main->quo_type == '5' or $main->quo_type=='9') {
                $excel->getActiveSheet()->mergeCells('J' . $pk2 . ':K' . $pk2);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk2, "KOMISI SALES");
                $excel->getActiveSheet()->getStyle('J' . $pk2)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk2, '=2.20/100');
                $excel->getActiveSheet()->getStyle('L' . $pk2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk2, '=L' . $pk2 . '*N' . $fm1);
                $excel->getActiveSheet()->getStyle('N' . $pk2)->getNumberFormat()->setFormatCode('#,##0');

                $excel->getActiveSheet()->mergeCells('J' . $pk3 . ':K' . $pk3);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk3, "KOMISI LAINNYA");
                $excel->getActiveSheet()->getStyle('J' . $pk3)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk3, '=2.60/100');
                $excel->getActiveSheet()->getStyle('L' . $pk3)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk3, '=L' . $pk3 . '*N' . $fm1);
                $excel->getActiveSheet()->getStyle('N' . $pk3)->getNumberFormat()->setFormatCode('#,##0');
            } else {
                $excel->getActiveSheet()->mergeCells('J' . $pk2 . ':K' . $pk2);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk2, "KOMISI SALES");
                $excel->getActiveSheet()->getStyle('J' . $pk2)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk2, '=2.20/100');
                $excel->getActiveSheet()->getStyle('L' . $pk2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk2, '=L' . $pk2 . '*N' . $fm3);
                $excel->getActiveSheet()->getStyle('N' . $pk2)->getNumberFormat()->setFormatCode('#,##0');

                $excel->getActiveSheet()->mergeCells('J' . $pk3 . ':K' . $pk3);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $pk3, "KOMISI LAINNYA");
                $excel->getActiveSheet()->getStyle('J' . $pk3)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('L' . $pk3, '=2.60/100');
                $excel->getActiveSheet()->getStyle('L' . $pk3)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $pk3, '=L' . $pk3 . '*N' . $fm3);
                $excel->getActiveSheet()->getStyle('N' . $pk3)->getNumberFormat()->setFormatCode('#,##0');
            }

            $excel->getActiveSheet()->mergeCells('J' . $pk4 . ':K' . $pk4);
        }


        // ========================= FINAL MARGIN ============================== //

        // Generate By System
        if (Session::get('division_id') == '7') {
            if ($main->quo_type == '5' or $main->quo_type=='9') {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm4);
            } else {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm6);
            }
        } else {
            if ($main->quo_type == '5' or $main->quo_type=='9') {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm2);
            } else {
                $excel->getActiveSheet()->mergeCells('J' . $fm1 . ':L' . $fm4);
            }
        }
        $excel->setActiveSheetIndex(0)->setCellValue('J' . $fm1, "Generate By System :" . Carbon::now('GMT+7')->toDateTimeString());
        $excel->getActiveSheet()->getStyle('J' . $fm1)->applyFromArray($style_note);

        if ($main->quo_type == '5' or $main->quo_type=='9') {
            // ========================= HITUNGAN B2B ============================== //

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm1, "Subtotal Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm1)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm1, '=K' . $nr2 . '-L' . $nr2 . '-N' . $row_total_biaya . '-N' . $ce2);
            $excel->getActiveSheet()->getStyle('N' . $fm1)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm2, "SP Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm2, '=N' . $fm1 . '/K' . $nr2);
            $excel->getActiveSheet()->getStyle('N' . $fm2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $batas_akhir = $fm2;
            if (Session::get('division_id') == '7') {

                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm3, "Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm3)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm3, '=N' . $fm1 . '-N' . $pk2 . '-N' . $pk3);
                $excel->getActiveSheet()->getStyle('N' . $fm3)->getNumberFormat()->setFormatCode('#,##0');

                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm4, "% Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm4)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm4, '=N' . $fm3 . '/K' . $nr2);
                $excel->getActiveSheet()->getStyle('N' . $fm4)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $batas_akhir = $fm4;
            }
            // ========================= END HITUNGAN B2B ============================== //
        } else {

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm1, "Subtotal Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm1)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm1, '=N' . $ce1 . '-L' . $nr2 . '+N' . $ce2);
            $excel->getActiveSheet()->getStyle('N' . $fm1)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm2, "SP Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm2)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm2, '=N' . $fm1 . '/K' . $nr2);
            $excel->getActiveSheet()->getStyle('N' . $fm2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm3, "Net Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm3)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm3, '=N' . $fm1 . '-N' . $row_total_biaya);
            $excel->getActiveSheet()->getStyle('N' . $fm3)->getNumberFormat()->setFormatCode('#,##0');

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm4, "% Net Margin");
            $excel->getActiveSheet()->getStyle('M' . $fm4)->getFont()->setBold(TRUE);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm4, '=N' . $fm3 . '/K' . $nr2);
            $excel->getActiveSheet()->getStyle('N' . $fm4)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $batas_akhir = $fm4;
            if (Session::get('division_id') == '7') {
                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm5, "Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm5)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm5, '=N' . $fm3 . '-N' . $pk2 . '-N' . $pk3);
                $excel->getActiveSheet()->getStyle('N' . $fm5)->getNumberFormat()->setFormatCode('#,##0');

                $excel->setActiveSheetIndex(0)->setCellValue('M' . $fm6, "% Final Margin");
                $excel->getActiveSheet()->getStyle('M' . $fm6)->getFont()->setBold(TRUE);
                $excel->setActiveSheetIndex(0)->setCellValue('N' . $fm6, '=N' . $fm5 . '/K' . $nr2);
                $excel->getActiveSheet()->getStyle('N' . $fm6)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

                $batas_akhir = $fm6;
            }
        }
        for ($f = $next_row; $f <= $batas_akhir; $f++) {
            $excel->getActiveSheet()->getStyle('J' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('K' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('L' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('M' . $f)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('N' . $f)->applyFromArray($style_col2);
        }

        //// ================================ END PROJECT COSTING ==============================

        //// ================================ APPROVAL ==============================

        $ra_1 = $next_row4 + 2;
        $ra_2 = $ra_1 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_1 . ':A' . $ra_2);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_1, "Jangka Waktu Pelaksanaan");
        $excel->getActiveSheet()->getStyle('A' . $ra_1)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_1 . ':C' . $ra_2);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_1, "Transfer 7 hari setelah terima barang ");

        $ra_3 = $ra_2 + 1;
        $ra_4 = $ra_3 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_3 . ':A' . $ra_4);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_3, "Opportunity Associate Tender");
        $excel->getActiveSheet()->getStyle('A' . $ra_3)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_3 . ':B' . $ra_4);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_3, '');
        $excel->getActiveSheet()->getStyle('B' . $ra_3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_3 . ':C' . $ra_4);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_3, "");

        $ra_5 = $ra_4 + 1;
        $ra_6 = $ra_5 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_5 . ':A' . $ra_6);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_5, "Opportunity Spv Sales ");
        $excel->getActiveSheet()->getStyle('A' . $ra_5)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_5 . ':B' . $ra_6);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_5, '');
        $excel->getActiveSheet()->getStyle('B' . $ra_5)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_5 . ':C' . $ra_6);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_5, "");

        $ra_7 = $ra_6 + 1;
        $ra_8 = $ra_7 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_7 . ':A' . $ra_8);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_7, "Opportunity Sales Manager");
        $excel->getActiveSheet()->getStyle('A' . $ra_7)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_7 . ':B' . $ra_8);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_7, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_7)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_7 . ':C' . $ra_8);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_7, "");

        $ra_9   = $ra_8 + 1;
        $ra_10  = $ra_9 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_9 . ':A' . $ra_10);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_9, "Business Unit Finance Manager");
        $excel->getActiveSheet()->getStyle('A' . $ra_9)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_9 . ':B' . $ra_10);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_9, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_9)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('C' . $ra_9 . ':C' . $ra_10);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_9, "");

        $ra_11  = $ra_10 + 1;
        $ra_12  = $ra_11 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_11 . ':A' . $ra_12);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_11, "Director");
        $excel->getActiveSheet()->getStyle('A' . $ra_11)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_11 . ':B' . $ra_12);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_11, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_11)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_11 . ':C' . $ra_12);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_11, "");

        $ra_13  = $ra_12 + 1;
        $ra_14  = $ra_13 + 2;

        $excel->getActiveSheet()->mergeCells('A' . $ra_13 . ':A' . $ra_14);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $ra_13, "Owner Head");
        $excel->getActiveSheet()->getStyle('A' . $ra_13)->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->mergeCells('B' . $ra_13 . ':B' . $ra_14);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $ra_13, "");
        $excel->getActiveSheet()->getStyle('B' . $ra_13)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C' . $ra_13 . ':C' . $ra_14);
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $ra_13, "");

        $excel->getActiveSheet()->getStyle('A' . $ra_1 . ':C' . $ra_14)->getAlignment()->setWrapText(true);

        for ($xy = $ra_1; $xy <= $ra_14; $xy++) {
            $excel->getActiveSheet()->getStyle('A' . $xy)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B' . $xy)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C' . $xy)->applyFromArray($style_col2);
        }

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Precalc Paket");
        $excel->setActiveSheetIndex(0);


        $writer = new Xlsx($excel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        $writer->save('php://output');
    }
}
