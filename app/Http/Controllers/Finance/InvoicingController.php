<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationReplacement;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Finance\FinanceHistory;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\warehouse_out;
use App\Models\WarehouseUpdate\WarehouseOut;
use App\Models\WarehouseUpdate\WarehouseOutDetail;
use App\Models\WarehouseUpdate\WarehouseOutHistory;
use App\Models\Warehouse\Warehouse_address;
use App\Models\Warehouse\Warehouse_pengiriman;
use App\Models\Warehouse\Warehouse_history;
use App\Models\Warehouse\Warehouse_resi;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationInvoicePaymentDetail;
use App\Models\Sales\QuotationInvoicePayment;
use App\Models\Sales\QuotationInvoiceOthers;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Purchasing\PurchaseFinanceBank;
use Carbon\Carbon;
use App\Models\Activity\ActQuoModel;
use DB;
use Storage;
use PDF;


class InvoicingController extends Controller
{
    
    public function index()
    {
        return view('finance.invoice.index',[
            'quo_id'  => $this->get_IdQuos(),
        ]);
    }

    public function get_IdQuos()
    {
        $data = QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo','=','quotation_models.id')->get();
        $arr = array();
        foreach ($data as $reg) {
            $check = $reg->quo_ekskondisi!='Batal' ? 'allow' : 'no';
            $check2= $reg->quo_approve_status!='reject' ? 'allow' : 'no';
            if($check=='allow' && $check2 == 'allow')
            {
                $arr[$reg->id_quo] = 'SO'.sprintf("%06d",$reg->id_quo);
            }
        }
        return $arr;
    }
    
    
public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'id_quo',
            2 => 'tgl_invoice',
            3 => 'created_by',
            4 => 'note',
            5 => 'no_invoice',
            6 => 'quo_no',
            7 => 'ket_lunas',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->whereNull('type')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->whereNull('type')
                ->orderby('quotation_invoice.id_quo', $dir)->offset($start)->limit($limit)->get();
            // dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->whereNull('type')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('quotation_invoice.id_quo', $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->whereNull('type')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('quotation_invoice.id_quo', $dir)->offset($start)->limit($limit)->count();
        }
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
            $check = $post->quo_ekskondisi!='Batal' ? 'allow' : 'no';
            $check2= $post->quo_approve_status!='reject' ? 'allow' : 'no';
                if($check=='allow' && $check2 == 'allow')
                {
                    if($post->type_payment!=null)
                    {
                        if($post->type_payment == "parsial")
                        {
                            $notes = "Parsial";
                        }else if($post->type_payment == "full"){
                            $notes = "Full";
                        }else{
                            $notes = "Unpaid";
                        }
                    }else{
                            $notes = "Unpaid";
                    }

                    if($post->ket_lunas == "Finish")
                    {
                        if($post->tgl_lunas==null)
                        {
                            $ket = " - Lunas";
                        }else{
                            $ket = " - Lunas ".Carbon::parse($post->tgl_lunas)->format('d F Y');
                        }
                    }else{
                        $ket = " ";
                    }

                    $quo_pro    = QuotationProduct::select(DB::raw('det_quo_harga_order*det_quo_qty as subtotal'))
                              ->join('quotation_models', 'quotation_product.id_quo','=', 'quotation_models.id')
                              ->where('quotation_models.id', $post->id_quo)->get()->sum('subtotal');
                    $hasil      = getPriceInvoice($post->id_quo);
                    $data[] = [
                            'no_invoice'  => $post->no_invoice,
                            'id_quo'      => "SO".sprintf("%06d", $post->id_quo),
                            'tgl_invoice' => $post->tgl_invoice,
                            'note'        => $post->note,
                            'ket_lunas'   => $notes." ".$ket,
                            'lunas'       => $post->ket_lunas,
                            'payments'    => number_format(sumInvoicePaid($post->id)),
                            'amount'      => number_format(SisaHargaInvoice($post->id)),                    
                            'notes'       => $notes,
                            'price'       => $hasil['total'],
                            'created_by'  => user_name($post->created_by),
                            'id'          => $post->id,
                            'quo_no'      => $post->quo_no,
                        ];
                }
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
    
    
public function update_invoice($id)
    {
        $quo_in     = QuotationInvoice::where('id', $id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $product    = QuotationProduct::where('quotation_product.id_quo', $quo_in->id_quo)
                    ->join('quotation_purchase as q', 'q.id_quo_pro', '=', 'quotation_product.id')
                    ->get();
        $quo_pro    = QuotationProduct::select(DB::raw('det_quo_harga_order*det_quo_qty as subtotal'))
                      ->join('quotation_models', 'quotation_product.id_quo','=', 'quotation_models.id')
                      ->where('quotation_models.id', $quo_in->id_quo)->get()->sum('subtotal');
        $payment    = QuotationInvoicePayment::where('id_quo_inv', $quo_in->id)->first();
        $inv_detail = QuotationInvoicePayment::join('quotation_invoice_payment_detail', 'quotation_invoice_payment.id', '=', 'quotation_invoice_payment_detail.id_dtl_payment')
                      ->where('quotation_invoice_payment.id_quo_inv', $quo_in->id)->groupBy('id_dtl_payment')->get();
        $cdtl       = count($inv_detail);
        $dtl        = QuotationInvoicePaymentDetail::where('id_quo_inv', $quo_in->id)->get()->sum('payment_amount');
        $selisih    = QuotationInvoicePaymentDetail::where('id_quo_inv',$id)->get()->sum('payment_amount');
        $sisa_bayar = ($quo_in->total_payment==null ? $quo_mo->quo_price - $selisih : $quo_in->total_payment - $selisih);
        $inv_oth    = QuotationInvoiceOthers::where('id_quo_inv', $quo_in->id)->get();
        $coth       = count($inv_oth);
        $other      = QuotationInvoiceOthers::where('id_quo_inv', $quo_in->id)->get()->sum('nilai_potongan');
        $harga      = getPriceInvoice($quo_in->id_quo);
        $amount     = SisaHargaInvoice($id);
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
            'sisa'       => $amount,
            'price'      => $price,
            'product'    => $product,
            'coth'       => $coth,
            'inv_oth'    => $inv_oth,
        ]);
        
    }

    public function edit_invoice_up(Request $request)
    {
        // dd($request);
        $quo_in     = QuotationInvoice::where('id', $request->id_inv)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        return view('finance.invoice.others.edit_upper',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'no_so'      => 'SO'.sprintf('%06d',$quo_in->id_quo),
        ]);
    }
    

    public function SaveEditUp(Request $request)
    {
        // dd($request);
        $quo_inv = QuotationInvoice::where('id', $request->id_inv)->first();
        $redirect= $request->has('type_read') ? "sales/quotation/" . $request->id_quo : 'finance/invoice/edit_invoice/'.$request->id_inv;
        if ($request->has('file_ntpn_pph')) {
            $files    = $request->file('file_ntpn_pph');
            $pph      = Storage::disk('public')->putFileAs('new_finance/invoicing', $request->file('file_ntpn_pph'), $files->getClientOriginalName());
            $ppn      = $quo_inv->file_ntpn_ppn;
        } else if($request->has('file_ntpn_ppn')){
            $file_ppn = $request->file('file_ntpn_ppn');
            $ppn      = Storage::disk('public')->putFileAs('new_finance/invoicing', $request->file('file_ntpn_ppn'), $file_ppn->getClientOriginalName());
            $pph      = $quo_inv->file_ntpn_pph;
        }else {
            $pph = $quo_inv->file_ntpn_pph;
            $ppn = $quo_inv->file_ntpn_ppn;
        }
        $data=[
            'npwp'              => $request->npwp,
            'no_ntpn'           => $request->no_ntpn,
            'npwp_nama'         => $request->npwp_nama,
            'no_ntpn_pph'       => $request->no_ntpn_pph,
            'potongan_ntpn_pph' => $request->potongan_ntpn_pph,
            'no_ntpn_ppn'       => $request->no_ntpn_ppn,
            'potongan_ntpn_ppn' => $request->potongan_ntpn_ppn,
            'file_ntpn_ppn'     => $ppn,
            'file_ntpn_pph'     => $pph,
        ];
        $qry1 = QuotationInvoice::where('id', $request->id_inv)->update($data);
        return redirect($redirect)->with('success', 'Updated Successfully');
    }
    
    

    public function edit_invoice_mid(Request $request)
    {
        // dd($request);
        $quo_in     = QuotationInvoice::where('id', $request->id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $pay        = QuotationInvoicePayment::where([['id', $request->id_pay],['id_quo_inv', $request->id]])->first();
        $detail     = QuotationInvoicePaymentDetail::where('id_dtl_payment', $request->id_pay)->get();
        // dd($detail, $pay);
        $count      = $request->count;
        return view('finance.invoice.others.edit_mid',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'pay'        => $pay,
            'bank'       => $this->getBank(),
            'detail'     => $detail,
            'redirect'   => 'finance/invoice/edit_invoice/'.$quo_in->id,
        ]);
    }


    public function SaveEditMid(Request $request)
    {   
        // dd($request);
        $quo_in   = QuotationInvoice::where('id', $request->id)->first();
        $dtl      = QuotationInvoicePaymentDetail::where('id_quo_inv', $quo_in->id)->get();
        $pay      = QuotationInvoicePayment::where('id_quo_inv' , $quo_in->id)->get()->count();
        $bank     = PurchaseFinanceBank::where('id', $request->bank_name)->first();
        $arr      = $request->payment_amounts;
        $arr_add  = $request->payment_amounts_add;

        $payment = [
                'id_quo'         => $request->id_quo,
                'id_quo_inv'     => $request->id,
                'method_payment' => $request->method_payment,
                'bank_id'        => $request->bank_name,
                'bank_name'      => $bank->nama_bank,
                'bank_no'        => $bank->no_rek,
                'created_at'     => Carbon::now(),
                'created_by'     => Auth::id(),
        ];
        $pays = QuotationInvoicePayment::where([['id_quo_inv', $quo_in->id],['id', $request->id_pay]])->update($payment);
        
        
        //save detail
        if($payment)
        {
        if($request->has('payment_amounts'))
            {
                foreach ($arr as $arrs=>$l){
                    $inv_dtl =[
                    'id_quo'         => $request->id_quo,
                    'id_quo_inv'     => $request->id,
                    'date_payment'   => $request->date_payment[$arrs],
                    'payment_amount' => $request->payment_amounts[$arrs],
                    'file_invoice'   => !empty($request->file('files')[$arrs]) ?  Storage::disk('public')->putFileAs('new_finance/invoicing', $request->file('files')[$arrs], rand().' '.$request->file('files')[$arrs]->getClientOriginalName()) : $dtl[$arrs]->file_invoice,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                    ];
                $parsial = QuotationInvoicePaymentDetail::where('id', $request->id_inv[$arrs])->update($inv_dtl);
                }
            }

        if($request->has('payment_amounts_add'))
            {
                foreach ($arr_add as $cr=>$l){
                    $up_inv_dtl =[
                    'id_quo'         => $request->id_quo,
                    'id_quo_inv'     => $request->id,
                    'id_dtl_payment' => $request->id_pay,
                    'date_payment'   => $request->date_payment_add[$cr],
                    'payment_amount' => $request->payment_amounts_add[$cr],
                    'file_invoice'   => !empty($request->file('files_add')[$cr]) ?  Storage::disk('public')->putFileAs('new_finance/invoicing', $request->file('files_add')[$cr], rand().' '.$request->file('files_add')[$cr]->getClientOriginalName()) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                    ];
                }
                $parsial = QuotationInvoicePaymentDetail::create($up_inv_dtl);
            }
        }
        // dd($payment);
        $data = [
            'type_payment'   => $request->type_payment,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now(),
        ];
        $qrys = QuotationInvoice::where('id', $request->id)->update($data);

        $act =[
            'activity_name'         => "Update Data Pembayaran Invoice",
            'activity_id_user'      => Auth::id(),
            'activity_id_quo'       => $quo_in->id_quo,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
            ];

        $act_fin =[
            'activity_name'         => "Update Data Pembayaran Invoice",
            'activity_user'         => getUserEmp(Auth::id())->id,
            'activity_refrensi'     => $quo_in->id,
            'status_activity'       => "Invoicing",
            'created_at'            => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'            => Auth::id(),
            ];
        $qry5 = FinanceHistory::create($act_fin);
        $qry2 = ActQuoModel::insert($act);
        
        return redirect($request->redirect)->with('success', 'Updated Successfully');
    }


    public function create_invoice_mid(Request $request)
    {
        // dd($request);
        $quo_in     = QuotationInvoice::where('id', $request->id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $count      = $request->count;
        return view('finance.invoice.others.create_mid',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'bank'       => $this->getBank(),
            'action'     => 'Finance\InvoicingController@SaveCreateMid',   
            'method'     => 'post',
            'redirect'   => 'finance/invoice/edit_invoice/'.$quo_in->id,
        ]);
    }


    public function SaveCreateMid(Request $request)
    {   
        // dd($request);
        $quo_in   = QuotationInvoice::where('id', $request->id)->first();
        $bank     = PurchaseFinanceBank::where('id', $request->bank_name)->first();
        $arr      = $request->payment_amounts;
        $arr_add  = $request->payment_amounts_add;
        
        //save detail
        $payment = [
                'id_quo'         => $request->id_quo,
                'id_quo_inv'     => $request->id,
                'method_payment' => $request->method_payment,
                'bank_name'      => $bank->nama_bank,
                'bank_no'        => $bank->no_rek,
                'bank_id'        => $request->bank_name,
                'created_at'     => Carbon::now(),
                'created_by'     => Auth::id(),
        ];
        $pays = QuotationInvoicePayment::create($payment);
        
        if($pays)
        {
        if($request->has('payment_amounts'))
            {
                foreach ($arr as $arrs=>$l){
                    $inv_dtl =[
                    'id_quo'         => $request->id_quo,
                    'id_quo_inv'     => $request->id,
                    'id_dtl_payment' => $pays->id,
                    'date_payment'   => $request->date_payment[$arrs],
                    'payment_amount' => $request->payment_amounts[$arrs],
                    'file_invoice'   => !empty($request->file('files')[$arrs]) ?  Storage::disk('public')->putFileAs('new_finance/invoicing', $request->file('files')[$arrs], rand().' '.$request->file('files')[$arrs]->getClientOriginalName()) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                    ];
                }
                $parsial = QuotationInvoicePaymentDetail::create($inv_dtl);
            }
       
        if($request->has('payment_amounts_add'))
            {
                foreach ($arr_add as $cr=>$l){
                    $up_inv_dtl =[
                    'id_quo'         => $request->id_quo,
                    'id_dtl_payment' => $pays->id,
                    'id_quo_inv'     => $request->id,
                    'date_payment'   => $request->date_payment_add[$cr],
                    'payment_amount' => $request->payment_amounts_add[$cr],
                    'file_invoice'   => !empty($request->file('files_add')[$cr]) ?  Storage::disk('public')->putFileAs('new_finance/invoicing', $request->file('files_add')[$cr], rand().' '.$request->file('files_add')[$cr]->getClientOriginalName()) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                    ];
                }
                $parsial = QuotationInvoicePaymentDetail::create($up_inv_dtl);
            }
        }
        // dd($payment);
        $data = [
            'type_payment'   => $request->type_payment,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now(),
        ];
        $qrys = QuotationInvoice::where('id', $request->id)->update($data);

        $act =[
            'activity_name'         => "Update Pembayaran / Data Invoice",
            'activity_id_user'      => Auth::id(),
            'activity_id_quo'       => $quo_in->id_quo,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
            ];

        $act_fin =[
            'activity_name'         => "Update Pembayaran / Data Invoice",
            'activity_user'         => getUserEmp(Auth::id())->id,
            'activity_refrensi'     => $request->id,
            'status_activity'       => "Invoicing",
            'created_at'            => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'            => Auth::id(),
            ];
        $qry5 = FinanceHistory::create($act_fin);
        $qry2 = ActQuoModel::insert($act);
        
        return redirect($request->redirect)->with('success', 'Created Successfully');
    }
    


    public function detail_invoice_mid(Request $request)
    {
        // dd($request);
        $quo_in     = QuotationInvoice::where('id', $request->id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $pay        = QuotationInvoicePayment::where([['id', $request->id_pay],['id_quo_inv', $request->id]])->first();
        $detail     = QuotationInvoicePaymentDetail::where('id_dtl_payment', $request->id_pay)->get();
        // dd($request, $pay, $detail);
        return view('finance.invoice.others.detail_payment_mid',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'pay'        => $pay,
            'detail'     => $detail,
        ]);
    }


    public function hapus_invoice_mid(Request $request)
    {
        // dd($request);
        $quo_in = QuotationInvoicePayment::where('id', $request->id_pay)->first();
        DB::table('quotation_invoice_payment')->where('id', $request->id_pay)->delete();
        DB::table('quotation_invoice_payment_detail')->where('id_dtl_payment', $request->id_pay)->delete();
        
        $act =[
            'activity_name'         => "Mengapus Data Pembayaran Invoice",
            'activity_id_user'      => Auth::id(),
            'activity_id_quo'       => $quo_in->id_quo,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
            ];

        $act_fin =[
            'activity_name'         => "Mengapus Data Pembayaran Invoice",
            'activity_user'         => getUserEmp(Auth::id())->id,
            'activity_refrensi'     => $quo_in->id_quo_inv,
            'status_activity'       => "Invoicing",
            'created_at'            => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'            => Auth::id(),
            ];
        $qry5 = FinanceHistory::create($act_fin);
        $qry2 = ActQuoModel::insert($act);
        return redirect()->back()->with('success', 'Deleted Successfully');
    }  

    
    public function remove_rows(Request $request)
    {
        DB::table('quotation_invoice_payment_detail')->where('id', $request->id)->delete();
        return response()->json(
        [
        'success'   => true,
        'n_equ'     => $request->n_equ,
        ]);
    }

    

    public function add_rows(Request $request){
        $n_equ = $request->n_equ;
        return view('finance.invoice.others.add_rowtbl',[
            'n_equ' => $n_equ,
        ]);
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


public function remove_potongan(Request $request){
        DB::table('quotation_invoice_othercost')->where('id', $request->id)->delete();
        return response()->json(
        [
        'success'   => true,
        ]);

}


    public function edit_invoice_last(Request $request)
    {
        // dd($request);
        $quo_in     = QuotationInvoice::where('id', $request->id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $inv_other  = QuotationInvoiceOthers::where('id_quo_inv', $request->id)->get();
        $coth       = count($inv_other);
        return view('finance.invoice.others.edit_last',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'no_so'      => 'SO'.sprintf('%06d',$quo_in->id_quo),
            'inv_oth'    => $inv_other,
            'coth'       => $coth,
        ]);
    }


        public function editplus_invoice_last(Request $request)
    {
        $quo_in     = QuotationInvoice::where('id', $request->id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $inv_other  = QuotationInvoiceOthers::where('id_quo_inv', $request->id)->get();
        return view('finance.invoice.others.tambah_potongan_last',[
            'invoice_id' => $quo_in,
            'quo_mo'     => $quo_mo,
            'no_so'      => 'SO'.sprintf('%06d',$quo_in->id_quo),
        ]);
    }

    public function add_forms(Request $request){
        $n_equ = $request->n_equ;
        return view('finance.invoice.others.add_form',[
            'n_equ' => $n_equ,
        ]);
    }

    public function SaveEditLast(Request $request)
    {
        // dd($request);
        $desk    = $request->des_potongan;
        $cdata   = QuotationInvoiceOthers::where('id_quo_inv', $request->id_inv)->get()->count();
        $creq    = $request->nilai_potongan==null ? null  : count($request->nilai_potongan);
        if($cdata>$creq || $cdata<$creq){
        foreach($desk as $req => $n){
        $potongan = [
            'id_quo'         => $request->id_quo,
            'id_quo_inv'     => $request->id_inv,
            'des_potongan'   => $request->des_potongan[$req],
            'nilai_potongan' => $request->nilai_potongan[$req],
            'created_by'     => Auth::id(),
            'created_at'     => Carbon::now(),
            ];
        }
            $sv = QuotationInvoiceOthers::insert($potongan);
        }
        else if($cdata==0 && $cdata>$creq || $cdata==0 && $cdata<$creq){
        foreach($desk as $req => $n){
        $potongan = [
            'id_quo'         => $request->id_quo,
            'id_quo_inv'     => $request->id_inv,
            'des_potongan'   => $request->des_potongan[$req],
            'nilai_potongan' => $request->nilai_potongan[$req],
            'created_by'     => Auth::id(),
            'created_at'     => Carbon::now(),
        ];
        $sv = QuotationInvoiceOthers::create($potongan);
        }
    }
    else if($request->des_potongan==null){
        return redirect ('finance/invoice/edit_invoice/'.$request->id_inv)->with('success', 'Updated and Deleted Data Successfully');
    }
        else{
        foreach($desk as $req => $n){
        $potongans = [
            'id_quo'         => $request->id_quo,
            'id_quo_inv'     => $request->id_inv,
            'des_potongan'   => $request->des_potongan[$req],
            'nilai_potongan' => $request->nilai_potongan[$req],
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now(),
        ];
        $sv = QuotationInvoiceOthers::where('id', $request->id_oth[$req])->update($potongans);
        }
    }
        return redirect ('finance/invoice/edit_invoice/'.$request->id_inv)->with('success', 'Updated Successfully');
    }


    public function finish_payment(Request $request)
    {
        $id = $request->segment(4);
        $data = [
            'ket_lunas' => "Finish",
            'tgl_lunas' => Carbon::now(),
        ];
        $qry = QuotationInvoice::where('id', $id)->update($data);
        return redirect('finance/invoice/edit_invoice/'.$id)->with('success', 'Saving Payment Successfully');
    }
    
    public function cetak_invoicing (Request $request)
    {
        $inv     = QuotationInvoice::where('id', $request->id)->first();
        $quo_mo  = QuotationModel::where('id', $inv->id_quo)->first();
        $inv_dtl = QuotationInvoicePaymentDetail::where('id_quo_inv', $request->id)->get();
        $payment = QuotationInvoicePayment::where('id_quo_inv', $request->id)->get();
        $date_pay= QuotationInvoicePaymentDetail::where('id_quo_inv', $request->id)->orderBy('id', 'desc')->first();
        $inv_oth = QuotationInvoiceOthers::where('id_quo_inv', $request->id)->where('des_potongan', 'LIKE', '%PPH%')
                                            ->orWhere([['des_potongan', 'LIKE', '%PPN%'], ['des_potongan', 'LIKE', "%VAT%"]])->get();
        $pay_other = QuotationInvoiceOthers::where('id_quo_inv', $request->id)->get();
        $pph     = QuotationInvoiceOthers::where('id_quo_inv', $request->id)->where('des_potongan', 'LIKE', '%PPH%')->first();
        $ppn     = QuotationInvoiceOthers::where('id_quo_inv', $request->id)
                                            ->Where('des_potongan', 'LIKE', '%PPN%')
                                            ->orWhere('des_potongan', 'LIKE', "%VAT%")
                                            ->first();
        $quo_doc = QuotationDocument::where('id_quo', $inv->id_quo)->first();
        $pay     = QuotationInvoicePayment::where('id_quo_inv', $request->id)->orderBy('id', 'desc')->first();
        $cdtl    = count($inv_dtl);
        $coth    = count($pay_other);
        // dd($coth);
        $dos     = WarehouseOut::join('warehouse_outbound_detail','warehouse_outbound.id', '=', 'warehouse_outbound_detail.id_outbound')
                    ->where('id_quo', $inv->id_quo)->first();
        //join
        $inv_payment = QuotationInvoicePayment::join('quotation_invoice_payment_detail as q', 'q.id_quo_inv', '=', 'quotation_invoice_payment.id_quo_inv')
                       ->where('quotation_invoice_payment.id_quo_inv', $request->id)->get();
        $dtl     = QuotationInvoicePaymentDetail::where('id_quo_inv', $request->id)->get()->sum('payment_amount');
        $other   = QuotationInvoiceOthers::where('id_quo_inv', $request->id)->get()->sum('nilai_potongan');
        $alltotal= ($dtl + $other);
        $quo_pro = QuotationProduct::select('*', DB::raw('SUM(det_quo_harga_order*det_quo_qty) as subtotal'), DB::raw('SUM(det_quo_harga_modal*det_quo_qty) as sub_modal'))
                      ->join('quotation_models', 'quotation_product.id_quo','=', 'quotation_models.id')
                      ->where('quotation_models.id', $inv->id_quo)->first();

        $hasil      = getPriceInvoice($inv->id_quo);
        $ttl        = $hasil['total'];
        $vats       = $hasil['VAT'];
        $dpp        = $hasil['total'] - $hasil['ppn'];

        $sisa       = $coth==0 ? ($ttl - $dtl - $inv->potongan_ntpn_ppn - $inv->potongan_ntpn_pph) : ($quo_mo->quo_price - $dtl - $other - $inv->potongan_ntpn_ppn - $inv->potongan_ntpn_pph); 
        $pdf        = PDF::loadview('pdf.finance_cetak_invoicing',[
            'inv'     => $inv,
            'inv_dtl' => $inv_dtl,
            'inv_oth' => $inv_oth,
            'other'   => $pay_other,
            'payment' => $payment,
            'cdtl'    => $cdtl,
            'dpp'     => $dpp,
            'modal'   => $quo_pro->sub_modal,
            'sumtotal'=> $ttl,
            'arr_inv' => $inv_payment,
            'date_pay'=> $date_pay,
            'quo_doc' => $quo_doc,
            'usr'     => getUserEmp(Auth::id())->emp_name,
            'pay'     => $pay,
            'dtl'     => $dtl,
            'alltotal'=> $alltotal,
            'ppn'     => $ppn,
            'vats'    => $vats,
            'dos'     => $dos,
            'pph'     => $pph,
            'coth'    => $coth,
            'sisa'    => $sisa,
            'quo_mo'  => $quo_mo,
            'time'    => Carbon::now('GMT+7')->format('d F Y')
        ]);
    	return $pdf->download('DOC-INVOICING'.' '.Carbon::now()->format('d/m/Y').'.pdf');        
    }
    

public function saveUpdate(Request $request)
    {
        $done     = $request->checks== "on"? "Finish" : null;
        $arr      = $request->payment_amounts;
        $quo_in   = QuotationInvoice::where('id', $request->id)->first();
        $dtl      = QuotationInvoiceDetail::where('id_quo_inv', $quo_in->id)->get();
        $redirect = $request->type == "fromadmin" ? redirect('sales/quotation/'.$request->hide_id_quo)->with('success', 'Updated Invoice Successfully'):  redirect('finance/invoice')->with('success', 'Updated '.$request->id_quo.' Successfully');
        $act_name = $request->type == "fromadmin" ? "Edit data Invoice": "Update Pembayaran / Data Invoice";
        
        if($arr!=null){
        $cdtl           = QuotationInvoiceDetail::where('id_quo_inv', $quo_in->id)->get()->count();
        $creq           = count($request->payment_amounts);
        $payment_amount = $request->type_payment== "parsial" ? end($arr) : $request->payment_amounts[0];
        }
        $data=[
            'no_invoice'      => $request->no_invoice,
            'id_quo'          => $request->hide_id_quo,
            'npwp'            => $request->npwp,
            'type_payment'    => $request->type_payment,
            'npwp_alamat'     => $request->npwp_alamat,
            'nama_bank'       => $request->nama_bank,
            'total_payment'   => $request->quo_price,
            'no_ntpn'         => $request->no_ntpn,
            'payment_amount'  => $arr!=null ? $payment_amount : null,
            'pot_biaya_bank'  => $request->pot_biaya_bank,
            'potongan_ppn'    => $request->potongan_ppn,
            'potongan_pph'    => $request->potongan_pph,
            'note'            => $request->note,
            'selisih_payment' => $request->selisih,
            'ket_lunas'       => $done,
        ];
        $qry1 = QuotationInvoice::where('id', $request->id)->update($data);
        if($request->type_payment == "parsial")
        {
        if ($creq>$cdtl || $creq<$cdtl){
            foreach ($arr as $arrs=>$l){
            $inv_dtl =[
            'id_quo'         => $request->hide_id_quo,
            'id_quo_inv'     => $request->id,
            'date_payment'   => $request->date_payment[$arrs],
            'payment_amount' => $request->payment_amounts[$arrs],
            'created_at'     => Carbon::now(),
            'created_by'     => Auth::id(),
            ];
        }
        $parsial = QuotationInvoiceDetail::create($inv_dtl);
    }else if($cdtl==0 && $creq>$cdtl || $cdtl==0 && $creq<$cdtl){
            foreach ($arr as $arrs=>$l){
            $inv_dtl =[
            'id_quo'         => $request->hide_id_quo,
            'id_quo_inv'     => $request->id,
            'date_payment'   => $request->date_payment[$arrs],
            'payment_amount' => $request->payment_amounts[$arrs],
            'created_at'     => Carbon::now(),
            'created_by'     => Auth::id(),
            ];
     $parsial = QuotationInvoiceDetail::create($inv_dtl);
        }
    }
    else{
            foreach ($arr as $arrs=>$l){
            $inv_dtl =[
            'id_quo'         => $request->hide_id_quo,
            'id_quo_inv'     => $request->id,
            'date_payment'   => $request->date_payment[$arrs],
            'payment_amount' => $request->payment_amounts[$arrs],
            'created_at'     => Carbon::now(),
            'created_by'     => Auth::id(),
            ];
        $parsial = QuotationInvoiceDetail::where('id', $request->inv_dtl[$arrs])->update($inv_dtl);
            }
        }
    } else if($request->type_payment=="full"){
            $quodtl = QuotationInvoiceDetail::where('id_quo_inv', $request->id)->get();
            $d = count($quodtl)!=0? "Update" : "Create";
            
            foreach ($arr as $arrs=>$l){
            $inv_dtl =[
            'id_quo'         => $request->hide_id_quo,
            'id_quo_inv'     => $request->id,
            'date_payment'   => $request->date_payment[$arrs],
            'payment_amount' => $request->payment_amounts[$arrs],
            'created_at'     => Carbon::now(),
            'created_by'     => Auth::id(),
            ];
        $parsial = $d=="Create" ? QuotationInvoiceDetail::create($inv_dtl) : QuotationInvoiceDetail::where('id_quo_inv', $request->id)->update($inv_dtl);
            }
        }
        
        if($qry1)
        {
        $act_name = $request->type == "fromadmin" ? "Edit data Invoice": "Update Pembayaran / Data Invoice";
            $act =[
            'activity_name'         => $act_name,
            'activity_id_user'      => Auth::id(),
            'activity_id_quo'       => $request->hide_id_quo,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
            ];

        $act_fin =[
            'activity_name'         => $act_name,
            'activity_user'         => getUserEmp(Auth::id())->id,
            'activity_refrensi'     => $request->hide_id_quo,
            'status_activity'       => "Invoicing",
            'created_at'            => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'            => Auth::id(),
            ];
        $qry5 = FinanceHistory::create($act_fin);
        $qry2 = ActQuoModel::insert($act);
        
    } if($qry2){
        return $redirect;
    }
}

public function removes(Request $request)
    {
        DB::table('quotation_invoice_detail')->join('quotation_invoice', 'quotation_invoice.id', '=', 'quotation_invoice_detail.id_quo_inv')
        ->where('quotation_invoice_detail.id', $request->id)->delete();
        $quo_mo  = QuotationModel::where('id', $request->id_quo)->first();
        $dtl     = QuotationInvoiceDetail::where('id_quo_inv', $request->id_quo_inv)->get()->sum('payment_amount');
        $sisa    = ($quo_mo->quo_price - $dtl);
        
        return response()->json(
        [
        'success'   => true,
        'sum'       => $sisa,
        ]);
    }

    
public function nextpayment(Request $request)
    {
        $quo_in = QuotationInvoice::where('id', $request->id_inv)->first();
        $quo_mo = QuotationModel::where('id', $quo_in->id_quo)->first();
        return view('finance.invoice.next_payment',[
            'invoice_id' => $quo_in,
            'n_equ'      => $request->n_equ,
            'quo_mo'     => $quo_mo,
        ]);
    }

public function show($id)
    {
        $quo_in    = QuotationInvoice::where('id', $id)->first();
        $quo_indtl = QuotationInvoiceDetail::where('id_quo_inv', $quo_in->id)->get();
        $quo_mo    = QuotationModel::where('id', $quo_in->id_quo)->first();
        return view('finance.invoice.show',[
            'inv'     => $quo_in,
            'inv_dtl' => $quo_indtl,
            'quo'     => $quo_mo,
        ]);
    }


    public function filter_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'id_quo',
            2 => 'tgl_invoice',
            3 => 'created_by',
            4 => 'note',
            5 => 'no_invoice',
            6 => 'quo_no',
        );
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        
        $id_quo   = $request->segment(4);
        $st_date  = $request->segment(5);
        $end_date = $request->segment(6);
        $k_lunas  = $request->segment(7)=="yes" ? 'Finish' : "null";
        //

        $menu_count    = QuotationInvoice::filtersearch($id_quo, $st_date, $end_date, $k_lunas);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationInvoice::filtersearchlimit($id_quo, $st_date, $end_date, $k_lunas, $start, $limit, $order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationInvoice::filtersearchfind($id_quo, $st_date, $end_date, $k_lunas, $start, $limit, $order, $dir, $search)->get();
            $totalFiltered = count(QuotationInvoice::filtersearchfind($id_quo, $st_date, $end_date, $k_lunas, $start, $limit, $order, $dir, $search)->get());
        }
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
            $check = $post->quo_ekskondisi!='Batal' ? 'allow' : 'no';
            $check2= $post->quo_approve_status!='reject' ? 'allow' : 'no';
                if($check=='allow' && $check2 == 'allow')
                {
                    if($post->type_payment!=null)
                    {
                        if($post->type_payment == "parsial")
                        {
                            $notes = "Parsial";
                        }else if($post->type_payment == "full"){
                            $notes = "Full";
                        }else{
                            $notes = "Unpaid";
                        }
                    }else{
                            $notes = "Unpaid";
                    }

                    if($post->ket_lunas == "Finish")
                    {
                        if($post->tgl_lunas==null)
                        {
                            $ket = " - Lunas";
                        }else{
                            $ket = " - Lunas ".Carbon::parse($post->tgl_lunas)->format('d F Y');
                        }
                    }else{
                        $ket = " ";
                    }

                    $quo_pro    = QuotationProduct::select(DB::raw('det_quo_harga_order*det_quo_qty as subtotal'))
                              ->join('quotation_models', 'quotation_product.id_quo','=', 'quotation_models.id')
                              ->where('quotation_models.id', $post->id_quo)->get()->sum('subtotal');
                    $hasil      = getPriceInvoice($post->id_quo);
                    $data[] = [
                            'no_invoice'  => $post->no_invoice,
                            'id_quo'      => "SO".sprintf("%06d", $post->id_quo),
                            'tgl_invoice' => $post->tgl_invoice,
                            'note'        => $post->note,
                            'ket_lunas'   => $notes." ".$ket,
                            'lunas'       => $post->ket_lunas,
                            'payments'    => number_format(sumInvoicePaid($post->id)),
                            'amount'      => number_format(SisaHargaInvoice($post->id)),                    
                            'notes'       => $notes,
                            'price'       => $hasil['total'],
                            'created_by'  => user_name($post->created_by),
                            'id'          => $post->id,
                            'quo_no'      => $post->quo_no,
                        ];
                }
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => count($data),
            "recordsFiltered" => count($data),
            "data"            => $data
        );

        echo json_encode($json_data);
    } 
}