<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role\Role_dashboard;
use App\Models\HR\EmployeeModel;
use App\Models\Sales\QuotationModel;
use App\Models\Product\ListContent;
use App\Models\Product\LiveManModel;
use App\Models\Activity\ActQuoModel;
use App\Models\Product\ProductLive;
use App\Models\HR\Req_TravelModel;
use App\Models\HR\Req_TravelApproval;
use App\Models\HR\Req_LeaveModel;
use App\Models\HR\Req_LeaveApp;
use App\Models\HR\Req_OvertimeModel;
use App\Models\HR\Req_OvertimeApp;
use App\Models\Finance\FinanceSettlementModel;
use App\Models\Finance\FinanceSettlementDetail;
use App\Models\Finance\FinanceSettlementApp;
use App\Models\Finance\CashAdvance;
use App\Models\Finance\CashAdvanceDesc;
use App\Models\Finance\CashAdvanceApp;
use App\Models\Purchasing\Purchase_address;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_draft;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_model;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\Warehouse_pengiriman;
use App\Models\Warehouse\Warehouse_out;
use App\Models\Warehouse\Warehouse_resi;
use App\Models\Product\PendingApproval;
use App\Models\Android\AndroidAbsensi;
use App\Models\Role\Role_division;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $usr = getEmp(Auth::id());

        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $count = $mine->count();
        $request->session()->put('division_id', $mine->division_id);
        $request->session()->put('cabang_id', $mine->cabang_id);
        $check = Role_dashboard::where('id_division', $mine->division_id)->first()->name;
        if ($check == "dashboard-management") {
            return $this->dash_management($request);
        } else if ($check == "dashboard-purchasing") {
            return $this->dash_purchasing($request);
        } else if ($check == "dashboard-sales") {
            return $this->dash_sales($request);
        } else if ($check == "dashboard-content") {
            return $this->dash_content($request);
        } else if ($check == "dashboard-admin") {
            return $this->dash_admin($request);
        } else if ($check == "dashboard-warehouse") {
            return $this->dash_warehouse($request);
        } else if ($check == "dashboard-finance") {
            return $this->dash_finance($request);
        } else if ($check == "dashboard-product") {
            return $this->dash_product($request);
        } else if ($check == "dashboard-hrd") {
            return $this->dash_hrd($request);
        } else if ($check == "dashboard-support"){
            return $this->dash_support($request);
        }else if($check=="dashboard-dev"){
            return $this->dash_dev($request);
        }
    }


    public function dash_support(Request $request)
    {
        
        return view('dashboard.dashboard-dev',[
            
        ]);
    }


    public function dash_dev(Request $request)
    {
        
        return view('dashboard.dashboard-dev',[
            
        ]);
    }



    public function dash_hrd(Request $request)
    {
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        //Log Request
        $leave  = Req_LeaveModel::where('status', 'Need Approval')->get();
        $travel = Req_TravelModel::where('status', 'Need Approval')->get();
        $over   = Req_OvertimeModel::where('status', 'Need Approval')->get();
        
        $cleave  = Req_LeaveModel::where('status', 'Need Approval')->get()->count();
        $ctravel = Req_TravelModel::where('status', 'Need Approval')->get()->count();
        $cover   = Req_OvertimeModel::where('status', 'Need Approval')->get()->count();
        
        $rej_leave = Req_leaveModel::where('status', 'Rejected By HRD')->get()->count();
        $app_leave = Req_LeaveModel::where('status', 'Approved By HRD')->get()->count();
        
        $rej_travel = Req_TravelModel::where('status', 'Rejected By HRD')->get()->count();
        $app_travel = Req_TravelModel::where('status', 'Approved By HRD')->get()->count();
        
        $rej_over = Req_OvertimeModel::where('status', 'Rejected By HRD')->get()->count();
        $app_over = Req_OvertimeModel::where('status', 'Approved By HRD')->get()->count();
        
        //kehadiran hari ini
        $absensi  = AndroidAbsensi::where('created_at', $now)->where('status', 'check-in')->get()->count();
        
        
        $setuju = $app_leave + $app_travel + $app_over;
        $tdk_setuju = $rej_leave + $rej_travel +$rej_over;
        return view('dashboard.dashboard-hrd', [
        'leave'      => $leave,
        'travel'     => $travel,
        'setuju'     => $setuju,
        'tdk_setuju' => $tdk_setuju,
        'over'       => $over,
        'cleave'     => $cleave,
        'ctravel'    => $ctravel,
        'cover'      => $cover,
        ]);
    }



    public function dash_management(Request $request)
    {
        // dd($request);
        $now      = Carbon::now();
        $format   = Carbon::now()->format('d/m/Y');

        $chart    = QuotationProduct::select('*')->orderBy('det_quo_harga_order', "DESC")->limit(10)->get();
        $so_month = QuotationModel::where('quo_type', '!=', 1)->whereMonth('created_at', Carbon::parse($now)->format('m'))->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();
        $so_year  = QuotationModel::where('quo_type', '!=', 1)->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();

        $so_nego  = QuotationModel::select('*', 'quotation_models.id')->whereNull('quo_instatus')
            ->whereNull('d.id')
            ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
            ->where([
                ['quo_type', '>', '1'],
                ['quo_eksposisi', 'not like', '%distri%'],
                ['quo_ekskondisi', '<>', 'Batal']
            ])->whereYear('quotation_models.created_at', Carbon::parse($now)->format('Y'))->get();
        $so_mnego  = QuotationModel::select('*', 'quotation_models.id')->whereNull('quo_instatus')
            ->whereNull('d.id')
            ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
            ->where([
                ['quo_type', '>', '1'],
                ['quo_eksposisi', 'not like', '%distri%'],
                ['quo_ekskondisi', '<>', 'Batal']
            ])->whereMonth('quotation_models.created_at', Carbon::parse($now)->format('m'))
            ->whereYear('quotation_models.created_at', Carbon::parse($now)->format('Y'))->get();
        
        $so_batal    = QuotationModel::where('quo_ekskondisi', 'Batal')->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();
        $so_mbatal   = QuotationModel::where('quo_ekskondisi', 'Batal')->whereMonth('created_at', Carbon::parse($now)->format('m'))->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();
        
        $so_mclose   = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')
                       ->whereMonth('q.created_at', Carbon::parse($now)->format('m'))->whereYear('quotation_invoice.created_at', Carbon::parse($now)->format('Y'))->get(); 
        $so_yclose   = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')
                       ->whereYear('q.created_at', Carbon::parse($now)->format('Y'))->get(); 
                   
        //array 149
        $result        = [];
        $result_year   = [];
        $result_nego   = [];
        $result_batal  = [];
        $result_mclose = [];
        $result_yclose = [];
        $result_mbatal = [];
        $result_mnego  = [];
        
        foreach($so_month as $main)
        {
            $month = GetTotalAkhir($main->id);
            $result[] = $month;
        }

        foreach ($so_year as $l)
        {
            $year = GetTotalAkhir($l->id);
            $result_year[]  = $year;
        }

        foreach ($so_nego as $his)
        {
            $nego          = GetTotalAkhir($his->id);
            $result_nego[] = $nego;
        }
        
        foreach ($so_batal as $batal)
        {
            $nego        = GetTotalAkhir($batal->id);
            $result_batal[] = $nego;
        }

        foreach ($so_mclose as $mclose)
        {
            $nego        = GetTotalAkhir($mclose->id);
            $result_mclose[] = $nego;
        }

        foreach ($so_yclose as $yclose)
        {
            $nego        = GetTotalAkhir($yclose->id);
            $result_yclose[] = $nego;
        }

        foreach ($so_mnego as $yclose)
        {
            $nego        = GetTotalAkhir($yclose->id);
            $result_mnego[] = $nego;
        }

        foreach ($so_mbatal as $yclose)
        {
            $nego        = GetTotalAkhir($yclose->id);
            $result_mbatal[] = $nego;
        }


        $sum_month  = array_sum($result);
        $sum_year   = array_sum($result_year);
        $sum_nego   = array_sum($result_nego);
        $sum_batal  = array_sum($result_batal);
        $sum_mclose = array_sum($result_mclose);
        $sum_yclose = array_sum($result_yclose);
        $sum_mnego  = array_sum($result_mnego);
        $sum_mbatal = array_sum($result_mbatal);
        
        return view('dashboard.dashboard-management', [
            'chart'     => $chart,
            'sum_month' => $sum_month,
            'sum_year'  => $sum_year,
            'sum_nego'  => $sum_nego,
            'sum_batal' => $sum_batal,
            'sum_mclose'=> $sum_mclose,
            'sum_yclose'=> $sum_yclose,
            'sum_mnego' => $sum_mnego,
            'sum_mbatal'=> $sum_mbatal,
        ]);
    }



    public function chart(Request $request)
    {
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');
        $y  = Carbon::now()->format('Y');
        $month_name_array = array();
        $monthly_post_count_array = array();
        $month_array = $this->getAllMonths();
        // 
        $price = DB::select(DB::raw("SELECT id_sales, quo_ekskondisi, sum(det_quo_harga_order*det_quo_qty) as sm FROM quotation_models JOIN quotation_product as p on p.id_quo = quotation_models.id
        And quo_type != 1 and isnull(quo_ekskondisi) or quo_ekskondisi = 'Masih Negosiasi' Group By id_sales ORDER BY sm DESC"));
        $batal = DB::select(DB::raw("SELECT id_sales, quo_ekskondisi, sum(det_quo_harga_order*det_quo_qty) as sm FROM quotation_models JOIN quotation_product as p on p.id_quo = quotation_models.id
        And quo_type != 1 and quo_ekskondisi = 'Batal' Group By id_sales ORDER BY sm DESC"));
        // dd($price, $batal);
        // 
        $count_id_quo = QuotationModel::select(DB::raw('count(id) as count', 'quo_ekskondisi'))->where('quo_type', '!=', 1)
            ->groupBy('id_sales')->orderBy('count', 'DESC')->get();

        $paket_aktif = DB::select(DB::raw("SELECT id_sales, quo_ekskondisi, count(id) as count FROM quotation_models
        where quo_type != 1 and isnull(quo_ekskondisi) or quo_ekskondisi = 'Masih Negosiasi' group by id_sales ORDER BY count DESC"));
        $pkt_tdk_aktif = QuotationModel::select(DB::raw('count(id) as count', 'quo_ekskondisi'))->where('quo_type', '!=', 1)
            ->where('quo_ekskondisi', 'Batal')
            ->groupBy('id_sales')->orderBy('count', 'DESC')->get();

        // 
        $kondisi_batal = [];
        foreach ($batal as $batal1 => $bt) {
            $kondisi_batal[] = $bt->sm;
        }

        $sales_array = [];
        $sales_nama_array = [];
        foreach ($price as $sales_array1 => $sm) {
            $sales_array[] = $sm->sm;
            $sales_nama_array[] = emp_name($sm->id_sales);
        }

        foreach ($month_array as $month_no => $month_name) {
            $monthly_post_count = $this->getMonthlyPostCount($month_no);
            array_push($monthly_post_count_array, $monthly_post_count);
            array_push($month_name_array, $month_name);
        }

        $CO = [];
        foreach ($count_id_quo as $count => $d) {
            $CO[] = $d->count;
        }

        $akt = [];
        foreach ($paket_aktif as $p => $r) {
            $akt[] = $r->count;
        }

        $tdk_aktif = [];
        foreach ($pkt_tdk_aktif as $tdk => $dk) {
            $tdk_aktif[] = $dk->count;
        }
        return response()->json([
            'aktif'           => $akt,
            'batal'           => $kondisi_batal,
            'btl_paket'       => $tdk_aktif,
            'price'           => $sales_array,
            'count_all'       => $CO,
            'month'           => $month_name_array,
            'post_count_data' => $monthly_post_count_array,
            'sales'           => $sales_nama_array,
        ]);
    }

    function getAllMonths()
    {

        $month_array = array();
        $posts_dates = QuotationModel::orderBy('created_at', 'ASC')->pluck('created_at');
        $posts_dates = json_decode($posts_dates);

        if (!empty($posts_dates)) {
            foreach ($posts_dates as $unformatted_date) {
                $month_no = Carbon::parse($unformatted_date)->format('m');
                $month_name = Carbon::parse($unformatted_date)->format('M');
                $year = Carbon::parse($unformatted_date)->format('Y');
                $month_array[$month_no] = $month_name . ' ' . $year;
            }
        }
        return $month_array;
    }

    function getMonthlyPostCount($month)
    {
        $monthly_post_count = QuotationProduct::join('quotation_models as qm', 'qm.id', '=', 'quotation_product.id_quo')
            ->whereMonth('qm.created_at', $month)
            ->get()->sum('det_quo_harga_order');
        return $monthly_post_count;
    }

    function getAllsales()
    {
        $sales_array = array();
        $where_sales = QuotationProduct::join('quotation_models as qm', 'qm.id', '=', 'quotation_product.id_quo')->pluck('id_sales');
        $where_sales = json_decode($where_sales);

        if (!empty($where_sales)) {
            foreach ($where_sales as $sales) {
                // dd($sales);
                $sales_id = $sales;
                $sales_name = emp_name($sales);
                $sales_array[$sales_id] = $sales_name;
            }
        }
        return $sales_array;
    }

    function getSalesSum($sales_id)
    {

        $y  = Carbon::now()->format('Y');
        $price = DB::select(DB::raw("SELECT id_sales, quo_ekskondisi, sum(det_quo_harga_order) as sm FROM quotation_models JOIN quotation_product as p on p.id_quo = quotation_models.id
        And quo_type != 1 and isnull(quo_ekskondisi) or quo_ekskondisi = 'Masih Negosiasi' WHERE id_sales = '$sales_id' ORDER BY sm DESC"));
        return $price;
    }


    public function dash_finance(Request $request)
    {
        
        $settle_blm_complete   = FinanceSettlementModel::where('status', '!=', 'Completed')->get()->count();
        $joinCash_blm_sls      = CashAdvance::join('finance_settlement as q', 'q.no_ref', '=','finance_cash_adv.no_cashadv')->where('q.status_note', '!=', null)->first();
        $user                  = getUserEmp(Auth::id());
        $birth                 = EmployeeModel::whereDay('emp_birthdate', Carbon::now()->format('d'))->whereMonth('emp_birthdate', Carbon::now()->format('m'))->get();
        $today                 = Carbon::now()->format('y/m/d');
        
        //alert untuk cash advance
        $cash = CashAdvance::where('status', 'Pending')->whereDate('created_at',Carbon::today())->get()->count();
        $sett = FinanceSettlementModel::where('status', 'Pending')->whereDate('created_at', Carbon::today())->get()->count();
        return view('dashboard.dashboard-finance', [
            'not_comp' => $settle_blm_complete,
            'joincash' => $joinCash_blm_sls,
            'user'     => $user,
            'birth'    => $birth,
            'cash'     => $cash,
            'set'      => $sett,    
        ]);
    }




    public function dash_purchasing(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $Sajukan = QuotationProduct::join('purchase_detail as p', 'p.id_product', '=', 'quotation_product.id')
            ->where('quotation_product.id_quo', 'purchase_detail.id_quo')->count();
        $new     = QuotationProduct::where('id_product', 'new')->count();
        $Qven    = QuotationProduct::where('id_vendor', null)->count();
        $SumOr   = QuotationModel::where('id_sales', $mine->id_emp)->whereMonth('created_at', $mon)->sum('quo_price');
        $Papp    = Purchase_order::where('status', 'approve')->whereYear('created_at', $year)->count();
        $Capp    = Purchase_order::where('status', 'approve')->whereYear('created_at', $year)->sum('price');
        
        //Card Cancel / Reject
        $Yco_reject   = Purchase_order::where('status', 'reject')->whereYear('created_at', $year)->count();
        $Ypr_reject   = Purchase_order::where('status', 'reject')->whereYear('created_at', $year)->sum('price');
        $Mco_reject   = Purchase_order::where('status', 'reject')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $Mpr_reject   = Purchase_order::where('status', 'reject')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->sum('price');
        
        $Yco_approve = Purchase_order::where('status', 'approve')->whereYear('created_at', $year)->count();
        $Ypr_approve = Purchase_order::where('status', 'approve')->whereYear('created_at', $year)->sum('price');
        $Mco_approve = Purchase_order::where('status', 'approve')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $Mpr_approve = Purchase_order::where('status', 'approve')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->sum('price');
        
        //Card Selanjutnya
        $PO_today      = Purchase_order::whereDay('created_at',$day)->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $POdraft_today = Purchase_order::where('status', 'draft')->whereDay('created_at',$day)->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        return view('dashboard.dashboard-purchasing', [
            'Yco_reject'    => $Yco_reject,
            'Ypr_reject'    => $Ypr_reject,
            'Mco_reject'    => $Mco_reject,
            'Mpr_reject'    => $Mpr_reject,
            'Yco_approve'   => $Yco_approve,
            'Ypr_approve'   => $Ypr_approve,
            'Mco_approve'   => $Mco_approve,
            'Mpr_approve'   => $Mpr_approve,
            'PO_today'      => $PO_today,
            'POdraft_today' => $POdraft_today,
        ]);
    }



    public function dash_sales(Request $request)
    {
        // config(['database.connections.mysql.strict' => false]);
        // DB::reconnect();

        $mine = getUserEmp(Auth::id());
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $cancel   = QuotationModel::where('quo_ekskondisi', 'Batal')->where('id_sales', $mine->id_emp)->whereYear('created_at', $year)->count();
        $cprice   = QuotationModel::where('quo_ekskondisi', 'Batal')->where('id_sales', $mine->id_emp)->whereYear('created_at', $year)->sum('quo_price');
        $rfq      = QuotationModel::where('quo_type', '!=', 1)->where('id_sales', $mine->id_emp)->whereMonth('created_at', $mon)->count();
        $rfqsum   = QuotationModel::where('quo_no', null)->where('id_sales', $mine->id_emp)->whereMonth('created_at', $mon)->sum('quo_price');
        $orMonth  = QuotationModel::where('id_sales', $mine->id_emp)->whereMonth('created_at', $mon)->count();
        $SumOr    = QuotationModel::where('id_sales', $mine->id_emp)->whereMonth('created_at', $mon)->sum('quo_price');
        $app      = QuotationModel::where('quo_approve_status', 'approve')->where('id_sales', $mine->id_emp)->whereYear('created_at', $year)->count();
        $rej      = QuotationModel::where('quo_approve_status', 'reject')->where('id_sales', $mine->id_emp)->whereYear('created_at', $year)->count();
        $salesAct = QuotationModel::select('*')->join('quotation_product', 'quotation_product.id_quo', '=', 'quotation_models.id')->where('id_product', 'new')
            ->where('quotation_models.id_sales', $mine->id_emp)->get();

        $akt_year = DB::select(DB::raw("SELECT id_sales, quo_ekskondisi, id_quo, sum(det_quo_harga_order*det_quo_qty) as sm FROM quotation_models JOIN quotation_product as p on p.id_quo = quotation_models.id
        WHERE id_sales='$mine->id_emp' AND quo_type != 1 and isnull(quo_ekskondisi) or quo_ekskondisi = 'Masih Negosiasi' AND id_sales = '$mine->id_emp' AND quo_type!=1 AND YEAR(quotation_models.created_at)"))[0];
        $akt_pktyear = QuotationModel::where('id_sales', $mine->id_emp)->where('quo_type', '!=', 1)->whereYear('created_at', $year)->count('id');

        $close_pktyear   = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')->where('id_sales', $mine->id_emp)->whereYear('q.created_at', $year)->count('id_quo');
        $close_priceyear = QuotationInvoice::select(DB::raw('sum(det_quo_harga_order*det_quo_qty) as harga'), 'qm.id', 'no_invoice', 'id_sales')
            ->join('quotation_models as qm', 'qm.id', '=', 'quotation_invoice.id_quo')->join('quotation_product as q', 'q.id_quo', '=', 'quotation_invoice.id_quo')->where('id_sales', $mine->id_emp)
            ->whereYear('qm.created_at', $year)->first();

        $close_pktmon = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')->whereMonth('quotation_invoice.created_at', $mon)->where('id_sales', $mine->id_emp)->count('id_quo');
        $close_pricemon = QuotationInvoice::select(DB::raw('sum(det_quo_harga_order*det_quo_qty) as harga'), 'qm.id', 'no_invoice', 'id_sales')
            ->join('quotation_models as qm', 'qm.id', '=', 'quotation_invoice.id_quo')->join('quotation_product as q', 'q.id_quo', '=', 'quotation_invoice.id_quo')->where('id_sales', $mine->id_emp)->whereMonth('quotation_invoice.created_at', $mon)->first();

        return view('dashboard.dashboard-sales', [
            'cancel'      => $cancel,
            'cprice'      => $cprice,
            'rfq'         => $rfq,
            'rfqsum'      => $rfqsum,
            'orMonth'     => $orMonth,
            'SumOr'       => $SumOr,
            'rej'         => $rej,
            'app'         => $app,
            'salesAct'    => $salesAct,
            'akt_year'    => $akt_year,
            'akt_pktyear' => $akt_pktyear,
            'close_pktyear'   => $close_pktyear,
            'close_priceyear' => $close_priceyear->harga,
            'close_pktmon'    => $close_pktmon,
            'close_pricemon'  => $close_pricemon->harga,
        ]);
    }

    public function dash_content(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $skup     = ListContent::whereYear('pro_created_date', $year)->Where('pro_status', '=', 'Pending')->count();
        $Lcontent = PendingApproval::where('pro_type', '=', 'Content New SKU')->where('pro_status', '=', 'Approved')
            ->whereYear('created_at', $year)->count();
        $active   = ProductLive::where('status', '=', 0)->whereYear('date_added', $year)->count();
        $Inact    = ProductLive::where('status', '=', 1)->whereYear('date_added', $year)->count();
        $newlist  = ListContent::whereDay('pro_created_date', $day)->where('pro_status', '!=', 'Approved')->count();
        $Pcontent = PendingApproval::where('pro_type',  'Price')->Where('pro_status', '=', 'Approved')
            ->whereYear('created_at', $year)->count();
        $Plist    = ListContent::where('pro_status',  'Reject')->whereYear('pro_created_date', $year)->count();
        $Plist1   = ListContent::where('pro_status',  'Approved')->whereYear('pro_created_date', $year)->count();
        $appw     = PendingApproval::where('pro_status', '=', 'Pending')
            ->whereMonth('created_at', $mon)->count();
        $brand = LiveManModel::select('*')->count();
        $soreq = PendingApproval::where('pro_type', '=', 'Request New SKU')->where('pro_status', 'Approved')->whereMonth('created_at', $mon)->count();
        $mix   = ListContent::select('*')->where('pro_sku', '!=', "null")
            ->orderBy('pro_id', 'DESC')->whereDay('pro_created_date', $day)->limit(6)->get();
        return view('dashboard.dashboard-content', [
            'skup'     => $skup,
            'newlist'  => $newlist,
            'Lcontent' => $Lcontent,
            'Pcontent' => $Pcontent,
            'Plist'    => $Plist,
            'Plist1'   => $Plist1,
            'brand'    => $brand,
            'soreq'    => $soreq,
            'mix'      => $mix,
            'active'   => $active,
            'Inact'    => $Inact,
            'appw'     => $appw,
        ]);
    }

    public function dash_admin(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $quo      = QuotationProduct::where('id_product', 'new')->count();
        $cancel1  = QuotationModel::where('quo_ekskondisi', 'Batal')->where('id_admin', $mine->id_emp)->whereYear('created_at', $year)->count();
        $cprice1  = QuotationModel::where('quo_ekskondisi', 'Batal')->where('id_admin', $mine->id_emp)->whereYear('created_at', $year)->sum('quo_price');
        $rfq1     = QuotationModel::where('id_admin', $mine->id_emp)->whereMonth('created_at', $mon)->count();
        $rfqsum1  = QuotationModel::where('id_admin', $mine->id_emp)->whereMonth('created_at', $mon)->sum('quo_price');
        $orMonth1 = QuotationModel::whereMonth('created_at', $mon)->where('id_admin', $mine->id_emp)->count();
        $SumOr1   = QuotationModel::whereMonth('created_at', $mon)->where('id_admin', $mine->id_emp)->sum('quo_price');
        $app1     = QuotationModel::where('quo_approve_status', 'approve')->where('id_admin', $mine->id_emp)->whereYear('created_at', $year)->count();
        $rej1     = QuotationModel::where('quo_approve_status', 'reject')->where('id_admin', $mine->id_emp)->whereYear('created_at', $year)->count();
        $salesAct1 = QuotationModel::join('activity_paket', 'activity_paket.activity_id_quo', '=', 'quotation_models.id')
            ->where('id_admin', $mine->id_emp)->orderBy('activity_id', 'DESC')
            ->distinct('activity_id_quo')->paginate(3);
        return view('dashboard.dashboard-admin', [
            'cancel1'   => $cancel1,
            'cprice1'   => $cprice1,
            'rfq1'      => $rfq1,
            'rfqsum1'   => $rfqsum1,
            'orMonth1'  => $orMonth1,
            'SumOr1'    => $SumOr1,
            'rej1'      => $rej1,
            'app1'      => $app1,
            'salesAct1' => $salesAct1,
        ]);
    }

    public function dash_warehouse(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $SumOr    = QuotationModel::where('id_sales', $mine->id_emp)->whereMonth('created_at', $mon)->sum('quo_price');
        // $Pven    = Purchase_model::where('id_vendor_beli', null)->count();
        $Papp    = Purchase_order::where('status', 'approve')->whereYear('created_at', $year)->count();
        $Capp    = Purchase_order::where('status', 'approve')->whereYear('created_at', $year)->sum('price');
        $Pcanc   = Purchase_order::where('status', 'reject')->whereYear('created_at', $year)->count();
        $Cprice  = Purchase_order::where('status', 'reject')->whereYear('created_at', $year)->sum('price');
        $ord     = QuotationModel::where('quo_type', '!=', 1)->whereYear('created_at', $year)->count();
        $Pord    = QuotationModel::where('quo_type', '!=', 1)->whereYear('created_at', $year)->sum('quo_price');
        $POkali  = Purchase_order::whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $POprice = Purchase_order::whereMonth('created_at', $mon)->whereYear('created_at', $year)->sum('price');
        $WhIn    = Warehouse_order::where('status', 'in')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $WhOut   = Warehouse_detail::where('kirim_status', 'yes')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $Whwait  = Purchase_order::where('status', '!=', 'order')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->count();
        $PWhwait = Purchase_order::where('status', '!=', 'order')->whereMonth('created_at', $mon)->whereYear('created_at', $year)->sum('price');
        $Whqty   = Warehouse_order::select('*')->join('warehouse_details', 'warehouse_orders.id', '=', 'warehouse_details.id_wo')
            ->orderBy('warehouse_orders.id', 'DESC')->limit(3)->get();
        $Whqty1  = Warehouse_order::select('*')->join('warehouse_details', 'warehouse_orders.id', '=', 'warehouse_details.id_wo')->where('status', 'in')
            ->first();
        return view('dashboard.dashboard-warehouse', [
            'WhIn'      => $WhIn,
            'WhOut'     => $WhOut,
            'Whwait'    => $Whwait,
            'PWhwait'   => $PWhwait,
            'Whqty'     => $Whqty,
            'Whqty1'    => $Whqty1,
            'Cprice'    => $Cprice,
            'Pcanc'     => $Pcanc,
            'SumOr'     => $SumOr,
            'Papp'      => $Papp,
            'Capp'      => $Capp,
            'ord'       => $ord,
            'POkali'    => $POkali,
            'POprice'   => $POprice,
            'Pord'      => $Pord,
        ]);
    }


    public function dash_product(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');
        $now  = Carbon::now()->format('d/m/Y');

        $skup     = ListContent::whereYear('pro_created_date', $year)->Where('pro_status', '=', 'Pending')->count();
        $Lcontent = PendingApproval::where('pro_type', '=', 'Content New SKU')->where('pro_status', '=', 'Approved')
            ->whereYear('created_at', $year)->count();
        $active   = ProductLive::where('status', '=', 0)->whereYear('date_added', $year)->count();
        $Inact    = ProductLive::where('status', '=', 1)->whereYear('date_added', $year)->count();
        $newlist  = ListContent::whereDay('pro_created_date', $day)->where('pro_status', '!=', 'Approved')->count();
        $Pcontent = PendingApproval::where('pro_type',  'Price')->Where('pro_status', '=', 'Approved')
            ->whereYear('created_at', $year)->count();
        $Plist    = ListContent::where('pro_status',  'Reject')->whereYear('pro_created_date', $year)->count();
        $Plist1   = ListContent::where('pro_status',  'Approved')->whereYear('pro_created_date', $year)->count();
        $appw     = PendingApproval::where('pro_status', '=', 'Pending')
            ->whereMonth('created_at', $mon)->count();
        $brand = LiveManModel::select('*')->count();
        $soreq = PendingApproval::where('pro_type', '=', 'Request New SKU')->where('pro_status', 'Approved')->whereMonth('created_at', $mon)->count();
        $mix   = ListContent::select('*')->where('pro_sku', '!=', "null")
            ->orderBy('pro_id', 'DESC')->whereDay('pro_created_date', $day)->limit(6)->get();

        return view('dashboard.dashboard-product', [
            'skup'     => $skup,
            'newlist'  => $newlist,
            'Lcontent' => $Lcontent,
            'Pcontent' => $Pcontent,
            'Plist'    => $Plist,
            'Plist1'   => $Plist1,
            'brand'    => $brand,
            'soreq'    => $soreq,
            'mix'      => $mix,
            'active'   => $active,
            'Inact'    => $Inact,
            'appw'     => $appw,
        ]);
    }

    public function Notify(Request $request)
    {
        // dd($request);
        $req = $request->division;
        $div = Role_division::where('id', $request->division)->first()->div_name;
        $mine = getUserEmp(Auth::id());
        $usr = getEmp(Auth::id());
        $Year = Carbon::now()->format('Y');
        $now = Carbon::now()->format('m');
        $date = Carbon::createFromFormat('m', $now)->subMonth();
        if ($req == 1) {
            $req = QuotationProduct::where('id_product', '=', 'new')->count();
            $comment = "Request Produk SKU Baru";
            $com2 = "Produk";
        } else if ($req == 2) {
            $req = PendingApproval::where('pro_type', '!=', 'New')->where('pro_status', 'Pending')->whereYear('created_at', $Year)->count();
            $comment = "Update Data Produk Tahun " . $Year;
            $com2 = "Request Pending";
        } else if ($req == 3) {
            $req = $div;
            $comment = "Active";
            $com2 = "";
        } else if ($req == 4) {
            $req = $div;
            $comment = "Belum Dicari";
            $com2 = "Order";
        } else if ($req == 5) {
            $req = Warehouse_detail::where('kirim_status', 'yes')->whereMonth('created_at', $now)->whereYear('created_at', $Year)->count();
            $comment = "Sudah Dikirim Bulan Ini";
            $com2 = "Purchase Order";
        } else if ($req == 6) {
            $req     = Purchase_order::where('status', 'draft')->whereYear('created_at', $Year)->count();
            $comment = "Status Masih Draft";
            $com2 = "Purchase Order";
        } else if ($req == 7) {
            $req = $div;
            $comment = " ";
            $com2 = " ";
        } else if ($req == 8) {
            $req = $div;
            $comment = "Belum Dicari";
            $com2 = "Order";
        } else if ($req == 9) {
            $req = QuotationModel::where('quo_ekskondisi', 'Masih Negosiasi')->where('id_sales', $mine->id_emp)->count();
            $comment = "Kondisi Masih Negosiasi";
            $com2 = "Order";
        } else if ($req == 10) {
            $req = QuotationModel::where('quo_ekskondisi', 'Masih Negosiasi')->count();
            $comment = "Kondisi Masih Negosiasi";
            $com2 = "Order";
        } else {
            $req = $div;
            $comment = "Belum Dicari";
            $com2 = "Order";
        }
        return view('dashboard.dashboard-notif', compact("req", "comment", "com2"));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

    public function filter_data(Request $request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        if ($request->what == 'product') {
            $this->ajax_homeproduct($request);
        } else if ($request->what == 'content') {
            $this->ajax_homecontent($request);
        } else if ($request->what == 'warehouse') {
            $this->ajax_homewarehouse($request);
        } else if ($request->what == 'sales') {
            $this->ajax_homesales($request);
        } else if ($request->what == 'purchasing') {
            $this->ajax_homepurchasing($request);
        } else if ($request->what == 'admin') {
            $this->ajax_homeadmin($request);
        }else if ($request->what == "finance"){
            $this->ajax_homefinance($request);
        }else if ($request->what == "finance_settle"){
            $this->ajax_homefinance_settle($request);
        }else if ($request->what == "hrd"){
            $this->ajax_homehrd($request);
        }
    }



    public function ajax_homehrd(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'tujuan',
            2 => 'type',
            3 => 'status',
            4 => 'created_by',
            5 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Req_LeaveModel::where('status','Pending')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Req_LeaveModel::select('*')->where('status','Pending')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = Req_LeaveModel::where('status','Pending')->where('no_cashadv', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Req_LeaveModel::where('status','Pending')->where('no_cashadv', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'            => $post->id,
                    'tujuan'        => $post->purpose,
                    'type'          => $post->type_leave,
                    'status'        => $post->status,
                    'created_by'    => getUserEmp($post->created_by)->name,
                    'created_at'    => Carbon::parse($post->created_at)->format('d - F - Y'),
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



    public function ajax_homefinance(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'no_cashadv',
            2 => 'status',
            3 => 'created_by',
            4 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = CashAdvance::where('status','Pending')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = CashAdvance::select('*')->where('status','Pending')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = CashAdvance::where('status','Pending')->where('no_cashadv', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = CashAdvance::where('status','Pending')->where('no_cashadv', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'            => $post->id,
                    'no_cashadv'    => $post->no_cashadv,
                    'status'        => $post->status,
                    'created_by'    => getUserEmp($post->created_by)->name,
                    'created_at'    => Carbon::parse($post->created_at)->format('d - m - Y'),
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

    public function ajax_homefinance_settle(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'no_settlement',
            2 => 'status',
            3 => 'created_by',
            4 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = FinanceSettlementModel::where('status','Pending')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = FinanceSettlementModel::select('*')->where('status','Pending')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = FinanceSettlementModel::where('status','Pending')->where('no_settlement', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = FinanceSettlementModel::where('status','Pending')->where('no_settlement', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'            => $post->id,
                    'no_settlement' => $post->no_settlement,
                    'status'        => $post->status,
                    'created_by'    => getUserEmp($post->created_by)->name,
                    'created_at'    => Carbon::parse($post->created_at)->format('d - m - Y'),
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




    public function ajax_homeproduct(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'id_admin',
            4 => 'id_sales',
            5 => 'created_at',
            6 => 'created_by',
            7 => 'quo_eksstatus',
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
                ->orderby('quotation_models.created_at', $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = QuotationModel::where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'            => $post->id,
                    'qty'           => $this->countproduct($post->id),
                    'quo_no'        => $post->quo_no,
                    'quo_name'      => $post->quo_name,
                    'id_customer'   => getCustomer($post->id_customer)->company,
                    'id_admin'      => getEmp($post->id_admin)->emp_name,
                    'id_sales'      => getEmp($post->id_sales)->emp_name,
                    'created_at'    => Carbon::parse($post->created_at)->format('d/m/Y'),
                    'created_by'    => getUserEmp($post->created_by)->emp_name,
                    'quo_eksstatus' => $post->quo_eksstatus,
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

    public function ajax_homecontent(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'id_product_request',
            4 => 'det_quo_qty',
            5 => 'det_quo_harga_req',
            6 => 'created_by',
            7 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationProduct::where('id_product', 'new')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = QuotationProduct::select('*')->join('quotation_models', 'quotation_product.id_quo', '=', 'quotation_models.id')->where('id_product', 'new')
                ->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationProduct::select('*')->join('quotation_models', 'quotation_product.id_quo', '=', 'quotation_models.id')->where('id_product', 'new')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = QuotationProduct::select('*')->join('quotation_models', 'quotation_product.id_quo', '=', 'quotation_models.id')->where('id_product', 'new')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'         => $post->id_quo,
                    'quo_no'     => getQuo($post->id_quo)->quo_no,
                    'name'       => getQuo($post->id_quo)->quo_name,
                    'request'    => getProductReq($post->id_product_request)->req_product,
                    'id_customer'=> getCustomer($post->id_customer)->company,
                    'quo_type'   => getQuoType($post->quo_type)->type_name,
                    'quo_color'  => getQuoType($post->quo_type)->color,
                    'qty'        => $post->det_quo_qty,
                    'harga'      => $post->det_quo_harga_req,
                    'user'       => getUserEmp($post->created_by)->emp_name,
                    'created_at' => Carbon::parse($post->created_at)->format('d/m/Y'),
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

    public function ajax_homesales(Request $request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $usr = getEmp(Auth::id());
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'id_customer',
            4 => 'id_admin',
            5 => 'id_sales',
            6 => 'quo_order_at',
            7 => 'updated_at',
            8 => 'quo_approve_status',
            9 => 'quo_price',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::where('id_sales', $mine->id_emp)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::select('*')->where('id_sales', $mine->id_emp)
                ->offset($start)->limit($limit)->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = QuotationModel::where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'                 => $post->id,
                    'quo_no'             => $post->quo_no,
                    'quo_name'           => $post->quo_name,
                    'id_customer'        => getCustomer($post->id_customer)->company,
                    'id_admin'           => getEmp($post->id_admin)->emp_name,
                    'id_sales'           => getEmp($post->id_sales)->emp_name,
                    'quo_order_at'       => $post->quo_order_at,
                    'updated_at'         => Carbon::parse($post->updated_at)->format('Y-m-d'),
                    'quo_approve_status' => $post->quo_approve_status,
                    'quo_price'          => $post->quo_price,
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


    public function ajax_homeadmin(Request $request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'id_customer',
            4 => 'id_sales',
            5 => 'created_at',
            6 => 'quo_eksstatus',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::where('id_admin', $mine->id_emp)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::where('id_admin', $mine->id_emp)
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::select('*')->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationModel::select('*')->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'                 => $post->id,
                    'quo_no'             => $post->quo_no,
                    'quo_name'           => $post->quo_name,
                    'id_customer'        => getCustomer($post->id_customer)->company,
                    'id_sales'           => getEmp($post->id_sales)->emp_name,
                    'created_at'         => Carbon::parse($post->created_at)->format('d/m/Y'),
                    'quo_eksstatus'      => $post->quo_eksstatus,
                    'quo_ekskondisi'     => $post->quo_ekskondisi,
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

    public function ajax_homepurchasing(Request $request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $usr = getEmp(Auth::id());
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'qty',
            4 => 'id_customer',
            5 => 'id_sales',
            6 => 'created_by',
            7 => 'quo_order_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::where('quo_type', '!=', 1)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::where('quo_type', '!=', 1)
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::select('*')->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationModel::select('*')->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'           => $post->id,
                    'id_quo'       => 'SO' . sprintf("%06d", $post->id),
                    'quo_no'       => $post->quo_no,
                    'quo_name'     => $post->quo_name,
                    'qty'          => $this->countproduct($post->id),
                    'id_customer'  => getCustomer($post->id_customer)->company,
                    'id_sales'     => getEmp($post->id_sales)->emp_name,
                    'created_by'   => getEmp($post->created_by)->emp_name,
                    'quo_order_at' => $post->quo_order_at,
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


    public function ajax_homewarehouse(Request $request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'no_po',
            2 => 'id_quo',
            3 => 'id_vendor',
            4 => 'status',
            // 5 => 'position',
            5 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Purchase_order::where('status', '=', 'order')->orderBy('id', 'DESC')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Purchase_order::select('*')->where('status', '=', 'order')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = Purchase_order::where('po_number', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Purchase_order::where('po_number', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($post->status == "order") {
                    $pos = "Diproses Vendor";
                } else {
                    $pos = "Menunggu Purchase";
                }
                $data[] = [
                    'id'         => $post->id,
                    'no_po'      => $post->po_number,
                    'id_quo'     => $post->id_quo,
                    'id_vendor'  => getVendor($post->id_vendor)->vendor_name,
                    'status'     => $post->status,
                    'position'   => $pos,
                    'created_at' => Carbon::parse($post->updated_at)->format('d-m-Y'),
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

    public function countproduct($id)
    {
        $count = QuotationProduct::where('id_quo', $id)->count();
        return $count;
    }

    public function Act()
    {

        $name = ActQuoModel::select('activity_name')->where('activity_id_quo', $id)->join('quotation_models', 'activity_paket.activity_id_quo', '=', 'quotation_models.id')
            ->orderBy('activity_id', 'DESC')->limit(1)->get();
        return $name;
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

//////////////// CARD ///////
    ////////////////////////


        public function data_card(Request $request)
    {
        $now    = Carbon::now();
        $format = Carbon::now()->format('Y-m-d');
        
        $main = [
            'mng' => $this->card_mng($request, $now, $format),
        ];

        if($request->type=="mng")
        {
            $view = "card-mng";
        }else{
            $view = "card-mng";
        }
        return view('dashboard.card.'.$view, [
            'main'     => $main,
        ]);
        
    }


    public function card_mng($request, $now, $format)
    {
        $so_today   = QuotationModel::whereDate('created_at', $format)->get()->count();
        $so_approve = ActQuoModel::join('quotation_models', 'quotation_models.id', '=', 'activity_paket.activity_id_quo')
        ->where('activity_name', 'LIKE', '%Paket ini sudah di approve%')->whereDate('activity_created_date',$format)->get()->count();
        
        $so_batal   = QuotationModel::where('quo_ekskondisi', 'Batal')->whereYear('created_at', Carbon::parse($now)->format('Y'))->get()->count();
        $so_closing = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')->whereYear('quotation_invoice.created_at', Carbon::parse($now)->format('Y'))->count();
        
        $data       = [
            'so_today'     => $so_today.' Sales Order',
            'note_today'   => "Di Input Hari Ini",
            
            'so_approve'   => $so_approve.' Sales Order',
            'note_approve' => "Telah Disetujui Bulan ".Carbon::parse($now)->format('F'),

            'so_batal'     => $so_batal.' Sales Order',
            'note_batal'   => "Telah Batal Tahun ".Carbon::parse($now)->format('Y'),

            'so_closing'   => $so_closing.' Sales Order',
            'note_closing' => "Total Invoicing Tahun ".Carbon::parse($now)->format('Y'),
        ];
        return $data;
    }











}