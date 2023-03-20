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
use App\Models\Sales\InvoiceModel;
use App\Models\Sales\InvoiceModelBarang;
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
use Carbon\Carbon;
use PDF;

class InvoiceController extends Controller
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

    public function invoice_costum($id)
    {
        $id   = request()->segment(4);
        $type = request()->segment(5);
        if ($type == 'lama') {
            $main = SalesMigrationModel::where('id', $id)->first();
            $wh   = WarehouseMigrateOut::where('id_quo', $id)->first();
        } else {
            $main = QuotationModel::where('id', $id)->first();
            $wh   = warehouse_out::where('id_quo', $id)->first();
        }
        $cust       = getCustomer($main->id_customer);
        $invoice    = QuotationInvoice::where('id_quo', $id)->first();
        $product    = QuotationProduct::where('id_quo', $id)->get();
        $history    = InvoiceModel::where('id_quo', $id)->get();
        $gethistory = count($history) == 0 ? $invoice : $history;
        $kondisi    = count($history) == 0 ? "normal" : "partial";
        $user       = EmployeeModel::all();
        // dd($invoice);
        return view('sales.invoice.invoice_custom', [
            'main'    => $main,
            'cust'    => $cust,
            'product' => $product,
            'wh'      => $wh,
            'invoice' => $invoice,
            'history' => $gethistory,
            'kondisi' => $kondisi,
            'user'    => $user,
            'dbs'     => $type,
            'method'  => "post",
            'action'  => 'Sales\InvoiceController@generate_invoice',
        ]);
    }

    public function invoice_tab(Request $request)
    {
        $product = QuotationProduct::where('id_quo', $request->quo)->get();
        $otherprice = QuotationOtherPrice::where('id_quo', $request->quo)->first();
        return view('sales.invoice.invoice_tabs', [
            'type'    => $request->jenis,
            'product' => $product,
            'otherprice' => $otherprice,
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
                $number       = "INVR/" . date("y") . "/" . integerToRoman($month_number) . "/" . $getmax;

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
                $number       = "INV/" . date("y") . "/" . integerToRoman($month_number) . "/" . $getmax;

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
                QuotationInvoice::create($datai);
                $kondisi  = 'normal';

                if ($request->jenis == 'partial') {
                    $kondisi  = 'partial';
                    $cinv     = QuotationInvoice::where('id_quo', $main->id)->first();
                    $countinv = InvoiceModel::where('id_quo', $main->id)->get();
                    $new_num  = count($countinv) == 0 ? $cinv->no_invoice : $cinv->no_invoice . '/' . (count($countinv) + 1);
                    if ($request->has('termin')) {
                        $number = $request->termin;
                    } elseif ($request->has('nominal')) {
                        $number = $request->nominal;
                    } else {
                        $number = null;
                    }
                    $partial  = [
                        'id_quo'         => $main->id,
                        'id_inv'         => $cinv->id,
                        'month'          => $cinv->month,
                        'month_id'       => $cinv->month_id,
                        'digit'          => $request->digit,
                        'type'           => $request->jenis,
                        'partial'        => $request->partial,
                        'number'         => $number,
                        'no_invoice'     => $new_num,
                        'tgl_invoice'    => $request->date,
                        'tgl_jatuhtempo' => $request->tempo,
                        'sign_by'        => $request->user,
                        'created_by'     => Auth::id(),
                        'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    InvoiceModel::insert($partial);
                    if ($request->has('pilih')) {
                        $pilih = $request->pilih;
                        $part   = InvoiceModel::where('id_inv', $cinv->id)->orderBy('id', 'desc')->first();
                        foreach ($pilih as $item => $v) {

                            $index = array_search($v, $request->id_quopro);

                            $sku         = getProductQuo($request->id_quopro[$index])->id_product;
                            $price_order = getProductQuo($request->id_quopro[$index])->det_quo_harga_order;
                            $data        = [
                                'id_quo'         => $main->id,
                                'id_inv'         => $cinv->id,
                                'id_inv_partial' => $part->id,
                                'sku'            => $sku,
                                'qty'            => $request->qty_invoice[$index],
                                'price'          => $price_order,
                                'created_by'     => Auth::id(),
                                'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                            ];
                            InvoiceModelBarang::insert($data);
                        }
                    }
                }

                $log = array(
                    'activity_id_quo'       => $main->id,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Invoice " . $kondisi . " sudah dicetak",
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
                    $number = "INV/" . date("y") . "/" . integerToRoman($month_number) . "/" . $getmax;
                }

                if ($request->jenis == 'partial') {
                    $kondisi  = 'partial';
                    $cinv     = QuotationInvoice::where('id_quo', $main->id)->first();
                    $countinv = InvoiceModel::where('id_quo', $main->id)->get();
                    $new_num  = count($countinv) == 0 ? $cinv->no_invoice : $cinv->no_invoice . '/' . (floatval(count($countinv) + 1));
                    if ($request->has('termin')) {
                        $harga = $request->termin;
                    } elseif ($request->has('nominal')) {
                        $harga = $request->nominal;
                    } else {
                        $harga = null;
                    }
                    $partial  = [
                        'id_quo'         => $main->id,
                        'id_inv'         => $cinv->id,
                        'month'          => $cinv->month,
                        'month_id'       => $cinv->month_id,
                        'digit'          => $request->digit,
                        'type'           => $request->jenis,
                        'partial'        => $request->partial,
                        'number'         => $harga,
                        'no_invoice'     => $new_num,
                        'tgl_invoice'    => $request->date,
                        'tgl_jatuhtempo' => $request->tempo,
                        'sign_by'        => $request->user,
                        'created_by'     => Auth::id(),
                        'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    InvoiceModel::insert($partial);
                    if ($request->has('pilih')) {
                        $pilih = $request->pilih;
                        $part   = InvoiceModel::where('id_inv', $cinv->id)->orderBy('id', 'desc')->first();
                        foreach ($pilih as $item => $v) {

                            $index = array_search($v, $request->id_quopro);

                            $sku         = getProductQuo($request->id_quopro[$index])->id_product;
                            $price_order = getProductQuo($request->id_quopro[$index])->det_quo_harga_order;
                            $data        = [
                                'id_quo'         => $main->id,
                                'id_inv'         => $cinv->id,
                                'id_inv_partial' => $part->id,
                                'sku'            => $sku,
                                'qty'            => $request->qty_invoice[$index],
                                'price'          => $price_order,
                                'created_by'     => Auth::id(),
                                'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                            ];
                            InvoiceModelBarang::insert($data);
                        }
                    }

                    $log = array(
                        'activity_id_quo'       => $main->id,
                        'activity_id_user'      => Auth::id(),
                        'activity_name'         => "Invoice partial baru sudah dicetak",
                        'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                    );
                    // dd($data2);
                    ActQuoModel::insert($log);
                } else {

                    $harga   = null;
                    $kondisi = 'normal';
                    $datai   = [
                        'month'          => $month_number,
                        'month_id'       => $getmax,
                        'no_invoice'     => $number,
                        'tgl_invoice'    => $request->date,
                        'tgl_jatuhtempo' => $request->tempo,
                        'sign_by'        => $request->user,
                        'digit'          => $request->digit,
                        'updated_by'     => Auth::id(),
                        'updated_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    // dd($datai);
                    QuotationInvoice::where('id_quo', $request->id)->update($datai);
                }
            }

            $id_khusus = array('242', '243');

            if (in_array($main->id, $id_khusus)) {

                $pdf = PDF::loadview('pdf.finance_invoice_khusus', [
                    'main'    => $main,
                    'cust'    => $cust,
                    'product' => $product,
                    'kondisi' => $kondisi,
                    'title'   => $title,
                    'number'  => $number,
                    'time'    => $time,
                    'inv'     => $check,
                    'digit'   => $request->digit,

                ]);
            } else {
                $wh  = warehouse_out::where('id_quo', $request->id)->first();
                $jenis = $kondisi == 'partial' ? $request->partial : '';
                $final_num = $kondisi == 'partial' ? $new_num : $number;
                $final_product = $request->has('pilih') ? InvoiceModelBarang::where('id_inv_partial', $part->id)->get() :  $product;
                $paper = $main->id == '952' ? 'specialcase.finance_no_do' : 'finance_invoice';
                $pdf   = PDF::loadview('pdf.' . $paper, [
                    'main'    => $main,
                    'cust'    => $cust,
                    'product' => $final_product,
                    'kondisi' => $kondisi,
                    'jenis'   => $jenis,
                    'title'   => $title,
                    'number'  => $final_num,
                    'harga'   => $harga,
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

    public function invoice_cetak(Request $request)
    {
        if ($request->kondisi == "partial") {
            $part   = InvoiceModel::where('id', $request->idinv)->first();
        } else {
            $part   = QuotationInvoice::where('id', $request->idinv)->first();
        }
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $part->id_quo)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();
        $cust    = CustomerModel::where('id', $main->id_customer)->first();

        if (isset($part->partial)) {
            if ($part->partial == "qty") {
                $product = InvoiceModelBarang::where([
                    ['id_quo', $part->id_quo],
                    ['id_inv_partial', $part->id]
                ])->get();
            } else {
                $product = QuotationProduct::where('id_quo', $part->id_quo)->get();
            }
        } else {
            $product = QuotationProduct::where('id_quo', $part->id_quo)->get();
        }
        // dd($product);

        $title   = sprintf("%06d", $main->id);
        $donum   = warehouse_out::where('id_quo', $main->id)->first();

        $jenis = $request->kondisi == 'partial' ? $request->partial : '';

        $wh  = warehouse_out::where('id_quo', $main->id)->first();
        $paper = $main->id == '952' ? 'specialcase.finance_no_do' : 'finance_invoice';
        $pdf   = PDF::loadview('pdf.' . $paper, [
            'main'    => $main,
            'cust'    => $cust,
            'product' => $product,
            'kondisi' => $request->kondisi,
            'jenis'   => $jenis,
            'title'   => $title,
            'number'  => $part->no_invoice,
            'do'      => 'WH/OUT/' . date('y', strtotime($wh->created_at)) . '/' . $donum->id,
            'time'    => date("d F Y", strtotime($part->tgl_invoice)),
            'inv'     => $part,
            'digit'   => $part->digit,
            'tempo'   => $part->tgl_jatuhtempo == null ? "-" : date("d F Y", strtotime($part->tgl_jatuhtempo))

        ]);
        return $pdf->download('MEG - INVOICE' . $title . '.pdf');
    }

    public function invoice_edit(Request $request)
    {
        if ($request->kondisi == "partial") {
            $part   = InvoiceModel::where('id', $request->idinv)->first();
        } else {
            $part   = QuotationInvoice::where('id', $request->idinv)->first();
        }
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $part->id_quo)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();

        if (isset($part->partial)) {
            if ($part->partial == "qty") {
                $views = "invoice_edit_qty";
            } else {
                $views = "invoice_edit_part";
            }
        } else {

            $views   = "invoice_edit_normal";
        }


        $user       = EmployeeModel::all();
        $otherprice = QuotationOtherPrice::where('id_quo', $part->id_quo)->first();
        $product    = QuotationProduct::where('id_quo', $part->id_quo)->get();
        return view('sales.invoice.' . $views, [
            'dbs'        => $request->kondisi,
            'method'     => "post",
            'action'     => 'Sales\InvoiceController@invoice_update',
            'main'       => $main,
            'part'       => $part,
            'user'       => $user,
            'product'    => $product,
            'kondisi'    => $request->kondisi,
            'otherprice' => $otherprice,
        ]);
    }

    public function invoice_update(Request $request)
    {
        if ($request->dbs == "partial") {
            $check = InvoiceModel::where('id', $request->idinv)->first();
            $idinv = $check->id_inv;
        } else {
            $check = QuotationInvoice::where('id', $request->idinv)->first();
            $idinv = $check->id;
        }
        // === handle partial barang === //

        if ($request->has('pilih')) {
            InvoiceModelBarang::where('id_inv_partial', $request->idinv)->delete();

            $pilih = $request->pilih;
            foreach ($pilih as $item => $v) {

                $index       = array_search($v, $request->id_quopro);
                $sku         = getProductQuo($request->id_quopro[$index])->id_product;
                $price_order = getProductQuo($request->id_quopro[$index])->det_quo_harga_order;

                $data        = [
                    'id_quo'         => $request->id,
                    'id_inv'         => $idinv,
                    'id_inv_partial' => $request->idinv,
                    'sku'            => $sku,
                    'qty'            => $request->qty_invoice[$index],
                    'price'          => $price_order,
                    'created_by'     => Auth::id(),
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                InvoiceModelBarang::insert($data);
            }
            $part_ongkir = $request->ongkir_type == "ongkir_input" ? $request->ongkir : QuotationOtherPrice::where('id_quo', $request->id)->first()->ongkir_customer;
        }
        // === end handle partial barang === //

        // == handle main invoice == //
        $month_number = date('m', strtotime($request->date));
        $checkmax     = QuotationInvoice::where('month', $month_number)->max('month_id');

        if ($month_number == $check->month) {
            $number = $check->no_invoice;
            $getmax = $check->month_id;
        } else {
            $getmax    = $checkmax == null ? 1 : $checkmax + 1;
            $newnumber = $checkmax == null ? 1 : $getmax;
            $newhead   = $check->type == 'lama' ? "INVR/" : "INV/";
            $number    = $newhead . date("y") . "/" . integerToRoman($month_number) . "/" . $newnumber;
        }
        $datai        = [
            'month'          => $month_number,
            'month_id'       => $getmax,
            'no_invoice'     => $number,
            'tgl_invoice'    => $request->date,
            'tgl_jatuhtempo' => $request->tempo,
            'sign_by'        => $request->user,
            'digit'          => $request->digit,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        QuotationInvoice::where('id_quo', $request->id)->update($datai);

        // == end handle main invoice == //

        if ($request->has('termin')) {
            $harga       = $request->termin;
            $part_ongkir = null;
        } elseif ($request->has('nominal')) {
            $harga       = $request->nominal;
            $part_ongkir = null;
        } else {
            $harga       = null;
            $part_ongkir = $request->ongkir_type == "ongkir_input" ? $request->ongkir : QuotationOtherPrice::where('id_quo', $request->id)->first()->ongkir_customer;
        }

        $partial  = [
            'id_quo'         => $request->id,
            'id_inv'         => $idinv,
            'month'          => $month_number,
            'month_id'       => $getmax,
            'digit'          => $request->digit,
            'type'           => $request->jenis,
            'partial'        => $check->partial,
            'number'         => $harga,
            'ongkir'         => $request->has('ongkos_semua') == null ? null : $part_ongkir,
            'no_invoice'     => $number,
            'tgl_invoice'    => $request->date,
            'tgl_jatuhtempo' => $request->tempo,
            'sign_by'        => $request->user,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        InvoiceModel::where('id', $request->idinv)->update($partial);



        // === log activity === // 

        if ($request->date <> $check->tgl_invoice) {
            if ($month_number == $check->month) {
                $message = "Merubah tanggal Invoice";
            } else {
                $message = "Merubah tanggal dan nomer Invoice";
            }
        } else if ($request->tempo <> $check->tgl_jatuhtempo) {
            $message = "Merubah tanggal Due Date Invoice";
        } else if ($request->user <> $check->sign_by) {
            $message = "Merubah Penandatangan Invoice";
        } else if ($request->digit <> $check->digit) {
            $message = "Merubah Digit Pembulatan Invoice";
        } else if ($request->digit <> $check->digit) {
            $message = "Merubah Digit Pembulatan Invoice";
        } else {
            $message = "Merubah partial invoice";
        }

        $log  = array(
            'activity_id_quo'       => $request->id,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $message . ' ' . $number,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        // === end log activity === //

        return redirect("sales/download/invoice_costum/" . $request->id . '/baru')->with('success', $message);
    }

    public function invoice_delete(Request $request)
    {
        if ($request->kondisi == 'partial') {
            $main = InvoiceModel::where('id', $request->idinv)->first();
        } else {
            $main = QuotationInvoice::where('id', $request->idinv)->first();
        }

        return view('sales.invoice.invoice_delete', [
            'kondisi' => $request->kondisi,
            'main'    => $main,
            'method'  => "post",
            'action'  => 'Sales\InvoiceController@invoice_delete_exec',
        ]);
    }

    public function invoice_delete_exec(Request $request)
    {
        if ($request->kondisi == 'partial') {
            $main  = InvoiceModel::where('id', $request->idinv)->first();
            $split = InvoiceModel::find($request->idinv);
        } else {
            $main  = QuotationInvoice::where('id', $request->idinv)->first();
            $split = QuotationInvoice::find($request->idinv);
        }
        $message = "Invoice " . ucfirst($request->kondisi) . ' ' . $main->no_invoice . " telah dihapus karena " . $request->delete_note;
        $log     = array(
            'activity_id_quo'       => $request->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $message,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        $split->delete();

        return redirect("sales/download/invoice_costum/" . $request->id_quo . '/baru')->with('success', $message);
    }

    public function proforma_invoice($id)
    {
        $id         = request()->segment(4);
        $type       = request()->segment(5);
        $main       = QuotationModel::where('id', $id)->first();
        $cust       = getCustomer($main->id_customer);
        $invoice    = QuotationInvoice::where('id_quo', $id)->first();
        $product    = QuotationProduct::where('id_quo', $id)->get();
        $history    = InvoiceModel::where('id_quo', $id)->get();
        $gethistory = count($history) == 0 ? $invoice : $history;
        $kondisi    = count($history) == 0 ? "normal" : "partial";
        $user       = EmployeeModel::all();
        // dd($invoice);
        return view('sales.invoice.invoice_custom_proforma', [
            'main'    => $main,
            'cust'    => $cust,
            'product' => $product,
            'invoice' => $invoice,
            'history' => $gethistory,
            'kondisi' => $kondisi,
            'user'    => $user,
            'dbs'     => $type,
            'method'  => "post",
            'action'  => 'Sales\InvoiceController@proforma',
        ]);
    }

    public function proforma(Request $request)
    {
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
            $number       = "PRO-INV/" . date("y") . "/" . integerToRoman($month_number) . "/" . $getmax;

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
            QuotationInvoice::create($datai);
            $kondisi  = 'normal';

            if ($request->jenis == 'partial') {
                $kondisi  = 'partial';
                $cinv     = QuotationInvoice::where('id_quo', $main->id)->first();
                $countinv = InvoiceModel::where('id_quo', $main->id)->get();
                $new_num  = count($countinv) == 0 ? $cinv->no_invoice : $cinv->no_invoice . '/' . (count($countinv) + 1);
                if ($request->has('termin')) {
                    $number = $request->termin;
                } elseif ($request->has('nominal')) {
                    $number = $request->nominal;
                } else {
                    $number = null;
                }
                $partial  = [
                    'id_quo'         => $main->id,
                    'id_inv'         => $cinv->id,
                    'month'          => $cinv->month,
                    'month_id'       => $cinv->month_id,
                    'digit'          => $request->digit,
                    'type'           => $request->jenis,
                    'partial'        => $request->partial,
                    'number'         => $number,
                    'no_invoice'     => $new_num,
                    'tgl_invoice'    => $request->date,
                    'tgl_jatuhtempo' => $request->tempo,
                    'sign_by'        => $request->user,
                    'created_by'     => Auth::id(),
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                InvoiceModel::insert($partial);
                if ($request->has('pilih')) {
                    $pilih = $request->pilih;
                    $part   = InvoiceModel::where('id_inv', $cinv->id)->orderBy('id', 'desc')->first();
                    foreach ($pilih as $item => $v) {

                        $index = array_search($v, $request->id_quopro);

                        $sku         = getProductQuo($request->id_quopro[$index])->id_product;
                        $price_order = getProductQuo($request->id_quopro[$index])->det_quo_harga_order;
                        $data        = [
                            'id_quo'         => $main->id,
                            'id_inv'         => $cinv->id,
                            'id_inv_partial' => $part->id,
                            'sku'            => $sku,
                            'qty'            => $request->qty_invoice[$index],
                            'price'          => $price_order,
                            'created_by'     => Auth::id(),
                            'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        InvoiceModelBarang::insert($data);
                    }
                }
            } else {
                $cinv     = QuotationInvoice::where('id_quo', $main->id)->first();
                $partial  = [
                    'id_quo'         => $main->id,
                    'id_inv'         => $cinv->id,
                    'month'          => $cinv->month,
                    'month_id'       => $cinv->month_id,
                    'digit'          => $request->digit,
                    'type'           => 'proforma',
                    'partial'        => 'proforma',
                    'number'         => null,
                    'no_invoice'     => $number,
                    'tgl_invoice'    => $request->date,
                    'tgl_jatuhtempo' => $request->tempo,
                    'sign_by'        => $request->user,
                    'created_by'     => Auth::id(),
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                InvoiceModel::insert($partial);
            }

            $log = array(
                'activity_id_quo'       => $main->id,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Proforma Invoice " .  $number . " " . $kondisi . " sudah dicetak",
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
                $number = "PRO-INV/" . date("y") . "/" . integerToRoman($month_number) . "/" . $getmax;
            }

            if ($request->jenis == 'partial') {
                $kondisi  = 'partial';
                $cinv     = QuotationInvoice::where('id_quo', $main->id)->first();
                $countinv = InvoiceModel::where('id_quo', $main->id)->get();
                $new_num  = count($countinv) == 0 ? $cinv->no_invoice : $cinv->no_invoice . '/' . (floatval(count($countinv) + 1));
                if ($request->has('termin')) {
                    $harga = $request->termin;
                } elseif ($request->has('nominal')) {
                    $harga = $request->nominal;
                } else {
                    $harga = null;
                }
                $partial  = [
                    'id_quo'         => $main->id,
                    'id_inv'         => $cinv->id,
                    'month'          => $cinv->month,
                    'month_id'       => $cinv->month_id,
                    'digit'          => $request->digit,
                    'type'           => $request->jenis,
                    'partial'        => $request->partial,
                    'number'         => $harga,
                    'no_invoice'     => $new_num,
                    'tgl_invoice'    => $request->date,
                    'tgl_jatuhtempo' => $request->tempo,
                    'sign_by'        => $request->user,
                    'created_by'     => Auth::id(),
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                InvoiceModel::insert($partial);
                if ($request->has('pilih')) {
                    $pilih = $request->pilih;
                    $part   = InvoiceModel::where('id_inv', $cinv->id)->orderBy('id', 'desc')->first();
                    foreach ($pilih as $item => $v) {

                        $index = array_search($v, $request->id_quopro);

                        $sku         = getProductQuo($request->id_quopro[$index])->id_product;
                        $price_order = getProductQuo($request->id_quopro[$index])->det_quo_harga_order;
                        $data        = [
                            'id_quo'         => $main->id,
                            'id_inv'         => $cinv->id,
                            'id_inv_partial' => $part->id,
                            'sku'            => $sku,
                            'qty'            => $request->qty_invoice[$index],
                            'price'          => $price_order,
                            'created_by'     => Auth::id(),
                            'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                        ];
                        InvoiceModelBarang::insert($data);
                    }
                }

                $log = array(
                    'activity_id_quo'       => $main->id,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => "Invoice partial baru sudah dicetak",
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                // dd($data2);
                ActQuoModel::insert($log);
            } else {

                $kondisi = 'normal';
                $harga   = null;
                $datai   = [
                    'month'          => $month_number,
                    'month_id'       => $getmax,
                    'no_invoice'     => $number,
                    'tgl_invoice'    => $request->date,
                    'tgl_jatuhtempo' => $request->tempo,
                    'sign_by'        => $request->user,
                    'digit'          => $request->digit,
                    'updated_by'     => Auth::id(),
                    'updated_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                // dd($datai);
                QuotationInvoice::where('id_quo', $request->id)->update($datai);
            }
        }

        $wh  = warehouse_out::where('id_quo', $request->id)->first();
        $jenis = $kondisi == 'partial' ? $request->partial : '';
        $final_num = $kondisi == 'partial' ? $new_num : $number;
        $pdf = PDF::loadview('pdf.finance_proformainvoice', [
            'main'    => $main,
            'cust'    => $cust,
            'product' => $product,
            'kondisi' => $kondisi,
            'jenis'   => $jenis,
            'title'   => $title,
            'number'  => $final_num,
            'harga'   => $harga,
            'time'    => $time,
            'inv'     => $check,
            'digit'   => $request->digit,
            'tempo'   => date("d F Y", strtotime($request->tempo))

        ]);


        return $pdf->download('MEG - PROINVOICE' . $title . '.pdf');
    }
}
