<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Finance\Pay_VoucherModel;
use App\Models\Finance\Pay_VoucherApp;
use App\Models\Finance\Pay_VoucherDetail;
use App\Models\Finance\Pay_VoucherDokumen;
use App\Models\Finance\Pay_VoucherPayment;
use App\Models\Finance\FinanceHistory;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationModel;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
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

class OtherCostController extends Controller
{
    public function index()
    {
        return view('finance.othercost.index',[
            'main' => getUserEmp(Auth::id()),
        ]);
    }


    public function indexOther()
    {
        return view('finance.othercost.index',[
            'main' => getUserEmp(Auth::id()),
        ]);
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

        $menu_count    = Pay_VoucherDetail::where([['finance_pay_voucher_detail.created_by', $usr], ['no_po', null]])->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::select('*')
             ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Pending Director") then "B" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NULL AND app_mng IS NULL) then "C" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NULL) then "D" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NOT NULL) then "E" when (status = "Completed") then "F" when (status = "Rejected") then "Z" else "Y" end) as status_sort'))
             ->where([['finance_pay_voucher_detail.created_by', $usr],['no_po', null]])->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
        } else {
            $search = $request->input('search')['value'];
            $posts  = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
                ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orWhere('no_so', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Pay_VoucherDetail::where('no_payment', 'like', '%' . $search . '%')
                ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orWhere('no_so', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        $data = $this->post_ajax_data($posts, "other");
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

        $menu_count    = Pay_VoucherDetail::where('no_po', null)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::select('*')->where('no_po', null)
             ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Pending Director") then "B" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NULL AND app_mng IS NULL) then "C" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NULL) then "D" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NOT NULL) then "E" when (status = "Completed") then "F" when (status = "Rejected") then "Z" else "Y" end) as status_sort'))
             ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
        } else {
            $search = $request->input('search')['value'];
            $posts  = Pay_VoucherDetail::where('no_po', null)->where('no_payment', 'like', '%' . $search . '%')
                ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orWhere('no_so', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Pay_VoucherDetail::where('no_po', null)->where('no_payment', 'like', '%' . $search . '%')
                ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orWhere('no_so', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        
        $data = $this->post_ajax_data($posts, "finance");
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

        $menu_count    = Pay_VoucherDetail::where('no_po', null)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Pay_VoucherDetail::select('*')->where('no_po', null)
             ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Pending Director") then "B" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NULL AND app_mng IS NULL) then "C" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NULL) then "D" when (status = "Approved" AND app_finance IS NOT NULL AND app_hrd IS NOT NULL AND app_mng IS NOT NULL) then "E" when (status = "Completed") then "F" when (status = "Rejected") then "Z" else "Y" end) as status_sort'))
             ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
        } else {
            $search = $request->input('search')['value'];
            $posts  = Pay_VoucherDetail::where('no_po', null)->where('no_payment', 'like', '%' . $search . '%')
                ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orWhere('no_so', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Pay_VoucherDetail::where('no_po', null)->where('no_payment', 'like', '%' . $search . '%')
                ->orWhere('no_po', 'like', '%' . $search . '%')
                ->orWhere('no_so', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        
        $data = $this->post_ajax_data($posts, "management");
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    
    public function post_ajax_data($posts, $user)
    {
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

                $vendors = $post->id_vendor==365 || checkPayAppr($post->id_pay)!=null ? 'direksi' : 'no';
                $note_payment = Pay_VoucherPayment::where('id_pay', $post->id_pay)->orderBy('id', 'desc')->first();
                $data[] = [
                    'tujuan'           => $post->tujuan,
                    'no_payment'       => $post->no_payment,
                    'id_vendor'        => $vendor_cust,
                    'link'             => $link,
                    'created_at'       => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'               => $post->id,
                    'nominal'          => $post->nominal,
                    'user'             => $user,
                    'note_pay'         => $note_payment==null? null : $note_payment->status.' '.Carbon::parse($note_payment->date_payment)->format('d F Y'),
                    'vendors'          => $vendors,
                    'status'           => $post->status,
                    'no_invoice'       => $post->no_invoice,
                    'no_efaktur'       => $post->no_efaktur,
                    'app_hr'           => $post->app_hrd,
                    'app_finance'      => $post->app_finance,
                    'app_mng'          => $post->app_mng,
                    'performa_invoice' => $post->performa_invoice,
                    'quo_no'           => getQuo_No($post->id_quo),
                    'id_quo'           => $post->id_quo,
                    'no_so'            => $post->no_so,
                    'note_pph'         => $post->note_pph,
                    'note_nominal_pph' => number_format($post->note_nominal_pph),
                    'note_transfer_pph'=> number_format($post->note_transfer_pph),
                ];
            }
        }
        return $data;
    }
}