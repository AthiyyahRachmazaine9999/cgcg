<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Finance\Pay_VoucherModel;
use App\Models\Finance\Pay_VoucherApp;
use App\Models\Finance\Pay_VoucherDetail;
use App\Models\Finance\Pay_VoucherDokumen;
use App\Models\Finance\FinanceHistory;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationModel;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Purchasing\PurchaseFinanceBank;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use App\Models\Sales\CustomerModel;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\warehouse_out;
use App\Models\Warehouse\Warehouse_address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DB;
use PDF;
use Storage;

class Pay_VoucherController extends Controller
{
    public function index()
    {
        return view('finance.payment_voucher.index',[
            'main' => getUserEmp(Auth::id()),
        ]);
    }

    public function destroy($id)
    {
        $query='DELETE finance_pay_voucher, finance_pay_voucher_detail
        FROM finance_pay_voucher JOIN finance_pay_voucher_detail on finance_pay_voucher_detail.id_pay = finance_pay_voucher.id WHERE finance_pay_voucher.id = ?';
        DB:: delete($query, array($id));

        return redirect()->back()->with('success', 'Deleted Successfully');
    }
    
    public function create()
    {
        $vendor = VendorModel::all();
        
        return view('finance.payment_voucher.create',[
            'vendor' => $this->get_Vendor(),
            'no_po'  => $this->getNO_PO(),
            'no_so'  => $this->get_SO(),
            'cust'   => $this->get_Customer(),
        ]);
    }

