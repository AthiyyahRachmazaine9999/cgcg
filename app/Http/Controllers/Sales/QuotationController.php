<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationReplacement;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationInvoiceDetail;
use App\Models\Sales\QuotationInvoicePayment;
use App\Models\Sales\QuotationInvoiceOthers;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\Customer_pic;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use App\Models\Activity\ActQuoModel;
use App\Models\Activity\ActPurchaseModel;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_model;
use App\Models\Inventory\InventoryModel;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\Warehouse_address;
use App\Models\WarehouseUpdate\WarehouseIn;
use App\Models\WarehouseUpdate\WarehouseInDetail;
use App\Models\WarehouseUpdate\WarehouseOutDetail;
use App\Models\WarehouseUpdate\WarehouseOut;
use App\Models\WarehouseUpdate\WarehouseDoc;
use Illuminate\Http\Request;
use App\Models\Sales\VisitModel;
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
use DB;

use function PHPUnit\Framework\isNull;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.quotation.index', [
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
    public function create($id=null)
    {
        $visit    = $id !=null ? VisitModel::where('id', $id)->first() : null;
        $is_admin = getEmp(Auth::id())->division_id = '10' ? [getEmp(Auth::id())->id => getEmp(Auth::id())->emp_name] : null;
        $is_sales = getEmp(Auth::id())->division_id = '9' ? [getEmp(Auth::id())->id => getEmp(Auth::id())->emp_name] : null;
        return view('sales.quotation.create', [
            'quo_type' => $this->get_quoType(),
            'id_admin' => getEmpSelect('division_id', '10'),
            'is_admin' => $is_admin,
            'id_sales' => getEmpSelect('division_id', '9'),
            'is_sales' => $is_sales,
            'visit'    => $visit,
            'method'   => "post",
            'action'   => 'Sales\QuotationController@store',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->save($request, 'created');
    }
    

    public function save($request, $save, $id = 0)
    {
        // dd($request);
        $quotype = $request->input('quo_type') == '1' ? "Opti Project" : "Order";
        $quo_name = $request->input('quo_name');
        $quo_no = $request->input('quo_no');
        $data = [
            'id_customer'  => $request->input('id_customer'),
            'id_admin'     => $request->input('id_admin'),
            'id_sales'     => $request->input('id_sales'),
            'quo_type'     => $request->input('quo_type'),
            'quo_no'       => $request->input('quo_no'),
            'quo_name'     => $request->input('quo_name'),
            'quo_order_at' => $request->input('quo_order_at'),
            'quo_price'    => $request->input('quo_price'),
            $save . '_by'       => Auth::id()
        ];

        $checkact = $save == 'created' ? '' : $this->checkperubahan($request, $id);
        // dd($data);
        $qry = $save == 'created' ? QuotationModel::create($data) : QuotationModel::where('id', $id)->update($data);
        if ($qry) {
            if ($save == 'created') {
                $ndata  = [
                    'id_quo'     => $qry->id,
                    $save . '_by'      => Auth::id(),
                    'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
                ];
                QuotationDocument::insert($ndata);
                QuotationOtherPrice::insert($ndata);

                $sku = $request->input('sku');
                foreach ($sku as $item => $v) {

                    $reqku[$item] = $request->sku[$item] == "new" ? $request->newsku[$item] : '';
                    if ($request->sku[$item] == "new") {
                        $datareq = array(
                            'req_product' => $reqku[$item],
                            'req_price'   => $request->price[$item],
                            'created_by'  => Auth::id()
                        );
                        // dd($data2);
                        $req = ProductReq::create($datareq);
                    }
                    $check = $request->sku[$item] == "new" ? "det_quo_harga_req" : "det_quo_harga_order";
                    $finalsku[$item] = $request->sku[$item] == "new" ? $req->id : null;
                    $data2 = array(
                        'id_quo'             => $qry->id,
                        'id_product'         => $request->sku[$item],
                        'id_product_request' => $finalsku[$item],
                        'det_quo_qty'        => $request->qty[$item],
                        $check               => $request->price[$item],
                        $save . '_by'              => Auth::id()
                    );
                    // dd($data2);
                    $idpro = QuotationProduct::create($data2);

                    $data3 = array(
                        'id_quo'           => $qry->id,
                        'id_quo_pro'       => $idpro->id,
                        'id_product'       => $request->sku[$item],
                        'det_quo_qty_beli' => $request->qty[$item],
                        $save . '_by'            => Auth::id()
                    );
                    $newpur = Purchase_model::insert($data3);

                    if ($request->input('quo_type') > 1) {

                        $cstock = StockCheck($request->sku[$item], $qry->id);
                        if ($cstock['condition'] == 'yes') {
                            $datastock = [
                                'det_quo_harga_modal' => $cstock['price'],
                            ];
                            QuotationProduct::where('id', $idpro->id)->update($datastock);
                        }
                    }
                }
                $log = array(
                    'activity_id_quo'       => $qry->id,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => 'Membuat ' . $quotype . ' ' . $quo_no . ' dengan Nama ' . $quo_name,
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                // dd($log);
                ActQuoModel::insert($log);
                $redto = 'sales/quotation/' . $qry->id;
            } else {
                $redto = 'sales/quotation/' . $id;
                $log = array(
                    'activity_id_quo'       => $id,
                    'activity_id_user'      => Auth::id(),
                    'activity_name'         => 'Merubah info utama berupa ' . $checkact,
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                );
                // dd($log);
                ActQuoModel::insert($log);
            }



            return redirect($redto)->with('success', ucwords($request->input('company')) . 'Quotation ' . $save . ' successfully');
        }
    }

    public function checkperubahan($request, $id)
    {
        $main  = QuotationModel::where('id', $id)->first();
        $cust  = $request->input('id_customer') <> $main->id_customer ? "Merubah Costumer" : " ";
        $adm   = $request->input('id_admin') <> $main->id_admin ? "Merubah Admin" : " ";
        $sales = $request->input('id_sales') <> $main->id_sales ? "Merubah Sales" : " ";
        $type  = $request->input('quo_type') <> $main->quo_type ? "Merubah Type Order dari " . getQuoType($main->quo_type)->type_name . " Menjadi " . getQuoType($request->input('quo_type'))->type_name : " ";
        $no    = $request->input('quo_no') <> $main->quo_no ? "Merubah Nomer Order dari " . $main->quo_no . " Menjadi " . $request->input('quo_no') . "," : " ";
        $name  = $request->input('quo_name') <> $main->quo_name ? "Merubah Nama Paket dari " . $main->quo_name . " Menjadi " . $request->input('quo_name') . "," : " ";
        $price = $request->input('quo_price') <> $main->quo_price ? "Merubah Harga Order dari " . number_format($main->quo_price) . " Menjadi " . number_format($request->input('quo_price')) : " ";

        return $cust . $adm . $sales . $type . $no . $name . $price;
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
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $id)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();
        $cust     = CustomerModel::where('id', $main->id_customer)->first();
        $product  = QuotationProduct::where('id_quo', $id)->get();
        $document = QuotationDocument::where('id_quo', $id)->first();
        $invoice  = QuotationInvoice::where('id_quo', $id)->first();
        $price    = QuotationOtherPrice::where('id_quo', $id)->first();
        $check    = QuotationProduct::where('id_quo', $id)->where('id_product', '=', 'new')->first();
        $act      = ActQuoModel::where('activity_id_quo', $id)
            ->orderby('activity_id', 'desc')->limit(2)->get();
        $wo       = Warehouse_address::where('id_quo', $id)->get();
        $purchase = QuotationProduct::where('quotation_product.id_quo', $id)
            ->join('quotation_purchase as q', 'q.id_quo_pro', '=', 'quotation_product.id')
            ->get();
            
        $filedo = WarehouseDoc::where('o.id_quo', $id)
        ->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_document.id_outbound')
        ->first();
        // dd($purchase);
        if ($main == null) {
            $val = "document";
        } else {
            $val = "document_edit";
        }
        return view('sales.quotation.show', [
            'check'    => $check,
            'val'      => $val,
            'wo'       => $wo,
            'main'     => $main,
            'act'      => $act,
            'product'  => $product,
            'purchase' => $purchase,
            'price'    => $price,
            'document' => $document,
            'invoice'  => $invoice,
            'cust'     => $cust,
            'filedo'   => $filedo,
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
        $main     = QuotationModel::where('id', $id)->first();
        $stype    = Quo_TypeModel::where('id', $main->quo_type)->first();
        $cust     = CustomerModel::where('id', $main->id_customer)->first();
        $product  = QuotationProduct::where('id_quo', $id)->get();
        return view('sales.quotation.edit', [
            'main'     => $main,
            'cust'     => [$cust->id => $cust->company],
            'is_type'  => $stype->id,
            'quo_type' => $this->get_quoType(),
            'id_admin' => getEmpSelect('division_id', '10'),
            'is_admin' => $main->id_admin,
            'id_sales' => getEmpSelect('division_id', '9'),
            'is_sales' => $main->id_sales,
            'method'   => "put",
            'product'  => $product,
            'action'   => ['Sales\QuotationController@update', $id],
        ]);
    }

    // ==== Add Or Edit Customer ======== //
    // =========================== //

    public function address_add(Request $request)
    {
        // dd($request);
        $ccomp = CustomerModel::where('id', $request->id)->first();
        $quo   = QuotationModel::where('id', $request->idquo)->first();
        $type  = $request->type;
        if ($request->type == "tambah") {
            return view('sales.quotation.attribute.customer_add', [
                'type'   => $type,
                'quo'    => $quo,
                'ccomp'  => $ccomp,
                'method' => "post",
                'action' => 'Sales\QuotationController@address_add_Save',
            ]);
        } else if ($type == "edit") {
            $quo = QuotationModel::where('id', $request->idquo)->first();
            $wo  = Warehouse_address::where('id', $request->id)->first();
            return view('sales.quotation.attribute.customer_edit', [
                'type'   => $type,
                'quo'    => $quo,
                'wo'     => $wo,
                'ccomp'  => $ccomp,
            ]);
        } else if ($type == "tambah_pic") {
            $pic = Customer_pic::where('id_customer', $request->id)->get();
            $quo = QuotationModel::where('id', $request->idquo)->first();
            // dd($pic);
            return view('sales.quotation.attribute.customer_addpic', [
                'type'   => $type,
                'quo'    => $quo,
                'pic'    => $pic,
                'ccomp'  => $ccomp,
                'method' => "post",
                'action' => 'Sales\QuotationController@address_add_Save',
            ]);
        } else if ($type == "edit_pic") {
            $fpic = Customer_pic::where('id', $request->idpic)->first();
            $pic  = Customer_pic::where('id_customer', $request->id)->get();
            $quo  = QuotationModel::where('id', $request->idquo)->first();
            // dd($pic);
            return view('sales.quotation.attribute.customer_editpic', [
                'type'   => $type,
                'fpic'   => $fpic,
                'quo'    => $quo,
                'pic'    => $pic,
                'ccomp'  => $ccomp,
            ]);
        }
    }

    public function address_add_Save(Request $request)
    {
        // dd($request);
        $pic = Customer_pic::where('id_customer', $request->idcust)->get();
        if ($request->type == "tambah_pic") {
            $pic_data = [
                "address_pic" => $request->address,
            ];
            $uppic = Customer_pic::where('id', $request->id)->update($pic_data);
        } else {
            $data = [
                'id_wo'      => $request->id_quo,
                'id_quo'     => $request->id_quo,
                'name'       => $request->nama_pic,
                'address'    => $request->address,
                'created_by' => Auth::id(),
            ];

            $wr_order = [
                'pengiriman' => 'multiple',
            ];
            $or = Warehouse_order::where('id_quo', $request->id_quo)->update($wr_order);
            $cr = Warehouse_address::create($data);
        }
        return redirect('sales/quotation/' . $request->id_quo)->with('success');
    }

    public function address_add_Update(Request $request)
    {
        if ($request->type == "edit_pic") {
            $ed_pic = [
                'address_pic' => $request->address,
            ];
            $ed = Customer_pic::where('id', $request->id)->update($ed_pic);
        } else {
            $data = [
                'name'    => $request->name_pic,
                'address' => $request->address,
                'updated_by' => Auth::id(),
            ];
            $cr = Warehouse_address::where('id', $request->id_wo)->update($data);
        }
        return redirect('sales/quotation/' . $request->id_quo)->with('success');
    }

    public function remove_addwo($id, $request, $type)
    {
        if ($type == "remove") {
            $wh_address = Warehouse_address::find($id);
            $wh_address->delete();
        }
        return redirect('sales/quotation/' . $request)->with('Success');
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
        return $this->save($request, 'updated', $id);
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

    // ==== other function ======== //
    // =========================== //
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

    public function activity(Request $request)
    {
        // dd($request);
        $main = ActQuoModel::where('activity_id_quo', $request->quo)
            ->orderby('activity_id', 'desc')->get();
        return view('sales.quotation.attribute.actall', [
            'act'    => $main,
            'method' => "post",
            'quo'    => $request->quo,
        ]);
    }

    public function activity_new(Request $request)
    {
        // dd($request);
        $main = ActQuoModel::where('activity_id_quo', $request->quo)
            ->orderby('activity_id', 'desc')->get();
        return view('sales.quotation.attribute.actform', [
            'method'   => "post",
            'quo'      => $request->quo,
            'action'   => 'Sales\QuotationController@activity_save',
        ]);
    }

    public function activity_save(Request $request)
    {
        // dd($request);
        $id_quo   = $request->id_quo;
        $log = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $request->activity_name,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return redirect("sales/quotation/" . $request->id_quo)->with('success', ucwords($request->input('company')) . 'Update Status successfully');
    }


    public function edit_status(Request $request)
    {
        // dd($request);
        $where = "status_type";
        $main = QuotationModel::where('id', $request->quo)->first();
        return view('sales.quotation.attribute.modal_status', [
            'cstatus'  => [$main->quo_eksstatus => $main->quo_eksstatus],
            'cposisi'  => [$main->quo_eksposisi => $main->quo_eksposisi],
            'ckondisi' => [$main->quo_ekskondisi => $main->quo_ekskondisi],
            'status'   => $this->get_status($where, "status"),
            'posisi'   => $this->get_status($where, "posisi"),
            'kondisi'  => $this->get_status($where, "kondisi"),
            'method'   => "post",
            'quo'      => $request->quo,
            'action'   => 'Sales\QuotationController@save_status',
        ]);
    }

    public function save_status(Request $request)
    {
        // dd($request);
        $id_quo   = $request->id_quo;
        $wo_out   = WarehouseOut::where('id_quo', $request->id_quo)->first();
        $getquo   = getQuo($id_quo);
        $cstatus  = $getquo->quo_eksstatus == '' || $getquo->quo_eksstatus == null ? "No data" : $getquo->quo_eksstatus;
        $cposisi  = $getquo->quo_eksposisi == '' || $getquo->quo_eksposisi == null ? "No data" : $getquo->quo_eksposisi;
        $ckondisi = $getquo->quo_ekskondisi == '' || $getquo->quo_ekskondisi == null ? "No data" : $getquo->quo_ekskondisi;
        $data2 = [
            'quo_eksstatus'   => $request->quo_eksstatus,
            'quo_eksposisi'   => $request->quo_eksposisi,
            'quo_ekskondisi'  => $request->quo_ekskondisi,
            'quo_ekslastnote' => $request->quo_notestatus,
            'updated_by'      => Auth::id(),
            'updated_at'      => Carbon::now('GMT+7')->toDateTimeString()
        ];


        if($request->quo_ekskondisi == "Batal" && $wo_out!=null)
        {
            $delete = WarehouseOut::where('id_quo', $request->id_quo)->first();
            $delete->delete();
        }



        $log = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => 'Merubah Status ' . $cstatus . " => " . $request->quo_eksstatus . ", 
            Posisi " . $cposisi . " => " . $request->quo_eksposisi . ",
            Kondisi " . $ckondisi . " => " . $request->quo_ekskondisi . ",
            Dengan Catatan : " . $request->quo_notestatus,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        QuotationModel::where('id', $id_quo)->update($data2);
        return redirect("sales/quotation/" . $request->id_quo)->with('success', ucwords($request->input('company')) . 'Update Status berhasil');
    }

    public function document(Request $request)
    {
        // dd($request);
        $quo  = QuotationModel::where('id', $request->quo)->first();
        $main = QuotationDocument::where('id_quo', $request->quo)->first();
        // dd($main);
        $create = $main == null ? "" : $main;
        $view   = $main == null ? "document" : "document_edit";
        // dd($view);
        return view('sales.quotation.attribute.' . $view, [
            'view'   => $view,
            'utama'  => $quo,
            'main'   => $create,
            'quo'    => $request->quo,
            'method' => "post",
            'action' => 'Sales\QuotationController@document_save',
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
        QuotationModel::where('id', $request->id_quo)->update($waktu_pelaksanaan);

        $log = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Menambahkan data dokumen",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($data2);
        ActQuoModel::insert($log);
        $type   = $request->type == "update" ? QuotationDocument::where('id_quo', $id_quo)->update($data2) : QuotationDocument::insert($data2);
        return redirect()->back()->with('success', ucwords($request->input('company')) . 'Update Data Document berhasil');
    }


    public function document_upload(Request $request)
    {
        // $main   = QuotationDocument::where('id_quo', $request->quo)->first();
        return view('sales.quotation.attribute.document_upload', [
            'method'           => "post",
            'quo'              => $request->quo,
            'type'             => $request->type,
            'action' => 'Sales\QuotationController@saveFile',
        ]);
    }



    public function saveFile(Request $request, $id = 0)
    {
        $file = $request->file('file');
        $folder = 'public/documents';
        $path = Storage::putfile($folder, $file);

        if ($request->type == "sp") {
            // dd($path);
            $data = [
                'id_quo' => $request->id_quo,
                'doc_sp' => $path,
            ];
        } elseif ($request->type == "po") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_po' => $path,
            ];
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
        $qry = QuotationDocument::where('id_quo', $request->id_quo)->update($data);
        return response()->json("success");
    }


    public function classic_upload(Request $request)
    {
        return view('sales.quotation.attribute.classic_upload', [
            'method'           => "post",
            'quo'              => $request->quo,
            'type'             => $request->type,
            'action' => 'Sales\QuotationController@saveFileClassic',
        ]);
    }

    public function saveFileClassic(Request $request)
    {
        // dd($request);
        $file = $request->file('file');
        $folder = 'public/documents';
        $path = Storage::putfile($folder, $file);

        if ($request->type == "sp") {
            // dd($path);
            $data = [
                'id_quo' => $request->id_quo,
                'doc_sp' => $path,
            ];
        } elseif ($request->type == "po") {
            $data = [
                'id_quo' => $request->id_quo,
                'doc_po' => $path,
            ];
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
        $qry = QuotationDocument::where('id_quo', $request->id_quo)->update($data);
        return response()->json("success");
    }


    public function product_clone(Request $request)
    {

        return view('sales.quotation.attribute.product-clone', [
            'n_equ'  => $request->input('n_equ'),
            'id_quo' => $request->id_quo,
        ]);
    }

    public function product_clone_change(Request $request)
    {
        // dd($request);
        return view('sales.quotation.attribute.product-clone-change', [
            'id_quo' => $request->id_quo,
            'idpro'  => $request->idpro,
            'n_equ'  => $request->idpro,
        ]);
    }

    public function save_changeproduct(Request $request)
    {
        $newsku = $request->idpro_new;
        $idpro  = $request->idpro;
        $id_quo = $request->id_quo;

        $check = QuotationProduct::where('id', $request->idpro)->first();

        // save to new table 
        $datch = [
            'id_quo'              => $request->id_quo,
            'id_detail_product'   => $request->idpro,
            'id_product'          => $check->id_product,
            'det_quo_qty'         => $check->det_quo_qty,
            'det_quo_harga_order' => $check->det_quo_harga_order,
            'note'                => $request->note,
            'created_by'          => Auth::id(),
            'created_at'          => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $brg_awal = $check->id_product == "new" ? getProductReq($check->id_product_request)->req_product : getProductDetail($check->id_product)->name;

        $activity = "Merubah Barang dari " . $brg_awal;
        $qry      = QuotationReplacement::create($datch);
        if ($qry) {

            $datapr = [
                'id_product_replace'    => $qry->id,
                'id_quo'                => $request->id_quo,
                'id_product'            => $newsku,
                'det_quo_qty'           => $request->p_qty_new,
                'det_quo_harga_order'   => $request->p_price_new,
                'updated_by'            => Auth::id(),
                'updated_at'            => Carbon::now('GMT+7')->toDateTimeString()
            ];
            QuotationProduct::where('id', $idpro)->update($datapr);

            if ($request->idpro_new == "new") {
                $datareq = array(
                    'req_product' => $request->newsku,
                    'req_price'   => $request->p_price_new,
                    'updated_by'  => Auth::id()
                );
                // dd($data2);
                ProductReq::where('id', getProductReq($check->id_product_request)->id)->update($datareq);
            }
            $brg_akhir = $newsku == "new" ? getProductReq($newsku)->req_product : getProductDetail($newsku)->name;
            $comment = $request->note == null ? 'tanpa alasan' : $request->note;
            $log  = array(
                'activity_id_quo'       => $id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => $activity . " menjadi " . $brg_akhir . " " . $comment,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($log);
            ActQuoModel::insert($log);
        }
        return "sales/quotation/" . $request->id_quo;
    }

    public function save_addproduct(Request $request)
    {
        $sku    = $request->idpro_new;
        $id_quo = $request->id_quo;

        $reqku = $sku == "new" ? $request->newsku : '';
        if ($sku == "new") {
            $datareq = array(
                'req_product' => $reqku,
                'req_price'   => $request->p_price_new,
                'created_by'  => Auth::id()
            );
            // dd($data2);
            $req = ProductReq::create($datareq);
        }
        $check       = $sku == "new" ? "det_quo_harga_req" : "det_quo_harga_order";
        $finalsku    = $sku == "new" ? $req->id : null;
        $idproduct   = $sku == "new" ? "new" : $sku;
        $data2 = array(
            'id_quo'             => $id_quo,
            'id_product'         => $idproduct,
            'id_product_request' => $finalsku,
            'det_quo_qty'        => $request->p_qty_new,
            $check               => $request->p_price_new,
            'created_by'         => Auth::id()
        );
        // dd($data2);
        $qry    = QuotationProduct::insertGetId($data2);

        $data3 = array(
            'id_quo'           => $id_quo,
            'id_quo_pro'       => $qry,
            'id_product'       => $idproduct,
            'det_quo_qty_beli' => $request->p_qty_new,
            'created_by'       => Auth::id()
        );
        // dd($data2);
        $qrys    = Purchase_model::insert($data3);

        $barang =  $sku == "new" ? $request->newsku : getProductDetail($request->idpro_new)->name;
        if ($qry) {
            $log  = array(
                'activity_id_quo'       => $id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Menambahkan barang " . $barang,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($log);
            ActQuoModel::insert($log);
        }
        return "sales/quotation/" . $request->id_quo;
    }

    public function delete_changeproduct(Request $request)
    {
        $sku    = $request->idpro;
        $id_quo = $request->id_quo;

        $nama_barang = $request->id == 'new' ? getProductReq(getProductQuo($sku)->id_product_request)->req_product : getProductDetail($request->id)->name;

        $log  = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $nama_barang . " telah dihapus dari list ",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        Purchase_model::where('id_quo_pro', $sku)->delete();

        QuotationProduct::where('id', $sku)->delete();
        return "sukses";
    }

    public function edit_product(Request $request)
    {
        if (in_array(getUserEmp($request->myid)->id_emp, array(
            getQuo($request->quo)->created_by, getQuo($request->quo)->id_sales, getQuo($request->quo)->id_admin
        ))) {
            $view = "product-editchange";
        } else {
            $div = $request->session()->get('division_id');
            if ($div == '2') {
                $view = "product-edit";
            } elseif ($div == '5') {
                $view = "product-editwh";
            } elseif ($div == '10') {
                $view = "product-editchange";
            } elseif ($div == '6') {
                $view = "product-editps";
            } else {
                $view = "product-edit";
            }
        }

        $stock = [
            'ready'       => 'Ready',
            'limited'     => 'Limited',
            'indent'      => 'Indent',
            'discontinue' => 'Discontinue',
            'eol'         => 'EOL',
        ];
        $bayar = [
            'cash' => 'Cash',
            'cod'  => 'COD',
            'cbd'   => 'CBD',
            'net'   => 'NET',
        ];
        $if_type = [
            'normal' => 'normal',
            'percen' => 'percen',
        ];

        $price   = QuotationOtherPrice::where('id_quo', $request->quo)->first();
        $product = QuotationProduct::where('id_quo', $request->quo)->get();
        $purchase = QuotationProduct::where('quotation_product.id_quo', $request->quo)
            ->join('quotation_purchase as q', 'q.id_quo_pro', '=', 'quotation_product.id')
            ->get();
        return view('sales.quotation.attribute.' . $view, [
            'method'   => "post",
            'action'   => 'Sales\QuotationController@save_product',
            'product'  => $product,
            'purchase' => $purchase,
            'stock'    => $stock,
            'price'    => $price,
            'if_type'  => $if_type,
            'bayar'    => $bayar,
        ]);
    }

    public function save_product(Request $request)
    {
        $gets = Session::get('division_id');
        // dd($request);
        $id_quo = $request->id_quo;

        if ($request->has('p_qty')) {
            $this->exec_change($request);
            $message = "Ubah Harga Berhasil";
        } else if ($request->has('bayar')) {
            $this->exec_purchasing($request);
            $message = "Ubah Harga Pembelian Berhasil";
        } else {
            $this->exec_product($request);
            $message = "Edit Harga Modal Berhasil";
        }

        return redirect("sales/quotation/" . $id_quo)->with('success', $message);
    }

    public function exec_product(Request $request)
    {
        $sku    = $request->idpro;
        $id_quo = $request->id_quo;
        foreach ($sku as $item => $v) {
            CariBeda($sku[$item], $request, $item);
            $data2 = [
                'id_vendor'             => $request->vendor[$item],
                'det_quo_status_vendor' => $request->stock[$item],
                'det_quo_note'          => $request->note[$item],
                'det_quo_harga_modal'   => $request->p_price[$item],
                'handle_by'             => Auth::id(),
                'updated_by'            => Auth::id(),
                'updated_at'            => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $exec = $data2;
            QuotationProduct::where('id', $sku[$item])->update($exec);

            if ($request->vendor[$item] <> null) {
                $data_history = [
                    'id_product'            => $sku[$item],
                    'id_vendor'             => $request->vendor[$item],
                    'det_quo_status_vendor' => $request->stock[$item],
                    'det_quo_note'          => $request->note[$item],
                    'det_quo_harga_modal'   => $request->p_price[$item],
                    'created_by'            => Auth::id(),
                    'created_at'            => Carbon::now('GMT+7')->toDateTimeString()
                ];
                $hist = ProductModalHistory::insert($data_history);
            }
        }
    }

    public function exec_purchasing(Request $request)
    {
        $sku    = $request->idpro;
        $id_quo = $request->id_quo;
        foreach ($sku as $item => $v) {
            $datapr = [
                'id_vendor_beli'      => $request->vendor[$item],
                'det_quo_status_beli' => $request->stock[$item],
                'det_quo_type_beli'   => $request->bayar[$item],
                'det_quo_note_beli'   => $request->note[$item],
                'det_quo_harga_final' => $request->p_price[$item],
                'updated_by'          => Auth::id(),
                'updated_at'          => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $exec = $datapr;
            Purchase_model::where('id', $sku[$item])->update($exec);


            // checking purchase status & change price //
            $checking = Purchase_detail::select('*','purchase_detail.price as price_detail')
            ->where([
                ['p.id_quo', $id_quo],
                ['sku', $request->sku[$item]]
            ])->join('purchase_orders as p', 'p.id', '=', 'purchase_detail.id_po')->first();
            // echo '<pre>' . var_export($checking, true) . '</pre>';
            if (!is_null($checking)) {
                $msg = "";
                if ($checking->price_detail <> $request->p_price[$item]) {
                    $po_utama = [
                        'status'        => 'draft',
                    ];
                    $po = [
                        'price'     => $request->p_price[$item],
                    ];
                    Purchase_order::where('id', $checking->id_po)->update($po_utama);
                    Purchase_detail::where([
                        ['sku', $request->sku[$item]],
                        ['id_po', $checking->id_po]
                    ])->update($po);
                    $msg = "Merubah harga dari " . $checking->price_detail . " ke " . $request->p_price[$item];
                }

                if ($checking->id_vendor <> $request->vendor[$item]) {
                    $idbaru   = $request->vendor[$item] == null ? '157' : $request->vendor[$item];
                    $po_utama = [
                        'id_vendor'     => $idbaru,
                        'status'        => 'draft',
                    ];

                    $po = [
                        'price'             => $request->p_price[$item],
                    ];

                    Purchase_order::where('id', $checking->id_po)->update($po_utama);
                    $msg = "Merubah vendor dari " . getVendor($checking->id_vendor)->vendor_name . " ke " . getVendor($idbaru)->vendor_name;
                }



                $lognew = array(
                        'activity_sku'          => $request->sku[$item],
                        'activity_id_quo'       => $id_quo,
                        'activity_id_user'      => Auth::id(),
                        'activity_name'         => $msg,
                        'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                    );
                ActPurchaseModel::insert($lognew);

            }
            // end checking //
        }


        $logh = $request->has('p_berat') ? 'Update Harga pengiriman ' : 'Update Harga & Status Stock ';
        $log  = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $logh,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
    }

    public function exec_change(Request $request)
    {
        // dd($request);
        $sku     = $request->idpro;
        $sku_new = $request->idpro_new;
        $id_quo  = $request->id_quo;

        foreach ($sku as $item => $v) {
            $prices = getProductQuo($sku[$item])->id_product == "new" ? 'det_quo_harga_req' : 'det_quo_harga_order';
            $datach = [
                'det_quo_qty'          => $request->p_qty[$item],
                $prices                => $request->p_price[$item],
                'det_quo_harga_ongkir' => $request->p_ongkir[$item],
                'updated_by'           => Auth::id(),
                'updated_at'           => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $exec = $datach;
            QuotationProduct::where('id', $sku[$item])->update($exec);

            $datachp = [
                'det_quo_qty_beli' => $request->p_qty[$item],
                'updated_by'       => Auth::id(),
                'updated_at'       => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_model::where('id_quo_pro', $sku[$item])->update($datachp);
        }
        if ($request->has('idpro_new')) {
            foreach ($sku_new as $item => $v) {
                $prices = $sku_new[$item] == "new" ? 'det_quo_harga_req' : 'det_quo_harga_order';
                $reqku[$item] = $sku_new[$item] == "new" ? $request->newsku[$item] : '';
                if ($sku_new[$item] == "new") {
                    $datareq = array(
                        'req_product' => $reqku[$item],
                        'req_price'   => $request->price_new[$item],
                        'created_by'  => Auth::id()
                    );
                    $req = ProductReq::create($datareq);
                }

                $finalsku[$item] = $sku_new[$item] == "new" ? $req->id : null;
                $datach = [
                    'id_quo'               => $id_quo,
                    'id_product'           => $sku_new[$item],
                    'id_product_request'   => $finalsku[$item],
                    'det_quo_qty'          => $request->p_qty_new[$item],
                    $prices                => $request->p_price_new[$item],
                    'created_by'           => Auth::id(),
                    'created_at'           => Carbon::now('GMT+7')->toDateTimeString()
                ];
                $exec = $datach;
                QuotationProduct::insert($exec);
            }
        }

        $price_if = $request->price_if_type == 'normal' ? $request->price_if_normal : $request->price_if_percen;
        $other_price = [
            'ongkir_customer' => $request->ongkir_customer,
            'price_other'     => $request->price_other,
            'price_if_type'   => $request->price_if_type,
            'price_if'        => $price_if,
            'updated_by'      => Auth::id(),
            'updated_at'      => Carbon::now('GMT+7')->toDateTimeString()
        ];
        QuotationOtherPrice::where('id_quo', $id_quo)->update($other_price);

        $logh = $request->has('idpro_new') ? 'Menambah kan barang' : 'Update Data Barang / Harga';
        $log  = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $logh,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
    }

    public function detail_product(Request $request)
    {
        $mine     = getUserEmp(Auth::id());
        $main     = QuotationProduct::where('id', $request->idquo)->first();
        $req_id   = QuotationProduct::select('id_product_request')->where('id_product_request', $main->id_product_request)->first();
        $live     = ProductLive::where('sku', $main->id_product)->first();
        if ($main->id_product == "new" &  in_array($mine->division_id, array('1', '2'))) {
            return "Redirect";
        } else {
            return view('sales.quotation.attribute.product-detail', [
                'idp'  => $request->idquo,
                'live'    => $live,
                'main' => $main,
            ]);
        }
    }


    public function approve(Request $request)
    {
        // dd($request);
        $main   = QuotationDocument::where('id_quo', $request->quo)->first();
        // dd($main);
        return view('sales.quotation.attribute.approval', [
            'main'   => $main,
            'quo'    => $request->quo,
            'type'   => $request->type,
            'method' => "post",
            'action' => 'Sales\QuotationController@approve_save',
        ]);
    }

    public function approve_save(Request $request)
    {
        // dd($request);
        $id_quo  = $request->id_quo;
        $savelog = $request->type == "approve" ? "Paket ini sudah di approve" : "Maaf Paket ini di reject";
        $approve = $request->type;
        if ($approve == "approve") {
            $st_name = "Setuju";
        } else {
            $st_name = "Tidak Setuju";
        }
        $data2   = [
            'quo_instatus'       => $request->type,
            'quo_approve_status' => $request->type,
            'quo_eksstatus'      => $st_name,
            'quo_approve_by'     => Auth::id(),
            'quo_approve_note'   => $request->approval_note,
            'updated_by'         => Auth::id(),
            'updated_at'         => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $qry = QuotationModel::where('id', $id_quo)->update($data2);
        if ($qry) {

            $note = $request->approval_note <> '' || $request->approval_note == null ? " dengan catatan " . $request->approval_note : '';
            $log = array(
                'activity_id_quo'       => $id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => $savelog . ' ' . $note,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($data2);
            ActQuoModel::insert($log);
        }


        return redirect("sales/quotation/" . $request->id_quo)->with('success', ucwords($request->input('company')) . 'Update Status successfully');
    }

    public function get_status($where, $value)
    {
        $data  = QuotationStatus::where($where, $value)->get();
        foreach ($data as $reg => $value) {
            $arr[$value->status_name] = $value->status_name;
        }
        return $arr;
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

        $menu_count    = QuotationModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::where('id', 'like', '%' . $search . '%')
                ->orWhere('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = QuotationModel::where('id', 'like', '%' . $search . '%')
                ->orWhere('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $typedata  = "normal";
        $data      = $this->dataNstatus($posts, $typedata);
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
        $icus   = $request->segment(9);
        $sku    = $request->segment(10);

        if ($sku <> 'kosong') {
            $typedata      = "sku";
            $menu_count    = QuotationModel::filtersearchproduct($type, $status, $sales, $sdate, $edate, $icus, $sku);
            $totalData     = $menu_count->count();
            $totalFiltered = $totalData;

            if (empty($request->input('search')['value'])) {
                $posts = QuotationModel::filtersearchlimitproduct($type, $status, $sales, $sdate, $edate, $icus, $sku, $start, $limit, $order, $dir)->get();
            } else {
                $search        = $request->input('search')['value'];
                $posts         = QuotationModel::filtersearchfindproduct($type, $status, $sales, $sdate, $edate, $icus, $sku, $start, $limit, $order, $dir, $search)->get();
                $totalFiltered = count(QuotationModel::filtersearchfindproduct($type, $status, $sales, $sdate, $edate, $icus, $sku, $start, $limit, $order, $dir, $search)->get());
            }
        } else {
            $typedata      = "normal";
            $menu_count    = QuotationModel::filtersearch($type, $status, $sales, $sdate, $edate, $icus);
            $totalData     = $menu_count->count();
            $totalFiltered = $totalData;
            if (empty($request->input('search')['value'])) {
                $posts = QuotationModel::filtersearchlimit($type, $status, $sales, $sdate, $edate, $icus, $start, $limit, $order, $dir)->get();
            } else {
                $search        = $request->input('search')['value'];
                $posts         = QuotationModel::filtersearchfind($type, $status, $sales, $sdate, $edate, $icus, $start, $limit, $order, $dir, $search)->get();
                $totalFiltered = count(QuotationModel::filtersearchfind($type, $status, $sales, $sdate, $edate, $icus, $start, $limit, $order, $dir, $search)->get());
            }
        }
        // dd($posts);

        $data = $this->dataNstatus($posts, $typedata);
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function dataNstatus($posts, $typedata)
    {
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $check_outbound = getOutbound('id_quo', $post->id);
                $check_invoice  = GetInvoice('id_quo', $post->id);
                $check_purchase = getPurchasing('id_quo', $post->id);
                $check_product  = CheckLengkap($post->id);

                if ($post->quo_approve_status == null) {
                    $status = $post->quo_type == '1' ? "Pendekatan" : "Negosiasi";
                } else {
                    $status = $post->quo_approve_status;
                }

                if ($check_product > 0) {
                    $posisi       = "Product";
                    $posisi_color = "primary";
                } else {
                    if ($post->quo_type == 1) {
                        $posisi       = "Sales";
                        $posisi_color = "brown-700";
                    } else {
                        if ($check_purchase == null) {
                            $posisi       = "Purchasing";
                            $posisi_color = "danger";
                        } else {
                            if ($check_outbound == null) {
                                $posisi       = "Warehouse";
                                $posisi_color = "warning";
                            } else {
                                if (SearchActivity($post->id_quo, "resi") == null) {
                                    $posisi       = "Warehouse";
                                    $posisi_color = "warning";
                                } else if ($check_invoice == null) {
                                    $posisi       = "Invoicing";
                                    $posisi_color = "violet-600";
                                } else {
                                    if ($check_invoice->$check_invoice == null) {
                                        $posisi       = "Finance";
                                        $posisi_color = "indigo-600";
                                    } else {
                                        $posisi       = "Selesai";
                                        $posisi_color = "success";
                                    }
                                }
                            }
                        }
                    }
                }
                if ($typedata == "sku") {
                    $newid = $post->id_quo;
                } else {
                    $newid = $post->id;
                }

                $data[] = [
                    'id'             => $newid,
                    'quo_no'         => $post->quo_no,
                    'quo_name'       => $post->quo_name,
                    'id_customer'    => getCustomer($post->id_customer)->company,
                    'id_admin'       => getEmp($post->id_admin)->emp_name,
                    'id_sales'       => getEmp($post->id_sales)->emp_name,
                    'quo_order_at'   => $post->quo_order_at,
                    'updated_at'     => $post->updated_at->format('Y-m-d'),
                    'quo_instatus'   => $post->quo_instatus,
                    'quo_eksstatus'  => $post->quo_eksstatus,
                    'quo_ekskondisi' => $post->quo_ekskondisi,
                    'quo_price'      => GetTotalAkhir($newid),
                    'quo_type'       => getQuoType($post->quo_type)->type_name,
                    'quo_color'      => getQuoType($post->quo_type)->color,
                    'status'         => $status,
                    'posisi'         => $posisi,
                    'posisi_color'   => $posisi_color,
                ];
            }
        }
        return $data;
    }

    public function draft_beli(Request $request)
    {

        $cmargin = MarginCheck($request->quo);
        if ($cmargin >= getConfig('automargin') || CheckApprove($request->quo) == 'yes') {
            $lanjut = 'yes';
        } else {
            $lanjut = 'no';
        }

        $if_type = [
            'normal' => 'normal',
            'pindah' => 'pindah',
        ];
        // dd($request);
        $product = Purchase_model::where([
            ['id_quo', $request->quo],
            ['id_vendor_beli', $request->idpro]
        ])->get();
        $main = QuotationModel::select('quotation_models.*', 'q.type_name')
            ->where('quotation_models.id', $request->quo)
            ->join('quotation_type as q', 'q.id', '=', 'quotation_models.quo_type')->first();
        $invoice  = QuotationInvoice::where('id_quo', $request->quo)->first();
        $document = QuotationDocument::where('id_quo', $request->quo)->first();
        return view('sales.quotation.attribute.purchasing_draftbeli', [
            'method'   => "post",
            'lanjut'   => $lanjut,
            'action'   => 'Sales\QuotationController@ajukan_beli',
            'product'  => $product,
            'vendor'   => $request->idpro,
            'if_type'  => $if_type,
            'invoice'  => $invoice,
            'document' => $document,
            'main'     => $main,
        ]);
    }

    public function ajukan_beli(Request $request)
    {
        $id_quo = $request->id_quo;
        $sku    = $request->idpro;

        $max_id = Purchase_order::max('id');

        $purchase_order  = [
            'id_quo'     => $id_quo,
            'po_number'  => 'PO' . date("y") . sprintf("%06d", $max_id + 1),
            'id_vendor'  => $request->vendor[0],
            'status'     => "draft",
            'price'      => $request->total,
            'created_by' => Auth::id(),
        ];
        $qry = Purchase_order::create($purchase_order);

        foreach ($sku as $item => $v) {
            $type = $request->if_type == null || in_array($request->if_type, array('normal', '', null)) ? null : $request->p_ref;
            $purchase_draft  = [
                'id_quo'     => $id_quo,
                'id_po'      => $qry->id,
                'no_ref'     => $type == null ? null : $type[$item],
                'id_product' => $sku[$item],
                'sku'        => $request->id_product[$item],
                'qty'        => $request->p_qty[$item],
                'price'      => getPurchasingQuo($sku[$item])->det_quo_harga_final,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_detail::insert($purchase_draft);
        }

        $update_quo  = [
            'quo_instatus' => "pengajuan beli",
            'updated_by'   => Auth::id(),
            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString()
        ];
        QuotationModel::where('id', $request->id_quo)->update($update_quo);

        $log = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Pembelian telah diajukan " . $purchase_order['po_number'] . " menunggu approval management",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($data2);
        ActQuoModel::insert($log);
        return redirect("sales/quotation/" . $id_quo)->with('success', 'Pengajuan PO Berhasil');
    }

    public function find_so(Request $request)
    {

        $data = [];
        // dd($request);
        if ($request->has('q')) {
            $search = $request->q;
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $data   = QuotationModel::select('*')->where('id', $search)
                ->orWhere('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->get();
        }
        return response()->json($data);
    }

    public function getproduct_so(Request $request)
    {
        $data = [];
        $search = $request->id_quo;
        $data   = QuotationProduct::select('*')->where('id_quo', $search)
            ->get();

        return view('sales.quotation.attribute.dropdownproduct', [
            'main'   => $data,
        ]);
    }

    public function getdetailproduct_so(Request $request)
    {
        $data   = [];
        $search = $request->id_pro;
        $data   = Purchase_model::select('*')->where('id_quo_pro', $search)->first();

        return response()->json($data);
    }

    function price($angka)
    {
        $hasil = number_format($angka, 2, ".", ",");
        return $hasil;
    }

    public function ex_quo(Request $request)
    {
        $status = $request->segment(5) == 0 ? '' : $request->segment(5);
        $type   = $request->segment(4) == 'kosong' ? '' : $request->segment(4);
        $status = $request->segment(5) == '' ? '' : $status;
        $sales  = $request->segment(6) == 'kosong' ? '' : $request->segment(6);
        $sdate  = $request->segment(7) == 'kosong' ? '' : $request->segment(7);
        $edate  = $request->segment(8) == 'kosong' ? '' : $request->segment(8);
        $All    = $request->segment(9) == 'kosong' ? '' : $request->segment(9);

        if ($request->segment(9) == "true") {
            $query = QuotationModel::select('*','quotation_models.created_at AS tanggalbuat', DB::raw('sum(det_quo_harga_order*det_quo_qty) as total'))
                ->join('quotation_product as q', 'q.id_quo', '=', 'quotation_models.id')->groupBy('id_quo')->get();
        } else {
            $query = QuotationModel::filterexport($type, $status, $sales, $sdate, $edate)->get();
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
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(25);
        $sheet->getColumnDimension('M')->setWidth(25);

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Sales Order');
        $sheet->setCellValue('C1', 'Type');
        $sheet->setCellValue('D1', 'Nomer');
        $sheet->setCellValue('E1', 'Nama Paket');
        $sheet->setCellValue('F1', 'Customer');
        $sheet->setCellValue('G1', 'Sales');
        $sheet->setCellValue('H1', 'Tanggal Order');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Invoice Price');
        $sheet->setCellValue('K1', 'Ongkir');
        $sheet->setCellValue('L1', 'Total');
        $sheet->setCellValue('M1', 'Tanggal Buat');
        $rows = 2;
        foreach ($query as $qp) {
            $price         = QuotationOtherPrice::where('id_quo', $qp->id_quo)->first();
            $subtotal      = $qp->total;
            $vat           = $subtotal * (GetPPN(GetInvoiceDate($qp['id_quo']), $qp->created_at) / 100);
    
            $sheet->setCellValue('A' . $rows, $j++);
            $sheet->setCellValue('B' . $rows, 'SO' . sprintf("%06d", $qp['id_quo']));
            $sheet->setCellValue('C' . $rows, typename($qp['quo_type'])->type_name);
            $sheet->setCellValue('D' . $rows, $qp['quo_no'] == null ? "RFQ" : $qp['quo_no']);
            $sheet->setCellValue('E' . $rows, $qp['quo_name']);
            $sheet->setCellValue('F' . $rows, getCustomer($qp['id_customer'])->company);
            $sheet->setCellValue('G' . $rows, emp_name($qp['id_sales']));
            $sheet->setCellValue('H' . $rows, $qp['quo_order_at']);
            if ($qp['quo_ekskondisi'] == "Batal") {
                $kondisi = "Batal";
            } else {
                $kondisi = $qp['quo_eksstatus'];
            }
            $sheet->setCellValue('I' . $rows, $kondisi);
            $sheet->setCellValue('J' . $rows, $subtotal + $vat);
            $sheet->setCellValue('K' . $rows, $price->ongkir_customer);
            $sheet->setCellValue('L' . $rows, '=J'.$rows.'+K'.$rows);
            $sheet->setCellValue('M' . $rows, $qp['tanggalbuat']);
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

    public function edit_invoice(Request $request)
    {
        // dd($request);
        $quo_in = QuotationInvoice::where('id', $request->id_invoice)->first();
        return view('sales.quotation.attribute.edit_invoice', [
            'invoice_id' => $quo_in,
            'type'       => $request->type,
        ]);
    }

    public function split_po(Request $request)
    {
        $stock = [
            'ready'       => 'Ready',
            'limited'     => 'Limited',
            'indent'      => 'Indent',
            'discontinue' => 'Discontinue',
            'eol'         => 'EOL',
        ];

        $bayar = [
            'cash' => 'Cash',
            'cod'  => 'COD',
            'cbd'  => 'CBD',
            'net'  => 'NET',
            'top'  => 'TOP',
        ];
        return view('sales.quotation.attribute.purchasing-split', [
            'n_equ'  => $request->input('n_equ'),
            'id_quo' => $request->id_quo,
            'idpro'  => $request->idpro,
            'stock'  => $stock,
            'bayar'  => $bayar,
        ]);
    }

    public function delete_split_po(Request $request)
    {
        $check  = Purchase_model::where('id', $request->idpro)->first();
        $oldqty = Purchase_model::where('id', $check->id_quo_pro)->first();
        $balik  = $oldqty->det_quo_qty_beli + $check->det_quo_qty_beli;

        $minqty = [
            'det_quo_qty_beli'    => $balik,
            'updated_by'          => Auth::id(),
            'updated_at'          => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Purchase_model::where('id', $check->id_quo_pro)->update($minqty);

        $split = Purchase_model::find($request->idpro);
        $split->delete();
        return "sales/quotation/" . $request->id_quo;
    }

    public function exec_split_po(Request $request)
    {
        $getid = Purchase_model::where('id', $request->id_pro_split)->first();
        // $sku   = getProductQuo($request->id_pro_split)->id_product;
        $sku   = $getid->id_product;
        $sisa  = $getid->det_quo_qty_beli - $request->p_qty_split;

        if ($sisa == 0) {
            return redirect("sales/quotation/" . $request->id_quo_split)->with('success', 'Opps Split Tidak dapat dilakukan');
        } else {
            

            $minqty = [
                'det_quo_qty_beli'    => $getid->det_quo_qty_beli - $request->p_qty_split,
                'updated_by'          => Auth::id(),
                'updated_at'          => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_model::where('id', $getid->id)->update($minqty);



            // save to new table 
            $datch = [
                'id_quo'              => $request->id_quo_split,
                'id_quo_pro'          => $getid->id_quo_pro,
                'id_product'          => $sku,
                'det_quo_qty_beli'    => $request->p_qty_split,
                'id_vendor_beli'      => $request->vendor_split,
                'det_quo_status_beli' => $request->stock_split,
                'det_quo_type_beli'   => $request->bayar_split,
                'det_quo_note_beli'   => $request->note_split,
                'det_quo_harga_final' => $request->p_price_split,
                'created_by'          => Auth::id(),
                'created_at'          => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_model::insert($datch);

            $checkpo = Purchase_order::where([
                ['id_quo',$request->id_quo_split],
                ['sku',$sku]
                ])->first();

                if($checkpo!==null){
                    $data_new = [
                        'status'=>'draft',
                    ];
                    Purchase_detail::where('id_po', $checkpo->id_po)->update($data_new);
                }

            $log  = array(
                'activity_id_quo'       => $request->id_quo_split,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => getProductDetail($sku)->name . " pembelian di pecah ke beberapa vendor",
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($log);
            ActQuoModel::insert($log);
            return "sales/quotation/" . $request->id_quo_split;
        }
    }

    public function SiapKirim(Request $request)
    {

        SendToOutbound($request->quo);
        SendToOut($request->quo);
        saveInventory($request->quo);      
        $log  = array(
            'activity_id_quo'       => $request->quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Paket telah siap kirim, nomer DO sudah tergenerate, menunggu proses pengiriman team gudang",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return "oke";
    }


    public function editinvoices(Request $request)
    {
        // dd($request);
        $quo_mo  = QuotationModel::where('id', $request->idquo)->first();
        $quo_pro = QuotationProduct::where('id_quo', $request->idquo)->get();
        $invoice = QuotationInvoice::where('id_quo', $request->idquo)->first();
        return view('sales.quotation.attribute.edit_catalog_inv', [
            'main'    => $quo_mo,
            'invoice' => $invoice,
        ]);
    }


    public function confirm_payments(Request $request)
    {
        // dd($request);
        $quo_in     = QuotationInvoice::where('id', $request->id_inv)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $product    = QuotationProduct::where('id_quo', $quo_mo->id)->get();
        $quo_pro    = QuotationProduct::select(DB::raw('det_quo_harga_order*det_quo_qty as subtotal'))
                      ->join('quotation_models', 'quotation_product.id_quo','=', 'quotation_models.id')
                      ->where('quotation_models.id', $quo_in->id_quo)->get()->sum('subtotal');
        $inv_detail = QuotationInvoiceDetail::where('id_quo_inv', $quo_in->id)->get();
        $cdtl       = count($inv_detail);
        $dtl        = QuotationInvoiceDetail::where('id_quo_inv', $quo_in->id)->get()->sum('payment_amount');
        $selisih    = QuotationInvoiceDetail::where('id_quo_inv',$quo_in->id)->get()->sum('payment_amount');
        $sisa_bayar = ($quo_in->total_payment==null ? $quo_mo->quo_price - $selisih : $quo_in->total_payment - $selisih);
        $inv_oth    = QuotationInvoiceOthers::where('id_quo_inv', $quo_in->id)->get();
        $coth       = count($inv_oth);
        $other      = QuotationInvoiceOthers::where('id_quo_inv', $quo_in->id)->get()->sum('nilai_potongan');
        $time_inv   = $quo_in == null ? '0000-00-00':$quo_in->tgl_invoice;
        $times      = $quo_mo->quo_type == 1 ? $quo_mo->created_at:$quo_mo->quo_order_at;
        $hitung     = $quo_pro*(GetPPN($time_inv,$times)/100);
        $ttl        = ($quo_pro+$hitung);
        $sisa       = $coth==0 ? ($ttl - $dtl - $quo_in->potongan_ntpn_ppn - $quo_in->potongan_ntpn_pph) : ($quo_mo->quo_price - $dtl - $other - $quo_in->potongan_ntpn_ppn - $quo_in->potongan_ntpn_pph); 
        $payment    = QuotationInvoicePayment::where('id_quo_inv', $quo_in->id)->first();
        $price      = QuotationOtherPrice::where('id_quo', $quo_mo->id)->first();
        $new_wh     = WarehouseOut::where('id_quo', $quo_mo->id)->first();
        $outs       = Warehouse_pengiriman::where('id_quo', $quo_mo->id)->first();
        return view('finance.invoice.update_invoice',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'payment'    => $payment,
            'inv'        => $inv_detail,
            'cdtl'       => $cdtl,
            'sisa_bayar' => $sisa_bayar,
            'dtl'        => $dtl,
            'outs'       => $outs,
            'quo_pro'    => $quo_pro,
            'wh_out'     => $new_wh,
            'sisa'       => $sisa,
            'price'      => $price,
            'product'    => $product,
            'coth'       => $coth,
            'inv_oth'    => $inv_oth,
        ]);
    }

    public function pakaistock(Request $request)
    {
        $check  = WarehouseOut::where('id_quo', $request->quo)->first();
        $getsku = getProductDetail($request->sku)->product_id;

        if ($check == null) {
        } else {                                            
            $product = QuotationProduct::where([
                ['id_quo', $request->quo],
                ['id_product', $request->sku],
            ])->first();
            $order_awal = InventoryModel::where([
                ['sku', $getsku],
                ['status', 'order'],
            ])->first();
            $checkinv = InventoryModel::where([
                ['sku', $getsku],
                ['id_quo', $request->quo],
                ['status', 'order'],
            ])->first();
            if($checkinv==null){
                $datainv = [
                    'id_quo'     => $request->quo,
                    'sku'        => $getsku,
                    'qty'        => $product->det_quo_qty,
                    'price'      => $order_awal->price,
                    'jenis'      => "sales",
                    'status'     => "use",
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                ];
                InventoryModel::insert($datainv);

            }
            
            
        }

        $data2 = [
            'id_vendor'   => null,
        ];
        QuotationProduct::where('id', $product->id)->update($data2);
        $log   = array(
            'activity_id_quo'       => $request->quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Purchase merubah " . $request->sku." ".getProductDetail($request->sku)->name . " menggunakan stock inventory",
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);
        return "oke";
    }

    public function show_dobalikan(Request $request)
    {
        $filedo = WarehouseDoc::select('warehouse_document.*')
        ->where('o.id_quo', $request->id)
        ->join('warehouse_outbound as o', 'o.id', '=', 'warehouse_document.id_outbound')
        ->get();
        return view('sales.quotation.attribute.dobalikan', [
            'filedo'  => $filedo,
        ]);
    }


}
