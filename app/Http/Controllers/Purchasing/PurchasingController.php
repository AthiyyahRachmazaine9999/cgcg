<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\Customer_pic;
use App\Models\Role\Role_cabang;
use App\Models\Finance\FinanceHistory;
use App\Models\Finance\Pay_VoucherApp;
use App\Models\Finance\Pay_VoucherModel;
use App\Models\Finance\Pay_VoucherDetail;
use App\Models\Finance\Pay_VoucherPayment;
use App\Models\HR\EmployeeModel;
use App\Models\Activity\ActQuoModel;
use App\Models\Activity\DownloadAct;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\PurchaseFinanceModel;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Purchasing\PurchaseHistory;
use App\Models\Purchasing\PurchaseFinanceBank;
use App\Models\Inventory\InventoryModel;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use Storage;
use DB;



class PurchasingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchasing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchasing.create', [
            'id_vendor' => $this->AllVendor(),
            'method'   => "post",
            'action'   => 'Purchasing\PurchasingController@store',
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
        $sku       = $request->input('sku');
        $usr       = getUserEmp(Auth::id());
        $price     = $request->price[0];
        $id_pro    = ProductLive::where('sku', $request->sku[0])->first();
        $max_id    = Purchase_order::max('id');
        $po_number = 'PO' . date("y") . sprintf("%06d", $max_id + 1);
        $data      = [
            'po_number'   => $po_number,
            'status_time' => $request->quo_order_at,
            'id_customer' => $request->id_customer,
            'id_vendor'   => $request->input('id_vendor'),
            'status'      => "draft",
            'type'        => "stock purchase",
            'status_by'   => $usr->id_emp,
            'nom_total'   => $request->quo_price,
            'status_time' => Carbon::now(),
            'price'       => $request->sub[0],
            $save . '_by'       => Auth::id(),
        ];
        $qry = $save == 'created' ? Purchase_order::create($data) : Purchase_order::where('id', $id)->update($data);
        if ($qry) {
            $w = $qry->id;
            foreach ($sku as $a => $q) {
                $data = [
                    'id_product' => $sku[$a],
                    'id_po'      => $w,
                    'sku'        => getProductPo($sku[$a])->sku,
                    'qty'        => $request->qty[$a],
                    'created_at' => Carbon::now(),
                    'price'      => $request->price[$a],
                    $save . '_by'=> Auth::id(),
                ];
                // dd($data);
                $qry = $save == 'created' ? Purchase_detail::create($data) : Purchase_detail::where('id', $id)->update($data);

                $datainv = [
                    'id_purchase' => $po_number,
                    'id_vendor'   => $request->input('id_vendor'),
                    'sku'         => $sku[$a],
                    'qty'         => $request->qty[$a],
                    'price'       => $request->price[$a],
                    'jenis'       => $request->jenis,
                    'status'      => "order",
                    'created_at'  => Carbon::now(),
                    $save . '_by'       => Auth::id(),
                ];
                // dd($data);
                InventoryModel::insert($datainv);
            }

            $hist = [
                    'id_po'         => $w,
                    'po_number'     => $po_number,
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                    'activity_name' => "Menambahkan Purchase Order",
                ];
            PurchaseHistory::create($hist);
        }

        return redirect("purchasing/order")->with('success');
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
        $pay_dtl    = Pay_VoucherDetail::where('no_po', $id)->get();
        $po_so = Purchase_order::join('quotation_invoice as qi', 'qi.id_quo', '=', 'purchase_orders.id_quo')
            ->where('po_number', $id)->first();
        $vend       = VendorModel::where('id', $main->id_vendor)->first();
        $altaddress = Purchase_address::where('id_po', $main->id)->first();
        $payvendor  = Pay_VoucherPayment::where('id_po', $main->id)->orderBy('id', 'desc')->get();
        $history    = PurchaseHistory::where('id_po', $main->id)->orderby('id', 'desc')->limit(5)->get();
        if ($main->type == "stock purchase") {
            $product    = Purchase_detail::where('id_po', $main->id)->get();
            return view('purchasing.showpo', [
                'main'     => $main,
                'product'  => $product,
                'vend'     => $vend,
                'usr'      => getUserEmp(Auth::id()),
                'alamat'   => $altaddress,
                'payvendor' => $payvendor,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
                'po_so'    => $po_so,
                'pay_dtl'  => $pay_dtl,
                'history'  => $history,
            ]);
        } else {
            $product    = Purchase_detail::where('id_po', $main->id)->get();
            return view('purchasing.show', [
                'main'     => $main,
                'product'  => $product,
                'vend'     => $vend,
                'payvendor' => $payvendor,
                'usr'      => getUserEmp(Auth::id()),
                'alamat'   => $altaddress,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
                'po_so'    => $po_so,
                'pay_dtl'  => $pay_dtl,
                'history'  => $history,
            ]);
        }
    }


    function pay_vendor(Request $request)
    {
        $po      = Purchase_order::where('po_number', $request->id)->first();
        $product = Purchase_detail::where('id_po', $po->id)
                ->join('quotation_product', 'quotation_product.id', '=', 'purchase_detail.id_product')
                ->get();
        $pay_dtl = Pay_VoucherDetail::where('id_pay', $request->id_pay)->first();
        return view('purchasing.attribute.pay_vendor',[
            'main'    => $po,
            'pay_dtl' => $pay_dtl,
            'id_pay'  => $request->id_pay,
            'product' => $product,
            'bank'    => $this->getBank(),
            'method' => "post",
            'action'  => "Purchasing\PurchasingController@savePay_vendor",
        ]);
    }



    function Editpay_vendor(Request $request)
    {
        // dd($request);
        $product = Pay_VoucherPayment::where([['id_po', $request->id_po], ['id', $request->id]])->first();
        return view('purchasing.attribute.Editpay_vendor',[
            'main'    => $product,
            'bank'    => $this->getBank(),
            'method'  => "post",
            'action'  => "Purchasing\PurchasingController@saveEditPay_vendor",
        ]);
    }


    function saveEditPay_vendor(Request $request)
    {
        $po      = Purchase_order::where('id', $request->id_po)->first();
        $bank    = PurchaseFinanceBank::where('id', $request->bank_name)->first();
        $product = Pay_VoucherPayment::where([['id_po', $request->id_po], ['id', $request->id]])->first();
        if($request->status=="lunas")
        {
            $pays = [
                'status'         => "Done Payment",
                'updated_by'     => Auth::id(),
                'updated_at'     => Carbon::now(),
            ];
            $saves  = Pay_VoucherDetail::where('id_pay', $request->id_pay)->update($pays);
            $fin_hist =[
            'activity_name'     => "Done Payment",
            'status_activity'   => "Payment Voucher",
            'activity_user'     => getUserEmp(Auth::id())->id,
            'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
            'activity_refrensi' => $request->id_pay,
            'created_by'        => Auth::id(),
            ];
            $qry3 = FinanceHistory::create($fin_hist);
        }
        
        $data = [
            'id_po'        => $request->id_po,
            'id_quo'       => $request->id_quo,
            'id_pay'       => $request->id_pay,
            'status'       => $product->status,
            'date_payment' => $request->date_payment,
            'note'         => $request->note,
            'id_pay'       => $request->id_pay,
            'no_po'        => $po->po_number,
            'pays'         => $request->pays,
            'bank_name'    => $bank->nama_bank,
            'no_rek'       => $bank->no_rek,
            'doc_pay'      => $request->has('files') ? Storage::disk('public')->putFileAs('new_finance/purchase', $request->file('files'), $request->file('files')->getClientOriginalName()) : $product->doc_pay,
            'doc_other'    => $request->has('doc_other') ? Storage::disk('public')->putFileAs('new_finance/purchase', $request->file('doc_other'), $request->file('doc_other')->getClientOriginalName()) : $product->doc_other,
            'updated_by'   => Auth::id(),
        ];
        $qrys  = Pay_VoucherPayment::where('id', $request->id)->update($data);

        $pay_v  = Pay_VoucherDetail::where('id_pay', $request->id_pay)->first();
        $no_pay = $pay_v==null ? '' : " Pada No. Payment ".$pay_v->no_payment;
        $hist = [
                    'id_po'         => $request->id_po,
                    'po_number'     => $po->po_number,
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                    'activity_name' => "Mengubah pembayaran PO".$pay_v->no_payment,
                ];
        PurchaseHistory::create($hist);
        return redirect('purchasing/order/'.$po->po_number)->with('success', 'Updated Successfully');
    }  
    
    
    function Hapuspay_vendor(Request $request)
    {
        $po   = Purchase_order::where('id', $request->id_po)->first();
        $hist = [
                    'id_po'         => $request->id_po,
                    'po_number'     => $po->po_number,
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                    'activity_name' => "Menghapus Pembayaran",
                ];
        PurchaseHistory::create($hist);
        $todo = Pay_VoucherPayment::where([['id_po', $request->id_po], ['id', $request->id]])->delete();
        return "purchasing/order/" . $po->po_number;
    }



    function getBank()
    {
        $data = PurchaseFinanceBank::all();
        $arr  = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->nama_bank. " - ".$reg->no_rek;
        }
        return $arr;
    }

    function savePay_vendor(Request $request)
    {
        // dd($request);
        $po   = Purchase_order::where('id', $request->id_po)->first();
        $bank = PurchaseFinanceBank::where('id', $request->bank_name)->first();
        
        if($request->status=="lunas")
        {
            $pays = [
                'status'         => "Done Payment",
                'updated_by'     => Auth::id(),
                'updated_at'     => Carbon::now(),
            ];
            $saves  = Pay_VoucherDetail::where('id_pay', $request->id_pay)->update($pays);
            $fin_hist =[
            'activity_name'     => "Done Payment",
            'status_activity'   => "Payment Voucher",
            'activity_user'     => getUserEmp(Auth::id())->id,
            'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
            'activity_refrensi' => $request->id_pay,
            'created_by'        => Auth::id(),
            ];
            $qry3 = FinanceHistory::create($fin_hist);
        
        }
        $data = [
            'id_po'        => $request->id_po,
            'id_quo'       => $request->id_quo,
            'id_pay'       => $request->id_pay,
            'no_po'        => $po->po_number,
            'status'       => $request->status,
            'date_payment' => $request->date_payment,
            'note'         => $request->note,
            'pays'         => $request->pays,
            'bank_name'    => $bank->nama_bank,
            'no_rek'       => $bank->no_rek,
            'doc_pay'      => $request->has('files') ? Storage::disk('public')->putFileAs('new_finance/purchase', $request->file('files'), $request->file('files')->getClientOriginalName()) : null,
            'doc_other'    => $request->has('doc_other') ? Storage::disk('public')->putFileAs('new_finance/purchase', $request->file('doc_other'), $request->file('doc_other')->getClientOriginalName()) :null,
            'created_by'   => Auth::id(),
            'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $qrys  = Pay_VoucherPayment::create($data);

        $pay_v = Pay_VoucherDetail::where('id_pay', $request->id_pay)->first();
        $no_pay = $pay_v==null ? '' : " Pada No. Payment ".$pay_v->no_payment;
        $hist = [
                    'id_po'         => $request->id_po,
                    'po_number'     => $po->po_number,
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                    'activity_name' => "Menambahkan pembayaran pembelian PO".$no_pay. " secara ".$request->status." pada tanggal ".$request->date_payment,
                ];
        PurchaseHistory::create($hist);

        return redirect('purchasing/order/'.$po->po_number)->with('success', 'Created Successfully');
    }    
  


    function create_payment(Request $request)
    {
        if ($request->type == "Edit") {
            $main       = Purchase_order::where('po_number', $request->po_number)->first();
            $vend       = VendorModel::where('id', $main->id_vendor)->first();
            $pay_dtl    = Pay_VoucherDetail::where([['no_po', $request->po_number], ['id_pay', $request->id_pay]])->first();
            $altaddress = Purchase_address::where('id_po', $main->id)->first();
            $product    = Purchase_detail::where('id_po', $main->id)
                ->join('quotation_product', 'quotation_product.id', '=', 'purchase_detail.id_product')
                ->get();
            $product1 = Purchase_detail::where('id_po', $main->id)
                ->join('quotation_product', 'quotation_product.id', '=', 'purchase_detail.id_product')
                ->first();

            $po_so = Purchase_order::join('quotation_invoice as qi', 'qi.id_quo', '=', 'purchase_orders.id_quo')
                ->where('po_number', $request->po_number)->first();
            return view('purchasing.attribute.payment_voucher_edit', [
                'main'     => $main,
                'product'  => $product,
                'product1' => $product1,
                'po_so'    => $po_so,
                'id_quo'   => $main->id_quo == 0 ? null : 'SO' . sprintf("%06d", getQuo($main->id_quo)->id),
                'pay_dtl'  => $pay_dtl,
                'vend'     => $vend,
                'vendor'   => $this->getVendor(),
                'no_po'    => $this->getNO_PO(),
                'alamat'   => $altaddress,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
            ]);
        } else {
            $main       = Purchase_order::where('po_number', $request->po_number)->first();
            $vend       = VendorModel::where('id', $main->id_vendor)->first();
            $altaddress = Purchase_address::where('id_po', $main->id)->first();
            $product    = Purchase_detail::where('id_po', $main->id)
                ->join('quotation_product', 'quotation_product.id', '=', 'purchase_detail.id_product')
                ->get();
            $product1 = Purchase_detail::where('id_po', $main->id)
                ->join('quotation_product', 'quotation_product.id', '=', 'purchase_detail.id_product')
                ->first();

            $po_so = Purchase_order::join('quotation_invoice as qi', 'qi.id_quo', '=', 'purchase_orders.id_quo')
                ->where('po_number', $request->po_number)->first();
            return view('purchasing.attribute.payment_voucher', [
                'main'     => $main,
                'product'  => $product,
                'product1' => $product1,
                'po_so'    => $po_so,
                'id_quo'   => $main->id_quo == 0 ? '---' : 'SO' . sprintf("%06d", getQuo($main->id_quo)->id),
                'vend'     => $vend,
                'vendor'   => $this->getVendor(),
                'no_po'    => $this->getNO_PO(),
                'alamat'   => $altaddress,
                'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
            ]);
        }
    }

    function show_payment(Request $request)
    {
        $id = $request->id;

        $pay     = Pay_VoucherModel::where('id', $id)->first();
        $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
        $pay_app = Pay_VoucherApp::where('id_pay_dtl', $pay_dtl->id)->first();
        $app     = Pay_VoucherApp::where('id_pay_dtl', $pay_dtl->id)->get();
        if ($pay_dtl->type_payment == "top") {
            $top = "TOP";
        } else if ($pay_dtl->type_payment == "cbd") {
            $top = "CBD";
        } else if ($pay_dtl->type_payment == "net") {
            $top = "NETT";
        } else {
            $top = $pay_dtl->type_payment;
        }
        $mine    = getUserEmp(Auth::id());
        if ($pay_dtl->app_hrd == "Done" && $mine->division_id == 8 || $mine->id == 2) {
            $info = array(
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } elseif ($pay_dtl->app_finance == "Done" && $mine->division_id == 3) {
            $info = array(
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } elseif ($pay_app != null && $mine->id == $pay_app->status_by) {
            $info = array(
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } else {
            $info = array(
                'BtnApp'  => "",
                'BtnApp2' => '',
            );
        }
        return view('purchasing.attribute.show_payv', [
            'pay'     => $pay,
            'pay_dtl' => $pay_dtl,
            'pay_app' => $pay_app,
            'app'     => $app,
            'top'     => $top,
            'info'    => $info,
            'main'    => $mine,
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

    public function approve(Request $request)
    {
        // dd($request);
        $main   = Purchase_order::where('id', $request->idpo)->first();
        // dd($main);
        return view('purchasing.attribute.approval', [
            'main'   => $main,
            'idpo'   => $request->idpo,
            'type'   => $request->type,
            'method' => "post",
            'action' => 'Purchasing\PurchasingController@approve_save',
        ]);
    }

    public function approve_save(Request $request)
    {
        // dd($request);
        $id_po   = $request->id_po;
        $look    = Purchase_order::where('id', $request->id_po)->first();
        $savelog = $request->type == "approve" ? "Pembelian " . $look->po_number . " di approve" : "Maaf Pembelian  " . $look->po_number . " ini di reject";
        $histlog = $request->type == "approve" ? "Menyutujui Pembelian": "Tidak Menyetujui Pembelian";
        $data2   = [
            'status'      => $request->type,
            'status_time' => Carbon::now('GMT+7')->toDateTimeString(),
            'status_by'   => Auth::id(),
            'updated_by'  => Auth::id(),
            'updated_at'  => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $qry = Purchase_order::where('id', $id_po)->update($data2);
        if ($qry) {

            $note = $request->approval_note <> '' || $request->approval_note == null ? " dengan catatan " . $request->approval_note : '';
            $log = array(
                'activity_id_quo'       => $look->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => $savelog . ' ' . $note,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($data2);
            ActQuoModel::insert($log);

            $hist = [
                'id_po'         => $request->id_po,
                'po_number'     => $look->po_number,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => $histlog . ' ' . $note,
            ];
            PurchaseHistory::create($hist);
        }

        return redirect("purchasing/order/")->with('success', $look->po_number . ' berhasil di ' . $request->type);
    }

    public function ganti_alamat(Request $request)
    {
        $idpo   = $request->idpo;
        $po     = Purchase_order::where('id', $idpo)->first();
        $adr    = Purchase_address::where('id_po', $idpo)->first();
        $names  = $request->type == "new" ? '' : $adr->name;
        $addre  = $request->type == "new" ? '' : $adr->address;
        $method = $request->type == "new" ? "post" : "put";
        $action = $request->type == "new" ? 'Purchasing\PurchasingController@save_alamat' : ['Purchasing\PurchasingController@update_alamat', $adr->id];
        // dd($view);
        return view('purchasing.attribute.purchasing_tambahalamat', [
            'idpo'   => $idpo,
            'names'  => $names,
            'addre'  => $addre,
            'method' => $method,
            'action' => $action,
        ]);
    }

    public function save_alamat(Request $request)
    {
        // dd($request);
        $alamat  = [
            'id_po'      => $request->idpo,
            'name'       => $request->name,
            'address'    => $request->address,
            'created_by' => Auth::id()
        ];
        $qry = Purchase_address::create($alamat);

        $update_po  = [
            'pengiriman'     => "dropship",
            'pengiriman_alt' => $qry->id,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Purchase_order::where('id', $request->idpo)->update($update_po);

        $look = Purchase_order::where('id', $request->idpo)->first();

        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Menambahkan alamat pengiriman untuk purchase order " . $request->name . " " . $look->po_number . " " . $request->address,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $hist = [
                'id_po'         => $request->idpo,
                'po_number'     => $look->po_number,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => "Menambahkan alamat pengiriman untuk purchase order " . $request->name . " " . $look->po_number . " " . $request->address,
            ];
        PurchaseHistory::create($hist);

        return redirect("purchasing/order/" . $look->po_number)->with('success', $look->po_number . ' Tambah alamat pengiriman lain berhasil');
    }

    public function update_alamat(Request $request)
    {
        $alamat  = [
            'name'       => $request->name,
            'address'    => $request->address,
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Purchase_address::where('id_po', $request->idpo)->update($alamat);
        $look = Purchase_order::where('id', $request->idpo)->first();

        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Merubah alamat pengiriman untuk purchase order " . $request->name . " " . $look->po_number . " " . $request->address,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $hist = [
                'id_po'         => $request->idpo,
                'po_number'     => $look->po_number,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => "Merubah alamat pengiriman untuk purchase order " . $request->name . " " . $request->address,
            ];
        PurchaseHistory::create($hist);

        return redirect("purchasing/order/" . $look->po_number)->with('success', $look->po_number . ' Tambah alamat pengiriman lain berhasil');
    }

    public function delete_alamat(Request $request)
    {
        // dd($request);
        $id   = $request->nopo;
        $todo = Purchase_address::where('id_po', $id)->delete();

        $update_po  = [
            'pengiriman'     => null,
            'pengiriman_alt' => null,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Purchase_order::where('id', $request->nopo)->update($update_po);
        $look = Purchase_order::where('id', $request->nopo)->first();

        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Delete alamat pengiriman untuk purchase order " . $look->po_number,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $hist = [
                'id_po'         => $request->nopo,
                'po_number'     => $look->po_number,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => "Menghapus alamat pengiriman untuk purchase order",
            ];
        PurchaseHistory::create($hist);

        return "purchasing/order/" . $look->po_number;
    }

    public function kirim_po(Request $request)
    {
        $idpo   = $request->idpo;
        $type   = $request->type;
        $po     = Purchase_order::where('id', $idpo)->first();
        $vendor = VendorModel::where('id', $po->id_vendor)->first();
        $pic    = Vendor_pic::where('vendor_id', $vendor->id)->get();
        $cabang = Role_cabang::where('id', $po->id_customer)->first();
        // dd($view);
        return view('purchasing.attribute.purchasing_emailpo', [
            'type'     => $type,
            'cabang'   => $cabang,
            'idpo'     => $idpo,
            'vendor'   => $vendor,
            'pic'      => $pic,
            'getemail' => [getAllEmail()],
            'method'   => "post",
            'action'   => 'Purchasing\PurchasingController@exec_kirim_po',
        ]);
    }

    public function defaulttext(Request $request)
    {
        $idpo   = $request->idpo;
        $type   = $request->type;
        $po     = Purchase_order::where('id', $idpo)->first();
        $price  = Purchase_detail::where('id_po', $idpo)->get();
        $vendor = VendorModel::where('id', $po->id_vendor)->first();
        $cabang = Role_cabang::where('id', $po->id_customer)->first();
        // dd($view);
        if ($type == "stock purchase") {
            return view('purchasing.attribute.defaulttext_purchase', [
                'text'   => $vendor,
                'po'     => $po,
                'cabang' => $cabang,
                'price'  => $price,
            ]);
        } else {
            return view('purchasing.attribute.defaulttext', [
                'text'  => $vendor,
                'po'    => $po,
                'price' => $price,
            ]);
        }
    }

    public function exec_kirim_po(Request $request)
    {
        $u_creator  = getUserEmp(Auth::id())->id_emp;
        $vendormail = $request->name;
        $cc         = $request->has('cc_mail') ? $request->cc_mail : 'no';

        $order    = Purchase_order::where('id', $request->idpo)->first();

        if ($request->has('lampiran')) {
            foreach ($request->lampiran as $value => $y) {
                $file = $request->file('lampiran')[$value]->getClientOriginalName();
                Storage::disk('public')->putFileAs('attachment_po/' . $order->po_number, $request->file('lampiran')[$value], $file);
            }
        }

        $detail   = [$order, $request->body];
        $event    = "Pembelian PT Mitra Era Grobal";
        $testdata = [$u_creator, $vendormail, $cc, $detail, $event, $order->po_number];
        // dd($testdata);
        SendEmailVendor($u_creator, $vendormail, $cc, $detail, $event, $order->po_number);

        $kirim_time = $order->kirim_time == null ? Carbon::now('GMT+7')->toDateTimeString() : $order->kirim_time;
        $data2   = [
            'kirim_time' => $kirim_time,
        ];
        Purchase_order::where('id', $request->idpo)->update($data2);

        $log = array(
            'activity_id_quo'       => $order->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Purchase order sudah dikirim melalui email untuk" . $order->po_number,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $hist = [
                'id_po'         => $request->idpo,
                'po_number'     => $order->po_number,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => "Mengirim Purchase Order melalui email",
            ];
        PurchaseHistory::create($hist);

        return redirect("purchasing/order/" . $order->po_number)->with('success', $order->po_number . ' Email Pembelian berhasil dikirim');
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'po_number',
            2 => 'id_quo',
            3 => 'id_vendor',
            4 => 'status',
            5 => 'price',
            6 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Purchase_order::select('*', 'purchase_orders.id as idku')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Purchase_order::select('*', 'purchase_orders.id as idku')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = Purchase_order::select('*', 'purchase_orders.id as idku')->where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Purchase_order::select('*', 'purchase_orders.id as idku')->where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
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

    public function filter(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'po_number',
            2 => 'id_quo',
            3 => 'id_vendor',
            4 => 'status',
            5 => 'price',
            6 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];


        $st       = $request->segment(4);
        $vendor   = $request->segment(5);
        $product  = $request->segment(6);
        $s_date   = $request->segment(7);
        $end_date = $request->segment(8);

        $menu_count    = Purchase_order::filtersearch($st, $vendor, $product, $s_date, $end_date);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $search        = $request->input('search')['value'];
            $posts = Purchase_order::filtersearchlimit($st, $vendor, $product, $s_date, $end_date, $start, $limit, $order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = Purchase_order::filtersearchfind($st, $vendor, $s_date, $end_date, $product, $start, $limit, $order, $dir, $search)->get();
            $totalFiltered = count(Purchase_order::filtersearchfind($st, $vendor, $s_date, $end_date, $product, $start, $limit, $order, $dir, $search)->get());
        }

        $typedata  = "filter";
        $data      = $this->dataNstatus($posts, $typedata);

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
                $note_payment = Pay_VoucherPayment::where('id_po', $post->idku)->orderBy('id', 'desc')->first();
                // dd($note_payment);

                if($post->status=="reject")
                {
                    $payment = "Reject";
                    $tgl_payment = null;
                }else{
                    if($note_payment==null)
                    {
                        $payment = "Unpaid";
                        $tgl_payment = null;
                    }else if($note_payment->status=="parsial" )
                    {
                        $payment     = "Paid Parsial";
                        $tgl_payment = Carbon::parse($note_payment->date_payment)->format('d F Y');
                    }else{
                        $payment = "Done Paid";
                        $tgl_payment = Carbon::parse($note_payment->date_payment)->format('d F Y');
                    }
                }

                
                if ($typedata == "filter") {
                    $newid = $post->idku;
                } else {
                    $newid = $post->id;
                }
                $time = $post->kirim_time == null ? $post->created_at : $post->kirim_time;
                $data[] = [
                    'id'          => $newid,
                    'po_number'   => $post->po_number,
                    'id_quo'      => 'SO' . sprintf("%06d", $post->id_quo),
                    'id_vendor'   => getVendor($post->id_vendor)->vendor_name,
                    'payment'     => $payment,
                    'tgl_payment' => $tgl_payment,
                    'price'       => PurchaseTotal($newid),
                    'created_at'  => Carbon::parse($time)->format('Y-m-d'),
                    'status'      => $post->status,
                ];
            }
        }
        return $data;
    }



public function ex_quo(Request $request)
{
    $type       = $request->segment(9);
        if($type==="normal")
        {
            return $this->export_normal($request);
        }else{
            return $this->export_product($request);
        }
}


public function export_normal($request)
{
    $status     = $request->segment(4);
    $vendor     = $request->segment(5);
    $id_product = $request->segment(6);
    $start      = $request->segment(7);
    $end        = $request->segment(8);

    $query = Purchase_order::filterexport($status,$vendor,$start,$end)->groupBy('id_po')->get();
    
    $j=1;
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:H1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'No. PO');
        $sheet->setCellValue('C1', 'No. SO');
        $sheet->setCellValue('D1', 'Vendor');
        $sheet->setCellValue('E1', 'Total Harga');
        $sheet->setCellValue('F1', 'Tanggal PO');
        $sheet->setCellValue('G1', 'Created by');
        $sheet->setCellValue('H1', 'Created at');
        $rows = 2;
        
    foreach($query as $qp){
        // dd($qp);
        $vat = $qp['total']*(GetPPN($qp['created_at'],$qp['created_at'])/100);
        
        $sheet->setCellValue('A' . $rows, $j++);  
        $sheet->setCellValue('B' . $rows, $qp['po_number']);  
        $sheet->setCellValue('C' . $rows, 'SO' . sprintf("%06d", $qp['id_quo']));  
        $sheet->setCellValue('D' . $rows, getVendor($qp['id_vendor'])->vendor_name);  
        $sheet->setCellValue('E' . $rows, $qp['total'] + $vat); 
        $sheet->setCellValue('F' . $rows, Carbon::parse($qp['kirim_time'])->format('Y-m-d'));
        $sheet->setCellValue('G' . $rows, user_name($qp['created_by']));
        $sheet->setCellValue('H' . $rows, Carbon::parse($qp['created_at'])->format('Y-m-d'));
        $rows++;  
    }
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('Purchasing Order.xlsx');
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Purchasing Order.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
}


public function export_product($request)
{
    // dd($request);
    $status     = $request->segment(4);
    $vendor     = $request->segment(5);
    $id_product = $request->segment(6);
    $start      = $request->segment(7);
    $end        = $request->segment(8);

    $query = Purchase_order::filterexportproduct($status,$vendor,$id_product,$start,$end)->get();
    dd($query);
    
    $j=1;
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:I1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(25);

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'No. PO');
        $sheet->setCellValue('C1', 'No. SO');
        $sheet->setCellValue('D1', 'Product');
        $sheet->setCellValue('E1', 'Vendor');
        $sheet->setCellValue('F1', 'Total Harga');
        $sheet->setCellValue('G1', 'Tanggal PO');
        $sheet->setCellValue('H1', 'Created by');
        $sheet->setCellValue('I1', 'Created at');
        $rows = 2;
        
    foreach($query as $qp){
        // dd($qp);
        $vat = $qp['total']*(GetPPN($qp['created_at'],$qp['created_at'])/100);
        // dd($qp);
        $sheet->setCellValue('A' . $rows, $j++);  
        $sheet->setCellValue('B' . $rows, $qp['po_number']);  
        $sheet->setCellValue('C' . $rows, 'SO' . sprintf("%06d", $qp['id_quo'])); 
        $sheet->setCellValue('D' . $rows, getProductDetail($qp['sku'])->name);  
        $sheet->setCellValue('E' . $rows, getVendor($qp['id_vendor'])->vendor_name);  
        $sheet->setCellValue('F' . $rows, $qp['total'] + $vat); 
        $sheet->setCellValue('G' . $rows, Carbon::parse($qp['kirim_time'])->format('Y-m-d'));
        $sheet->setCellValue('H' . $rows, user_name($qp['created_by']));
        $sheet->setCellValue('I' . $rows, Carbon::parse($qp['created_at'])->format('Y-m-d'));
        $rows++;  
    }
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('Purchasing Order - Product.xlsx');
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Purchasing Order - Product.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
}




    public function AllVendor()
    {

        $data = VendorModel::all();
        $arr  = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->vendor_name;
        }
        return $arr;
    }

    public function save_date(Request $request)
    {
        // dd($request);
        $data = [
            'order_at'   => $request->date,
            'updated_by' => Auth::id(),
        ];

        Purchase_order::where('po_number', $request->idpo)->update($data);
        $look    = Purchase_order::where('po_number', $request->idpo)->first();
        $log = array(
            'activity_id_quo'       => $look->id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => 'Merubah tanggal PO menjadi ' . $request->date,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($data2);
        ActQuoModel::insert($log);

        $hist = [
                'id_po'         => $look->id,
                'po_number'     => $request->idpo,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => 'Merubah tanggal PO menjadi ' . $request->date,
            ];
        PurchaseHistory::create($hist);
        return $request->date;
    }

    public function gantivendor(Request $request)
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
        ];

        $id      = $request->nomerpo;
        $po      = Purchase_order::where('po_number', $id)->first();
        // $product = Purchase_detail::where('purchase_detail.id_po', $po->id)
        //     ->join('quotation_product as p', 'p.id', '=', 'purchase_detail.id_product')->get();
        $product = Purchase_detail::where('purchase_detail.id_po', $po->id)
            ->join('quotation_purchase as p', 'p.id', '=', 'purchase_detail.id_product')
            ->join('quotation_product as pro', 'pro.id', '=', 'p.id_quo_pro')
            ->get();
        return view('purchasing.attribute.purchasing_gantivendor', [
            'method'  => "post",
            'action'  => 'Purchasing\PurchasingController@save_gantivendor',
            'product' => $product,
            'stock'   => $stock,
            'bayar'   => $bayar,
            'po'      => $po,
        ]);
    }

    public function save_gantivendor(Request $request)
    {
        $sku    = $request->idpro;
        $id_quo = $request->id_quo;
        $id_po  = $request->id_po;

        $po     = Purchase_detail::where('id_po', $id_po)->get();
        $subttl = Purchase_detail::select(DB::raw('sum(price*qty) as kali'))->where('id_po', $id_po)->first();
        $ppn    = $subttl->kali / 10;

        $data = [
            'id_vendor'  => $request->vendor_baru,
            'status'     => 'draft',
            'price'      => ($subttl->kali + $ppn),
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
        ];
        Purchase_order::where('id', $id_po)->update($data);

        foreach ($sku as $item => $v) {
            $quo_pur = Purchase_model::where('id_quo_pro', $sku[$item])->first();
            $datapr = [
                'id_vendor_beli'      => $request->vendor_baru,
                'det_quo_harga_final' => $request->p_price[$item],
                'updated_by'          => Auth::id(),
                'updated_at'          => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_model::where('id_quo_pro', $sku[$item])->update($datapr);

            $datapur = [
                'price'      => $request->p_price[$item],
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_detail::where([
                ['id_product', $quo_pur->id],
                ['id_po', $id_po]
            ])->update($datapur);

            $datapur = [
                'price'      => $request->p_price[$item],
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_detail::where([
                ['id_product', $sku[$item]],
                ['id_po', $id_po]
            ])->update($datapur);
        }
        $po      = Purchase_order::where('id', $request->id_po)->first();
        $poss    = Purchase_order::where('id', $request->id_po)->first();

        // $data_po = [
        //     'status' => 'draft',
        // ];

        // purchase_order::where([
        //     ['id', $request->id_po],
        // ])->update($data_po);

        $message = 'Merubah vendor pembelian untuk ' . $po->po_number . ' menunggu approval ulang management';
        $log     = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $message,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        $hist = [
                'id_po'         => $request->id_po,
                'po_number'     => $poss->po_number,
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'    => Auth::id(),
                'activity_name' => $message,
            ];
        PurchaseHistory::create($hist);
        return redirect("sales/quotation/" . $id_quo)->with('success', $message);
    }

    public function changeisppn(Request $request)
    {
        // dd($request);
        
        $look  = Purchase_order::where('id', $request->id)->first();
        $isppn = $look->isppn == "yes" ? "no" : "yes";
        $idppn = $look->isppn == "yes" ? "tanpa" : "dengan";
        if($look->id_quo <> "0"){
            $log = array(
                'activity_id_quo'       => $look->id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => 'Merubah ' . $look->po_number.' menjadi '.$idppn.' PPN',
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            ActQuoModel::insert($log);

            $hist = [
                    'id_po'         => $request->id,
                    'po_number'     => $look->po_number,
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                    'activity_name' => 'Merubah ' . $look->po_number.' menjadi '.$idppn.' PPN',
                ];
            PurchaseHistory::create($hist);
        }

        $data  = [
            'isppn'      => $isppn,
            'updated_by' => Auth::id(),
        ];
        Purchase_order::where('id', $request->id)->update($data);
        
        return "purchasing/order/" . $look->po_number;
    }

    public function cancel(Request $request)
    {
        # code...
    }

    public function product_clone(Request $request)
    {

        return view('purchasing.attribute.product-clone', [
            'n_equ'  => $request->input('n_equ'),
            'id_quo' => $request->id_quo,
            'id_po'  => $request->id_po,
        ]);
    }

    public function product_newclone(Request $request)
    {
        $id_po = $request->id_po;
        $look  = Purchase_order::where('id', $id_po)->first();
        $main  = [
            'type'       => 'link po',
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
        ];

        $qry = Purchase_order::where('id', $id_po)->update($main);

        if ($qry) {

            // detail part 
            $name       = explode("-", $request->idpro_new);
            $jml_segmen = count($name);
            $idn        = $name[$jml_segmen - 1];
            $skun       = $name[$jml_segmen - 2];

            $detail   = [
                'id_po'      => $id_po,
                'id_quo'     => $request->id_quo,
                'no_ref'     => $request->idso,
                'id_product' => $skun,
                'sku'        => $idn,
                'qty'        => $request->p_qty_new,
                'price'      => $request->p_price_new,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];

            $det      = Purchase_detail::where('id', $look->id)->insert($detail);
            $sku      = getProductQuo($request->idpro_new)->id_product;
            $quo_awal = $look->id_quo;
            $quo_new  = $request->id_quo;

            $log = array(
                'activity_id_quo'       => $quo_awal,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => $sku . ' dipindahkan ke nomer ' . $look->po_number . ' dengan ' . sprintf("%06d", $request->id_quo),
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );

            $log = array(
                'activity_id_quo'       => $quo_new,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => $sku . ' dipindahkan ke nomer ' . $look->po_number . ' dengan ' . sprintf("%06d", $request->id_quo),
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($data2);
            ActQuoModel::insert($log);

            $hist = [
                    'id_po'         => $request->id_po,
                    'po_number'     => $look->po_number,
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                    'activity_name' => "Mengirim Purchase Order melalui email",
                ];
            PurchaseHistory::create($hist);

        }
        return "purchasing/order/" . $look->po_number;
    }

    public function attachment(Request $request)
    {
        return view('purchasing.attribute.attachment-clone', [
            'n_equ'  => $request->input('n_equ'),
            'idpo'  => $request->idpo,
        ]);
    }


    public function getNO_PO()
    {

        $data = Purchase_order::join('quotation_invoice as qi', 'purchase_orders.id_quo', '=', 'qi.id_quo')
            ->get();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->po_number] = strtoupper($reg->po_number);
        }
        return $arr;
    }

    function getVendor()
    {
        $vendor = VendorModel::all();
        $arr    = array();
        foreach ($vendor as $reg) {
            $arr[$reg->id] = $reg->vendor_name;
        }
        return $arr;
    }


    public function get_vendor()
    {
        $data = Purchase_order::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id_vendor] = getVendor($reg->id_vendor)->vendor_name;
        }
        return $arr;
    }

    public function FindQuo()
    {
        $data = Purchase_order::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id_quo] = "SO" . sprintf("%06d", $reg->id_quo);
        }
        return $arr;
    }

    public function FindPO()
    {
        $data = Purchase_order::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->po_number] = $reg->po_number;
        }
        return $arr;
    }

    public function all_history(Request $request)
    {
        $history = PurchaseHistory::where('id_po', $request->id)->orderBy('id', 'desc')->get();
        return view('purchasing.attribute.act_all_log',[
            'history' => $history,
        ]);
    }
}