    public function edit($id)
    {
         $pay     = Pay_VoucherModel::where('id', $id)->first();
         $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
         $vendor  = VendorModel::all();
         return view('finance.payment_voucher.from_po.edit',[
            'vendor'  => $this->AllVendor(),
            'no_po'   => $this->getNO_PO(),
            'pay'     => $pay,
            'pay_dtl' => $pay_dtl,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $return  = $request->mark!="FromFinance" ? redirect('purchasing/order/'.$request->no_po)->with('success', 'Created Payment Voucher Successfully')
        : redirect('finance/payment_voucher')->with('success', 'Created Payment Voucher Successfully');
        $month   = Carbon::now()->format('m');
        $year    = Carbon::now()->format('Y');
        $files   = $request->file('doc_alltop');
        $inv     = $request->file('doc_inv_performa');
        $other   = $request->file('doc_lainnya');
        $no_do   = $request->payment=="top" || $request->payment=="net" ? $request->no_do : null;
        $voucher = [
            'id_vendor'  => $request->vendor_id,
            'tujuan'     => 'Payment '.$request->no_po,
            'nominal'    => $request->total,
            'top_date'   => $request->top_date,
            'created_by' => Auth::id(),
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry1 = Pay_VoucherModel::create($voucher);
        if($qry1)
        {
            $voucher_detail = [
                'id_vendor'        => $request->vendor_id,
                'id_pay'           => $qry1->id,
                'id_quo'           => $request->id_quo,
                'no_do'            => $request->no_do,
                'status'           => "Pending",
                'tujuan'           => "Payment ".$request->no_po,
                'from_date'        => $request->from_date,
                'terbilang'        => terbilang($request->total),
                'no_invoice'       => $request->no_invoice,
                'no_po'            => $request->no_po,
                'no_so'            => $request->no_so,
                'top_date'         => $request->top_date,
                'to_date'          => $request->to_date,
                'type_payment'     => $request->payment,
                'nominal'          => $request->total,
                // 'no_payment'       => $qry1->id.'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
                'no_efaktur'       => $request->no_faktur,
                'performa_invoice' => $request->no_peforma_inv,
                'doc_alltop'       => $request->has('doc_alltop') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_alltop'), $files->getClientOriginalName()) : null,
                'doc_inv_performa' => $request->has('doc_inv_performa') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_inv_performa'), $inv->getClientOriginalName()) : null,
                'doc_lainnya'      => $request->has('doc_lainnya') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_lainnya'), $other->getClientOriginalName()) : null,
                'created_by'       => Auth::id(),
                'created_at'       => Carbon::now('GMT+7')->toDateTimeString(),
     ];
        $qry2 = Pay_VoucherDetail::create($voucher_detail);
        }
        if($qry2){
            $fin_hist =[
                'activity_name'     => "Menambahkan Payment Voucher",
                'activity_user'     => getUserEmp(Auth::id())->id,
                'status_activity'   => "Payment Voucher",
                // 'activity_refrensi' => $qry2->no_payment,
                'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'        => Auth::id(),
            ];
        $qry3= FinanceHistory::create($fin_hist);
        }
        return $return;
    }
    
    public function update(Request $request)
    {
        return $this->saveUpdate($request)->with('success','Updated Succesfully');
    }

    public function saveUpdate($request, $id=0)
    {
        $return  = $request->mark!="FromFinance" ? redirect('purchasing/order/'.$request->no_po)->with('success', 'Created Payment Voucher Successfully')
        : redirect('finance/payment_voucher')->with('success', 'Created Payment Voucher Successfully');
        $month   = Carbon::now()->format('m');
        $year    = Carbon::now()->format('Y');
        $detail  = Pay_VoucherDetail::where('id_pay',$request->id_pay)->first();
        $model   = Pay_VoucherModel::where('id',$request->id_pay)->first();
        $no_do   = $request->payment=="top" || $request->payment=="net" ? $request->no_do : null;
        $files   = $request->file('doc_alltop');
        $inv     = $request->file('doc_inv_performa');
        $other   = $request->file('doc_lainnya');
        $voucher = [
            'id_vendor'  => $request->vendor_id,
            'tujuan'     => 'Payment '.$request->no_po,
            'nominal'    => $request->total,
            'top_date'   => $request->top_date,
            'created_by' => Auth::id(),
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry1 = Pay_VoucherModel::where('id',$request->id_pay)->update($voucher);
        if($qry1)
        {
            $voucher_detail = [
                'id_vendor'        => $request->vendor_id,
                'id_pay'           => $request->id_pay,
                'id_quo'           => $request->id_quo,
                'no_do'            => $request->no_do,
                'status'           => "Pending",
                'tujuan'           => 'Payment '.$request->no_po,
                'from_date'        => $request->from_date,
                'terbilang'        => terbilang($request->total),
                'no_invoice'       => $request->no_invoice,
                'no_po'            => $request->no_po,
                'no_so'            => $request->no_so,
                'top_date'         => $request->top_date,
                'to_date'          => $request->to_date,
                'type_payment'     => $request->payment,
                'nominal'          => $request->total,
                // 'no_payment'       => $model->id.'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
                'no_efaktur'       => $request->no_faktur,
                'performa_invoice' => $request->no_peforma_inv,
                'doc_alltop'       => $request->has('doc_alltop') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_alltop'), $files->getClientOriginalName()) : $detail->doc_alltop,
                'doc_inv_performa' => $request->has('doc_inv_performa') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_inv_performa'), $inv->getClientOriginalName()) : $detail->doc_inv_performa,
                'doc_lainnya'      => $request->has('doc_lainnya') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_lainnya'), $other->getClientOriginalName()) : $detail->doc_lainnya,
                'updated_by'       => Auth::id(),
                'updated_at'       => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $request->id_pay)->update($voucher_detail);
        }    
        if($qry2){
            $fin_hist =[
                'activity_name'     => "Mengubah Data Payment Voucher",
                'activity_user'     => getUserEmp(Auth::id())->id,
                'status_activity'   => "Payment Voucher",
                'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'        => Auth::id(),
            ];
        $qry3= FinanceHistory::create($fin_hist);
        }
        return $return;
    }


public function done_payment(Request $request)
{
    $pay    = Pay_VoucherModel::where('id', $request->id)->first();
    $id_dtl = Pay_VoucherDetail::where('id_pay', $pay->id)->first();
    return view('finance.payment_voucher.form_done_payment',[
        'pay'    => $pay,
        'bank'   => $this->getBank(),
        'dtl'    => $id_dtl,
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



public function save_donePayment(Request $request)
{
    // dd($request);
    $dtl = Pay_VoucherDetail::where('id_pay', $request->id_pay)->first();
    if ($request->has('file_payment')) {
        if(!empty($request->file('file_payment'))){
            $files    = $request->file('file_payment');
            $name     = rand()." ".$files->getClientOriginalName();
            $newName  = Storage::disk('public')->putFileAs('file_finance', $files, $name);
        }
    }else {
        $newName=$dtl->file_payment;
    }
    $data = [
        'status'         => "Done Payment",
        'file_payment'   => $newName,
        'bank_payment'   => $request->bank_name,
        'note_payment'   => $request->note_payment,
        'nominal_payment'=> $request->nominal_payment,
        'tgl_payment'    => $request->tgl_payment,
        'updated_by'     => Auth::id(),
        'updated_at'     => Carbon::now(),
    ];
    $fin_hist =[
        'activity_name'     => "Done Payment",
        'status_activity'   => "Payment Voucher",
        'activity_user'     => getUserEmp(Auth::id())->id,
        'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
        'activity_refrensi' => $dtl->id_pay,
        'created_by'        => Auth::id(),
    ];
    $qry = Pay_VoucherDetail::where('id_pay', $request->id_pay)->update($data);
    $qry3 = FinanceHistory::create($fin_hist);
    return redirect('finance/payment_voucher')->with('success', 'Done Payment Successfully');
}


public function add_note(Request $request)
{
    $pay    = Pay_VoucherModel::where('id', $request->id)->first();
    $id_dtl = Pay_VoucherDetail::where('id_pay', $pay->id)->first();
    return view('finance.payment_voucher.form_addnote',[
        'pay'    => $pay,
        'dtl'    => $id_dtl,
    ]);
}


public function save_add_note(Request $request)
{
    $dtl = Pay_VoucherDetail::where('id_pay', $request->id_pay)->first();
    if ($request->has('note_file_pph')) {
        if(!empty($request->file('note_file_pph'))){
            $files    = $request->file('note_file_pph');
            $name     = rand()." ".$files->getClientOriginalName();
            $newName  = Storage::disk('public')->putFileAs('file_finance', $files, $name);
        }
    }else {
        $newName=$dtl->note_file_pph;
    }
    $data = [
        'note_file_pph'     => $newName,
        'note_pph'          => $request->note_pph,
        'note_nominal_pph'  => $request->pilih_nominal=="Persen" ? $request->persen : $request->note_nominal_pph,
        'note_transfer_pph' => $request->note_transfer_pph,
    ];
    $fin_hist =[
        'activity_name'     => "Add Note : ".$request->note_pph,
        'status_activity'   => "Payment Voucher",
        'activity_user'     => getUserEmp(Auth::id())->id,
        'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
        'activity_refrensi' => $dtl->id_pay,
        'created_by'        => Auth::id(),
    ];
    $qry = Pay_VoucherDetail::where('id_pay', $request->id_pay)->update($data);
    $qry3 = FinanceHistory::create($fin_hist);
    return redirect('finance/payment_voucher/'.$request->id_pay."/show_finance")->with('success', 'Done Payment Successfully');
}


public function add_files(Request $request)
{
    $pay    = Pay_VoucherModel::where('id', $request->id)->first();
    $id_dtl = Pay_VoucherDetail::where('id_pay', $pay->id)->first();
    $dok    = Pay_VoucherDokumen::where('id_pay', $pay->id)->get();
    return view('finance.payment_voucher.form_add_file',[
        'pay'    => $pay,
        'dtl'    => $id_dtl,
        'dok'    => $dok,
    ]);
}


public function save_add_files(Request $request)
{
    // dd($request);
    $dtl = Pay_VoucherDetail::where('id_pay', $request->id_pay)->first();
    if ($request->has('file')) {
        if(!empty($request->file('file'))){
            $files    = $request->file('file');
            $name     = rand()." ".$files->getClientOriginalName();
            $newName  = Storage::disk('public')->putFileAs('file_finance/additional', $files, $name);
        }
    }else {
        $newName=$dtl->file_upload;
    }
    $data = [
        'id_pay'            => $request->id_pay,
        'file_upload'       => $newName,
        'nama_dokumen'      => $request->nama_dokumen,
        'created_at'        => Carbon::now(),
        'created_by'        => Auth::id(),
    ];
    $fin_hist =[
        'activity_name'     => "Add File for Payment Voucher - ".$dtl->no_payment,
        'status_activity'   => "Payment Voucher",
        'activity_user'     => getUserEmp(Auth::id())->id,
        'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
        'activity_refrensi' => $dtl->id_pay,
        'created_by'        => Auth::id(),
    ];
    $qry = Pay_VoucherDokumen::create($data);
    $qry3 = FinanceHistory::create($fin_hist);
    return redirect('finance/payment_voucher/'.$request->id_pay."/show_finance")->with('success', 'Done Payment Successfully');
}




    public function filter_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'tujuan',
            2 => 'created_at',
            3 => 'created_by',
            4 => 'id_vendor',
            5 => 'status',
            6 => 'no_invoice',
            7 => 'no_efaktur',
            8 => 'performa_invoice',
            9 => 'id_quo',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $status = $request->segment(4);
        $sdate  = $request->segment(5);
        $edate  = $request->segment(6);
        
        $menu_count    = Pay_VoucherDetail::filtersearch($status, $sdate, $edate);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::filtersearchlimit($status, $sdate, $edate, $start, $limit, $order, $dir)->get(); 
        } else {
            $search        = $request->input('search')['value'];
            $posts         = Pay_VoucherDetail::filtersearchfind($status, $sdate, $edate, $start, $limit, $order, $dir, $search)->get();
            $totalFiltered = count(Pay_VoucherDetail::filtersearchfind($status, $sdate, $edate, $start, $limit, $order, $dir, $search)->get());
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if($post->id_vendor==null){
                    $vendor_cust= getCustomer($post->id_customer)->company;
                }else{
                    $vendor_cust= getVendor($post->id_vendor)->vendor_name;
                }

                if($post->no_po==null){
                    $link = "fromfinance";
                }else{
                    $link = "frompurchase";
                }

                $data[] = [
                    'tujuan'           => $post->tujuan,
                    'no_payment'       => $post->no_payment,
                    'id_vendor'        => $vendor_cust,
                    'created_at'       => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'link'             => $link,
                    'id'               => $post->id,
                    'user'             => "finance",
                    'quo_no'           => getQuo_No($post->id_quo),
                    'status'           => $post->status,
                    'app_hr'           => $post->app_hrd,
                    'app_finance'      => $post->app_finance,
                    'no_invoice'       => $post->no_invoice,
                    'no_efaktur'       => $post->no_efaktur,
                    'performa_invoice' => $post->performa_invoice,
                    'id_quo'           => $post->id_quo,
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



public function ajax_data(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $usr  = Auth::id();
        if($mine->division_id==3){
            return $this->ajax_finance($request);
        }
        else if($mine->division_id==8 || $mine->id==2 || $mine->division_id==7)
        {
            return $this->ajax_Management($request);
        }else{
        $columns = array(
            0 => 'id',
            1 => 'tujuan',
            2 => 'created_at',
            3 => 'created_by',
            4 => 'id_vendor',
            5 => 'status',
            6 => 'no_invoice',
            7 => 'no_efaktur',
            8 => 'performa_invoice',
            9 => 'id_quo',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Pay_VoucherDetail::where('finance_pay_voucher_detail.created_by', $usr)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::where('finance_pay_voucher_detail.created_by', $usr)
            ->orderby($order, $dir)->offset($start)->limit($limit)->get(); 
        } else {
            $search = $request->input('search')['value'];
            $posts  = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
            ->where('no_po', 'like', '%' . $search . '%')
            ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
            ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if($post->id_vendor==null){
                    $vendor_cust= getCustomer($post->id_customer)->company;
                }else{
                    $vendor_cust= getVendor($post->id_vendor)->vendor_name;
                }

                if($post->no_po==null){
                    $link = "fromfinance";
                }else{
                    $link = "frompurchase";
                }
                $vendors = $post->id_vendor==365 ? 'direksi' : 'no';
                $data[] = [
                    'tujuan'           => $post->tujuan,
                    'no_payment'       => $post->no_payment,
                    'id_vendor'        => $vendor_cust,
                    'created_at'       => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'               => $post->id,
                    'link'             => $link,
                    'user'             => "other",
                    'vendors'          => $vendors,
                    'nominal'          => $post->nominal,
                    'status'           => $post->status,
                    'no_invoice'       => $post->no_invoice,
                    'no_efaktur'       => $post->no_efaktur,
                    'app_hr'           => $post->app_hrd,
                    'app_finance'      => $post->app_finance,
                    'app_mng'          => $post->app_mng,
                    'performa_invoice' => $post->performa_invoice,
                    'quo_no'           => getQuo_No($post->id_quo),
                    'id_quo'           => $post->id_quo,
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

public function ajax_finance(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'tujuan',
            2 => 'created_at',
            3 => 'created_by',
            4 => 'id_vendor',
            5 => 'status',
            6 => 'no_invoice',
            7 => 'no_efaktur',
            8 => 'performa_invoice',
            9 => 'id_quo',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Pay_VoucherDetail::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NULL AND app_mng IS NULL) then "B" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NULL) then "C" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NOT NULL) then "D" when (status = "Completed") then "E" else "Z" end) as status_sort'))
             ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
        } else {
            $search = $request->input('search')['value'];
            $posts  = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
            ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
            ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if($post->id_vendor==null){
                    $vendor_cust= getCustomer($post->id_customer)->company;
                }else{
                    $vendor_cust= getVendor($post->id_vendor)->vendor_name;
                }

                if($post->no_po==null){
                    $link = "fromfinance";
                }else{
                    $link = "frompurchase";
                }
                $vendors = $post->id_vendor==365 ? 'direksi' : 'no';
                $data[] = [
                    'tujuan'           => $post->tujuan,
                    'no_payment'       => $post->no_payment,
                    'id_vendor'        => $vendor_cust,
                    'created_at'       => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'link'             => $link,
                    'id'               => $post->id,
                    'user'             => "finance",
                    'vendors'          => $vendors,
                    'nominal'          => $post->nominal,
                    'quo_no'           => getQuo_No($post->id_quo),
                    'status'           => $post->status,
                    'app_hr'           => $post->app_hrd,
                    'app_finance'      => $post->app_finance,
                    'app_mng'          => $post->app_mng,
                    'no_invoice'       => $post->no_invoice,
                    'no_efaktur'       => $post->no_efaktur,
                    'performa_invoice' => $post->performa_invoice,
                    'id_quo'           => $post->id_quo,
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
    
public function ajax_Management(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'tujuan',
            2 => 'created_at',
            3 => 'created_by',
            4 => 'id_vendor',
            5 => 'status',
            6 => 'no_invoice',
            7 => 'no_efaktur',
            8 => 'performa_invoice',
            9 => 'id_quo',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Pay_VoucherDetail::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NULL AND app_mng IS NULL) then "B" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NULL) then "C" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NOT NULL) then "D" when (status = "Completed") then "E" else "Z" end) as status_sort'))
             ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
        } else {
            $search = $request->input('search')['value'];
            $posts  = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
            ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
            ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if($post->id_vendor==null){
                    $vendor_cust= getCustomer($post->id_customer)->company;
                }else{
                    $vendor_cust= getVendor($post->id_vendor)->vendor_name;
                }

                if($post->no_po==null){
                    $link = "fromfinance";
                }else{
                    $link = "frompurchase";
                }
                $vendors = $post->id_vendor==365 ? 'direksi' : 'no';
                $data[] = [
                    'tujuan'           => $post->tujuan,
                    'no_payment'       => $post->no_payment,
                    'id_vendor'        => $vendor_cust,
                    'link'             => $link,
                    'created_at'       => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'               => $post->id,
                    'nominal'          => $post->nominal,
                    'user'             => "management",
                    'vendors'          => $vendors,
                    'status'           => $post->status,
                    'no_invoice'       => $post->no_invoice,
                    'no_efaktur'       => $post->no_efaktur,
                    'app_hr'           => $post->app_hrd,
                    'app_finance'      => $post->app_finance,
                    'performa_invoice' => $post->performa_invoice,
                    'quo_no'           => getQuo_No($post->id_quo),
                    'id_quo'           => $post->id_quo,
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


    public function AllVendor()
    {

        $data = VendorModel::all();
        $arr  = array();
        foreach ($data as $reg) {
        $arr[$reg->id] = $reg->vendor_name;
        }
        return $arr;
    }

public function show($id)
    {
        $pay     = Pay_VoucherModel::where('id', $id)->first();
        $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
        $pay_app = Pay_VoucherApp::where('id_pay_dtl', $pay_dtl->id)->first();
        $app     = Pay_VoucherApp::where('id_pay_dtl', $pay_dtl->id)->get();
        if($pay_dtl->type_payment== "top"){
            $top = "TOP";
        }
        else if($pay_dtl->type_payment== "cbd"){
            $top= "CBD";
        }else{
            $top="Nett";
        }
        $mine    = getUserEmp(Auth::id());
        if($pay_dtl->app_hrd=="Done" && $mine->division_id==8 || $mine->id==2)
        {
            $info = array (
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } else {
            $info = array(
            'BtnApp'  => "",
            'BtnApp2' => '',
            );
        }
        return view('finance.payment_voucher.from_po.show',[
             'pay'     => $pay,
             'pay_dtl' => $pay_dtl,
             'pay_app' => $pay_app,
             'app'     => $app,
             'top'     => $top,
             'info'    => $info,
             'main'    => $mine,
         ]);
         
    }

function approve (Request $request)
{
    $req_id = $request->segment(4);
    $req_dtl= $request->segment(5);
    $req_usr= $request->segment(6); 
    $id     = Pay_VoucherModel::where('id', $req_id)->first();
    $id_dtl = Pay_VoucherDetail::where('id_pay', $req_id)->first();
    $main   = getUserEmp(Auth::id());
    $app_by = $req_usr == "Finance"? "Approved" : "Approval Complete";
    $tbl_app = [
        'id_pay_dtl' => $id_dtl->id,
        'approval_by'=> $req_usr,
        'status_app' => $app_by,
        'status_by'  => Auth::id(),
        'created_at' => Carbon::now(),
        'created_by' => Auth::id(),
    ];
    $qry1 = Pay_VoucherApp::create($tbl_app);
    if($qry1){
        if($req_usr=="finance"){
        $tbl_dtl = [
        'status'      => "Approved",
        'app_finance' => Auth::id(),
        ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $req_id)->update($tbl_dtl);
        } else if($req_usr=="hr")
        {
        $tbl_dtl = [
        'status'     => "Completed",
        'app_hrd'    => Auth::id(),
        ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $req_id)->update($tbl_dtl);
        }else if($req_usr=="khusus")
        {
        $tbl_dtl = [
        'status'     => "Completed",
        'app_mng'    => Auth::id(),
        ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $req_id)->update($tbl_dtl);
        }
    }
    if($qry1){
            $fin_hist =[
                'activity_name'   => "Menyetujui Payment Voucher",
                'status_activity' => "Payment Voucher",
                'activity_user'   => getUserEmp(Auth::id())->id,
                'created_at'      => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'      => Auth::id(),
            ];
        $qry3= FinanceHistory::create($fin_hist);
        }
    return redirect('finance/payment_voucher')->with('success', 'Approved Successfully');
}




function reject (Request $request)
{
    $req_id = $request->segment(4);
    $req_dtl= $request->segment(5);
    $req_usr= $request->segment(6); 
    $id     = Pay_VoucherModel::where('id', $req_id)->first();
    $id_dtl = Pay_VoucherDetail::where('id_pay', $req_id)->first();
    $main   = getUserEmp(Auth::id());
    $app_by = $req_usr == "Finance"? "Rejected" : "Rejected";
    $tbl_app = [
        'id_pay_dtl' => $id_dtl->id,
        'approval_by'=> $req_usr,
        'status_app' => $app_by,
        'status_by'  => Auth::id(),
        'created_at' => Carbon::now(),
        'created_by' => Auth::id(),
    ];
    $qry1 = Pay_VoucherApp::create($tbl_app);
    if($qry1){
        if($req_usr=="finance"){
        $tbl_dtl = [
        'status'      => "Rejected",
        'app_finance' => Auth::id(),
        ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $req_id)->update($tbl_dtl);
        } else if($req_usr=="hr")
        {
        $tbl_dtl = [
        'status'     => "Rejected",
        'app_hrd'    => Auth::id(),
        ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $req_id)->update($tbl_dtl);
        }
    }
    if($qry1){
            $fin_hist =[
                'activity_name'   => "Tidak Menyetujui Payment Voucher",
                'activity_user'   => getUserEmp(Auth::id())->id,
                'status_activity' => "Payment Voucher",
                'created_at'      => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'      => Auth::id(),
            ];
        $qry3= FinanceHistory::create($fin_hist);
        }
    return redirect('finance/payment_voucher')->with('success', 'Rejected Successfully');
}


function auto_value(Request $request)
{
    if($request->has('type')){
    $po_so = Purchase_order::join('quotation_invoice as qi', 'purchase_orders.id_quo', '=','qi.id_quo')
    ->where('po_number', $request->id)->first();
    $wo_so = Purchase_order::join('warehouse_out as po', 'po.id_quo', '=','purchase_orders.id_quo')
    ->where('po_number', $request->id)->first();

    $id_quo    = 'SO'.sprintf("%06d",$po_so->id_quo);
    $id_vendor = getVendor($po_so->id_vendor)->vendor_name;
    $year      = Carbon::now()->format('Y');
    $year      = substr($year, -2);
    $no_do     = 'WO/OUT/'.$year.'/'.$wo_so->id;
    return response()->json([
        'data' =>[
            'po_so'  => $po_so,
            'id_quo' => $id_quo,
            'vendor' => $id_vendor,
            'wo_so'  => $wo_so,
            'no_do'  => $no_do,
        ],
    ]);
    }else{
        $quo_mo = QuotationModel::where('id', $request->id)->first();
        $join   = QuotationModel::select('*', DB::raw('det_quo_harga_ongkir*det_quo_qty as kali'))
                ->join('quotation_product', 'quotation_product.id_quo', '=', 'quotation_models.id')
                ->join('quotation_purchase', 'quotation_purchase.id_quo', '=', 'quotation_models.id')
                ->where('quotation_models.id', $request->id)->first();
        $vendor = VendorModel::where('id', $join->id_vendor)->get();
        $ongkir = QuotationModel::select('*', DB::raw('det_quo_harga_ongkir*det_quo_qty as kali'))->join('quotation_product', 'quotation_product.id_quo', '=', 'quotation_models.id')
                ->where('quotation_models.id', $request->id)->get()->sum('kali');
        $type_beli = $join->det_quo_type_beli == "cbd" ? Carbon::now()->format('Y-m-d') : null;
        return response()->json([
            'data' =>[
                'vendor' => $vendor,
                'ongkir' => $ongkir,
                'quo_no' => $quo_mo->quo_no,
                'cust'   => $quo_mo->id_customer,
                'type'   => $type_beli,
            ],
        ]);
    }
}


function find_vendor(Request $request)
{
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data   = VendorModel::select("id", "vendor_name")
                ->where('vendor_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
}


function find_so(Request $request)
{
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data   = QuotationModel::select("*")->where('quo_type','!=',1)
                ->where('id', 'LIKE', "%$search%")
                ->orWhere('quo_no', 'LIKE', "%$search%")
                ->orWhere('quo_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
}


public function modal_vendor(Request $request)
{
    // dd($request);
        return view('finance.payment_voucher.add_vendor',[
            'method'  => "post",
            'action'  => "Finance\Pay_VoucherController@saveVendor",
        ]);

    }

public function saveVendor(Request $request)
{
        $data = [
            'vendor_name' => $request->input('vendor_name'),
            'created_by'       => Auth::id()
        ];
        // dd($data);
        $qry = VendorModel::create($data);
        return redirect()->back()->with('succeess');
    }


    public function download_payment($id)
    {
        // dd($id);
        $month   = Carbon::now()->format('m');
        $year    = Carbon::now()->format('Y');
        $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
        $pay     = Pay_VoucherModel::where('id', $id)->first();
        $pays    = Pay_VoucherModel::select('*',DB::raw('max(id_cetak) as max'))->first();
        if($pay->id_cetak==null){
        $data=[
            'id_cetak'   => $pays->max==''? 1 : $pays->max+1,
        ];
        $data1=[
            'no_payment' => $pays->max==''? 1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year : $pays->max+1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
        ];
        Pay_VoucherModel::where('id', $id)->update($data);
        Pay_VoucherDetail::where('id_pay', $id)->update($data1);
        }else{

            $data=[
            'no_payment' => $pay->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
        ];
        Pay_VoucherDetail::where('id_pay', $id)->update($data);
        }
        $qrc    = QrCode::format('png')->size(300)->generate('myNote');
        $pdf     = PDF::loadview('pdf.payment_voucher',[
            'pay'      => $pay,
            'pay_dtl'  => $pay_dtl,
            'id_cetak' => $pays->max+1,
            'pays'     => $pays,
            'qr'       => $qrc,
            'month'    => $month,
            'year'     => $year,
            'time'     => Carbon::now()->format('d - M - Y'),
            'type'     => "no_check",
        ]);
    	return $pdf->download('Payment Voucher MEG - '.Carbon::now()->format('d/m/Y').'.pdf');        
    }

    
    public function download_checked($id)
    {
        if($id=="more2")
            {
                return redirect('finance/payment_voucher')->with('error', 'Maximum Checked Only 2 Boxes For Print!!');
            }
}

function download_checked_double(Request $request)
{
    $req    = $request->segment(4);
    $arr_id = explode(',' , $req);
    $id_arr = $arr_id;
    if(count($id_arr)>1) 
    {
    $index0 = $id_arr[0];
    $index1 = $id_arr[1];
    //Yang Pertama
    $month   = Carbon::now()->format('m');
    $year    = Carbon::now()->format('Y');
    $pay_dtl = Pay_VoucherDetail::where('id_pay', $index0)->first();
    $pay     = Pay_VoucherModel::where('id', $index0)->first();
    $pays    = Pay_VoucherModel::select('*',DB::raw('max(id_cetak) as max'))->first();

    if ($pay->id_cetak==null){
            $data=[
                'id_cetak'   => $pays->max==''? 1 : $pays->max+1,
            ];
            $data1=[
                'no_payment' => $pays->max==''? 1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year : $pays->max+1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
            ];
        $qry1 =   Pay_VoucherModel::where('id', $index0)->update($data);
        $qry2 =   Pay_VoucherDetail::where('id_pay', $index0)->update($data1);
        $info =   $qry1 != 0 ? "oke" : "gagal";

    }else{
            $data=[
                'no_payment' => $pay->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
            ];
        $qry1 = Pay_VoucherDetail::where('id_pay', $index0)->update($data);
        $info =   $qry1 == 0 || $qry1 != 0 ? "oke" : "gagal";
    }
    // Kedua
    if($info=="oke"){
    $pay_dtl1 = Pay_VoucherDetail::where('id_pay', $index1)->first();
    $pay1     = Pay_VoucherModel::where('id', $index1)->first();
    $pays1    = Pay_VoucherModel::select('*',DB::raw('max(id_cetak) as max'))->first();
    if($pay1->id_cetak==null){
                $data=[
                    'id_cetak'   => $pays1->max==''? 1 : $pays1->max+1,
                ];
                $data1=[
                    'no_payment' => $pays1->max==''? 1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year: $pays1->max+1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
                ];
            $qry1 =   Pay_VoucherModel::where('id', $index1)->update($data);
            $qry2 =   Pay_VoucherDetail::where('id_pay', $index1)->update($data1);
            $info2=   $qry1 != 0 ? "oke" : "gagal";
        }else{
                $data=[
                'no_payment' => $pay1->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
                ];
            $qry2 = Pay_VoucherDetail::where('id_pay', $index1)->update($data);
            $info2=   $qry1 == 0 || $qry1 != 0 ? "oke" : "gagal";
            }
        }
        return response()->json([
        'success' => true,
        'infos'   => $info2,
        ]);
    }
    else{
        return $this->download_oneChecked($request);
    }
}


function download_oneChecked($request)
{
        $id      = $request->segment(4);
        $month   = Carbon::now()->format('m');
        $year    = Carbon::now()->format('Y');
        $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
        $pay     = Pay_VoucherModel::where('id', $id)->first();
        $pays    = Pay_VoucherModel::select('*',DB::raw('max(id_cetak) as max'))->first();
        if($pay->id_cetak==null){
        $data=[
            'id_cetak'   => $pays->max==''? 1 : $pays->max+1,
        ];
        $data1=[
            'no_payment' => $pays->max==''? 1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year : $pays->max+1 .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
        ];
        $qry1 = Pay_VoucherModel::where('id', $id)->update($data);
        $qry2 = Pay_VoucherDetail::where('id_pay', $id)->update($data1);
        $info3=   $qry1 != 0 ? "oke" : "gagal";
        }else{

            $data=[
            'no_payment' => $pay->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year,
        ];
        $qry1 = Pay_VoucherDetail::where('id_pay', $id)->update($data);
        $info3=   $qry1 != 0 ? "oke" : "gagal";
        }
        return response()->json([
        'success' => true,
        'info'    => $info3,
        ]);
}

function download_doublecheck(Request $request){
    $arr_id   = explode(',' , $request->segment(4));
    $id_arr   = $arr_id;
    if(count($id_arr)>1) 
    {
    $month    = Carbon::now()->format('m');
    $year     = Carbon::now()->format('Y');
    $pay_dtl  = Pay_VoucherDetail::where('id_pay', $id_arr[0])->first();
    $pay_dtl1 = Pay_VoucherDetail::where('id_pay', $id_arr[1])->first();
    $pay      = Pay_VoucherModel::where('id', $id_arr[0])->first();
    $pay1     = Pay_VoucherModel::where('id', $id_arr[1])->first();
    $pays     = Pay_VoucherModel::select('*',DB::raw('max(id_cetak) as max'))->first();
    $qrc    = QrCode::format('png')->size(300)->generate('myNote');
    $pdf      = PDF::loadview('pdf.payment_voucher',[
        'pay'      => $pay,
        'pay_dtl'  => $pay_dtl,
        'pay1'     => $pay1,
        'pay_dtl1' => $pay_dtl1,
        'pays'     => $pays->max== '' ? 1 : $pays->max+1,
        'month'    => $month,
        'qr'       => $qrc,
        'time'     => Carbon::now()->format('d - M - Y'),
        'year'     => $year,
        'type'     => 'check',
    ]);
}else{
    $id      = $request->segment(4);
    $month   = Carbon::now()->format('m');
    $year    = Carbon::now()->format('Y');
    $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
    $pay     = Pay_VoucherModel::where('id', $id)->first();
    $pays    = Pay_VoucherModel::select('*',DB::raw('max(id_cetak) as max'))->first();
    $qrc    = QrCode::format('png')->size(300)->generate('myNote');
    $pdf     = PDF::loadview('pdf.payment_voucher',[
        'pay'     => $pay,
        'pay_dtl' => $pay_dtl,
        'month'   => $month,
        'year'    => $year,
        'qr'       => $qrc,
        'time'    => Carbon::now()->format('d - M - Y'),
        'pays'    => $pays->max==''? 1 : $pays->max+1,
        'type'    => "no_check",
    ]);
}
    return $pdf->download('Payment Voucher MEG'.' '.Carbon::now()->format('d/m/Y').'.pdf');        
}


function penyebut($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp  = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}

function terbilang($nilai) {
	if($nilai<0) {
		$hasil = "minus ". trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}     		
	return $hasil;
    }



public function getNO_PO()
    {

        $data = Purchase_order::join('quotation_invoice as qi', 'purchase_orders.id_quo', '=','qi.id_quo')
        ->get();
        $arr  = array();
        foreach ($data as $reg) {
            $arr[$reg->po_number] = strtoupper($reg->po_number);
        }
        return $arr;
    }


    public function get_province()
    {
        $data = Provinsi::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }

    public function get_city()
    {
        $data = Kota::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->kota);
        }
        return $arr;
    }


    public function get_Kecamatan()
    {
        $data = Kecamatan::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }


/////////////////////////////////////   Tambahan    //////////////////////

    public function edit_finance($id)
    {
        // dd($id);
         $pay     = Pay_VoucherModel::where('id', $id)->first();
         $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
         $vendor  = VendorModel::all();
         return view('finance.payment_voucher.edit',[
            'vendor'  => $this->AllVendor(),
            'no_po'   => $this->getNO_PO(),
            'cust'    => $this->get_Customer(),
            'no_so'   => $this->get_SO(),
            'pay'     => $pay,
            'pay_dtl' => $pay_dtl,
        ]);
    }

    public function store_finance(Request $request)
    {
        // dd($request);
        $month      = Carbon::now()->format('m');
        $year       = Carbon::now()->format('Y');
        $id_vendor  = $request->type_voucher=="lainnya" ? $request->vendor_id : $request->sec_vnd;
        $tujuan     = $request->tujuan==null?"Payment " : $request->tujuan;
        $tujuan     = $request->has('no_so') || $request->no_so!=null ? $request->tujuan : $tujuan.' '.$request->quo_no;
        $files   = $request->file('doc_alltop');
        $inv     = $request->file('doc_inv_performa');
        $other   = $request->file('doc_lainnya');
        $voucher    = [
            'id_vendor'      => $id_vendor,
            'tujuan'         => $tujuan,
            'nominal'        => $request->nominal,
            'keperluan_form' => $request->type_voucher,
            'created_by'     => Auth::id(),
            'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry1 = Pay_VoucherModel::create($voucher);
        if($qry1)
        {
            $voucher_detail = [
                'id_vendor'        => $id_vendor,
                'id_customer'      => $request->id_cust,
                'id_pay'           => $qry1->id,
                'id_quo'           => $request->no_so,
                'status'           => "Pending",
                'tujuan'           => $tujuan,
                'from_date'        => $request->tgl_payment,
                'terbilang'        => terbilang($request->nominal),
                'no_invoice'       => $request->no_invoice,
                'no_so'            => $request->no_so==null ? null : 'SO'.sprintf("%06d",$request->no_so),
                'to_date'          => $request->to_date,
                'doc_alltop'       => $request->has('doc_alltop') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_alltop'), rand()."".$files->getClientOriginalName()) : null,
                'doc_inv_performa' => $request->has('doc_inv_performa') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_inv_performa'), rand()."".$inv->getClientOriginalName()) : null,
                'doc_lainnya'      => $request->has('doc_lainnya') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_lainnya'), rand()."".$other->getClientOriginalName()) : null,                
                'nominal'          => $request->nominal,
                'created_by'       => Auth::id(),
                'created_at'       => Carbon::now('GMT+7')->toDateTimeString(),
     ];
        $qry2 = Pay_VoucherDetail::create($voucher_detail);
        }
        if($qry2){
            $fin_hist =[
                'activity_name'     => "Menambahkan Payment Voucher",
                'activity_user'     => getUserEmp(Auth::id())->id,
                'status_activity'   => "Payment Voucher",
                'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'        => Auth::id(),
            ];
        $qry3= FinanceHistory::create($fin_hist);
        }
        return redirect('finance/payment_voucher')->with('success', 'Created Successfully');
    }

    public function update_finance(Request $request)
    {
        // dd($request);
        $month   = Carbon::now()->format('m');
        $year    = Carbon::now()->format('Y');
        $id_vendor = $request->type_voucher=="lainnya" ? $request->vendor_id : $request->sec_vnd;
        $tujuan   = $request->has('no_so') || $request->no_so!=null ? $request->tujuan.' '.$request->quo_no : $request->tujuan;
        $detail  = Pay_VoucherDetail::where('id_pay',$request->id_pay)->first();
        $files   = $request->file('doc_alltop');
        $inv     = $request->file('doc_inv_performa');
        $other   = $request->file('doc_lainnya');
        $voucher = [
            'id_vendor'      => $id_vendor,
            'tujuan'         => $tujuan,
            'nominal'        => $request->nominal,
            'keperluan_form' => $request->type_voucher,
            'created_by'     => Auth::id(),
            'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry1 = Pay_VoucherModel::where('id',$request->id_pay)->update($voucher);
        if($qry1)
        {
            $voucher_detail = [
                'id_vendor'        => $id_vendor,
                'id_customer'      => $request->id_cust,
                'id_pay'           => $request->id_pay,
                'id_quo'           => $request->id_quo,
                'status'           => "Pending",
                'tujuan'           => $tujuan,
                'from_date'        => $request->tgl_payment,
                'terbilang'        => terbilang($request->nominal),
                'no_invoice'       => $request->no_invoice,
                'no_so'            => 'SO'.sprintf("%06d",$request->no_so),
                'to_date'          => $request->to_date,
                'nominal'          => $request->nominal,
                'doc_alltop'       => $request->has('doc_alltop') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_alltop'), rand()." ".$files->getClientOriginalName()) : $detail->doc_alltop,
                'doc_inv_performa' => $request->has('doc_inv_performa') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_inv_performa'), rand()."".$inv->getClientOriginalName()) : $detail->doc_inv_performa,
                'doc_lainnya'      => $request->has('doc_lainnya') ?Storage::disk('public')->putFileAs('file_finance', $request->file('doc_lainnya'), rand()."".$other->getClientOriginalName()) : $detail->doc_lainnya,                
                'created_by'       => Auth::id(),
                'created_at'       => Carbon::now('GMT+7')->toDateTimeString(),
     ];
        $qry2 = Pay_VoucherDetail::where('id_pay', $request->id_pay)->update($voucher_detail);
        }
        if($qry2){
            $fin_hist =[
                'activity_name'     => "Menambahkan Payment Voucher",
                'activity_user'     => getUserEmp(Auth::id())->id,
                'status_activity'   => "Payment Voucher",
                'created_at'        => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'        => Auth::id(),
            ];
        $qry3= FinanceHistory::create($fin_hist);
        }
        return redirect('finance/payment_voucher')->with('success', 'Updated Successfully');
    }

public function show_finance($id)
    {
        $pay     = Pay_VoucherModel::where('id', $id)->first();
        $pay_dtl = Pay_VoucherDetail::where('id_pay', $id)->first();
        $pay_app = Pay_VoucherApp::where('id_pay_dtl', $pay_dtl->id)->first();
        $app     = Pay_VoucherApp::where('id_pay_dtl', $pay_dtl->id)->get();
        $quo_mo  = QuotationModel::where('id', $pay_dtl->id_quo)->first();
        $dok     = Pay_VoucherDokumen::where('id_pay', $id)->get();
        if($pay_dtl->type_payment== "top"){
            $top = "TOP";
        }
        else if($pay_dtl->type_payment== "cbd"){
            $top= "CBD";
        }else{
            $top="Nett";
        }
        $mine    = getUserEmp(Auth::id());
        if($pay_dtl->app_hrd=="Done" && $mine->division_id==8 || $mine->id==2)
        {
            $info = array (
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } else {
            $info = array(
            'BtnApp'  => "",
            'BtnApp2' => '',
            );
        }
        return view('finance.payment_voucher.show',[
             'pay'     => $pay,
             'pay_dtl' => $pay_dtl,
             'pay_app' => $pay_app,
             'dok'     => $dok,
             'quo_no'  => $quo_mo==null ? null : $quo_mo->quo_no,
             'quo_name'=> $quo_mo==null ? null : $quo_mo->quo_name,
             'app'     => $app,
             'top'     => $top,
             'info'    => $info,
             'main'    => $mine,
         ]);
         
    }

   public function get_Vendor(){
        $vendor = VendorModel::all();
        $arr    = array();
        foreach ($vendor as $reg) {
            $arr[$reg->id] = $reg->vendor_name;
        }
        return $arr;
    }

    public function get_Customer(){
        $cust = CustomerModel::all();
        $arr    = array();
        foreach ($cust as $reg) {
            $arr[$reg->id] = $reg->company;
        }
        return $arr;
    }

    public function get_SO(){
        $quo_mo = QuotationModel::all();
        $arr    = array();
        foreach ($quo_mo as $reg) {
            if($reg->quo_no==null){
                $num = "RFQ";
            }else{
                $num = $reg->quo_no;
            }
            $arr[$reg->id] = '['.'SO'.sprintf("%06d", $reg->id).' - '.$num.'] '.$reg->quo_name;
        }
        return $arr;
    }



    
}