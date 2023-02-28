<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Models\Finance\CashAdvance;
use App\Models\Finance\CashAdvanceDesc;
use App\Models\Finance\CashAdvanceApp;
use App\Models\Finance\CashAdvanceHistory;
use App\Models\Finance\CashAdvSettlement;
use App\Models\Finance\FinanceSettlementModel;
use App\Models\Finance\FinanceSettlementDetail;
use App\Models\Finance\FinanceSettlementApp;
use App\Models\Finance\FinanceHistory;
use App\Models\Finance\SettlementDetail;
use App\Http\Controllers\Controller;
use App\Models\role_division;
use App\Models\HR\EmployeeModel;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use PDF;
use DB;
use Storage;

class SettlementController extends Controller
{
    public function index()
    {
        return view('finance.settlement.index');
    }


    public function getCash(){
        $cash = CashAdvance::where('created_by', Auth::id())->get();
        $arr  = array();
        foreach ($cash as $reg) {
            $sets = FinanceSettlementModel::where([['no_ref', $reg->no_cashadv], ['status', '!=', 'Rejected']])->first();
            if($sets){
                $arr ==null;
            }else{
            $tujuan = $reg->type_cash=="dinas" ? province($reg->des_provinsi) : $reg->des_tujuan;
            $arr[$reg->id] = '['. $reg->no_cashadv.'] - '.emp_name($reg->emp_id) .' - '.$tujuan;
        }
    }
        return $arr;
    }

    function find_cash(Request $request)
{
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data   = CashAdvance::select("id", "no_cashadv", "nama_emp","des_kota")
                ->where('no_cashadv', 'LIKE', "%$search%")
                ->where('nama_emp', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
}

function get_value(Request $request)
{
    // dd($request);
    $cash  = CashAdvance::where('id', $request->id)->first();
    return response()->json([
        'data' =>[
           'cash'          => $cash,
           'prov'          => $cash->des_provinsi,
           'city'          => city($cash->des_kota),
           'tgl_berangkat' => Carbon::parse($cash->tgl_berangkat)->format('d-F-Y'),
           'tgl_pulang'    => Carbon::parse($cash->tgl_pulang)->format('d-F-Y'),
        ],
    ]);
    }

    function blank_forms(Request $request)
{
        $cash = CashAdvance::where('created_by', Auth::id())->get();
        return view('finance.settlement.attribute.blank_form',[
            'employee' => $this->AllEmp(),
            'province' => $this->get_province(),
            'city'     => [],
            'cash'     => $cash,
            'user'     => Auth::id(),
            'cash'     => $this->getCash(),
            'action'   => 'Finance\SettlementController@store',
            'method'   => 'post',
        ]);    
    }



function get_cash(Request $request)
{
    $cash        = CashAdvance::where('id', $request->id)->first();
    $cash_dtl    = CashAdvanceDesc::where('id_cash', $request->id)->get();
    $cash_app    = CashAdvanceApp::where('id_cash', $request->id)->get();
    $total_biaya = CashAdvanceDesc::where('id_cash', $request->id)->sum('est_biaya');
    return view('finance.settlement.attribute.with_cash',[
        'cash'        => $cash,
        'cash_dtl'    => $cash_dtl,
        'cash_app'    => $cash_app,
        'cdtl'        => count($cash_dtl),  
        'total_biaya' => $total_biaya,
        'capp'        => count($cash_app),
        'dtls'        => count($cash_dtl),
        'divisi'      => div_name($cash->div_id),
    ]);
}


function add_kegiatan(Request $request)
{
    // dd($request);
    return view("finance.settlement.attribute.add_kegiatan",[
        'n_equ'  => $request->n_equ,
        ]);
}





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cash = CashAdvance::where('created_by', Auth::id())->get();
        return view('finance.settlement.create',[
            'employee' => $this->AllEmp(),
            'province' => $this->get_province(),
            'city'     => [],
            'cash'     => $cash,
            'user'     => Auth::id(),
            'emp_data' => getUserEmp(Auth::id()),
            'cashs'    => $this->getCash(),
            'action'   => 'Finance\SettlementController@store',
            'method'   => 'post',
        ]);    
    }

    public function create_seattlement(Request $request)
    {
        $id= $request->segment(3);
        $mine = getUserEmp(Auth::id());
        $emp  = EmployeeModel::where('spv_id', $mine->id)->first();
        $cash = CashAdvance::where('id', $id)->first();
        $dtl  = CashAdvanceDesc::where('id_cash', $id)->get();
        $dtls = CashAdvanceDesc::where('id_cash', $id)->first();
        $capp = CashAdvanceApp::where('id_cash',$id)->first();
        $app  = CashAdvanceApp::where('id_cash', $id)->get();
        $sett = SettlementModel::where('id_cash', $id)->first();
        return view('finance.settlement.create',[
             'cash'  => $cash,
             'emp'   => $emp,
             'mine'  => $mine,
             'capp'  => $capp,
             'dtls'  => $dtls,
             'set'   => $sett,
             'app'   => $app,
             'divisi'=> div_name($cash->div_id),
             'dtl'   => $dtl,
         ]);
    }

    


    

    public function totalsmenustotalsmenus()
    {

        $mine = getUserEmp(Auth::id());
        $emp = EmployeeModel::where('division_id', '=', 9 )
        ->get();
        
        
        return view('finance.salessetcost.index',[

            'emp'      => $emp,
           
            
         ]);
    }

   




    public function saveSettlement(Request $request, $id=0 ){
        $sett =[
            'set_files' => $request->has('files') ? Storage::disk('public')->put('cash_advance', $request->file('files')) : null,
            'note'      => $request->input('note'),
            'set_status'=> "Submitted By Finance",
        ];
        $cash = [
            'status'    => "Submitted By Finance",
            'status_set'=> 'Submitted',
        ];
        $setss = SettlementModel::where('id_cash', $request->id)->update($sett);
        $cashh = CashAdvance::where('id', $request->id)->update($cash);
        return redirect('finance/settlement')->with('success', 'Created Successfully');
    }


    function set_update(Request $request)
    {
        $cash = CashAdvance::where('id', $request->id)->first();
        $dtl  = CashAdvanceDesc::where('id_cash', $request->id)->get();
        $sett = SettlementModel::where('id_cash', $request->id)->first();
        $set = [
            'set_files' => $request->has('files') ? Storage::disk('public')->put('cash_advance', $request->file('files')) : $sett->set_files,
        ];
        $setss = SettlementModel::where('id_cash', $request->id)->update($set);
        // return redirect('finance/settlement/'.$request->id.'/create');
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
        return $this->save($request, 'created');
    }


    public function save($request, $save, $id=0)
    { 
        // dd($request);
        $cash = CashAdvance::where('id', $request->id_cash)->first();
        $sets = FinanceSettlementModel::max('id');
        $type = $request->type_settlement;
        $mtd  = $type=="with_cash" ? $cash->mtd_cash : $request->mtd_cash;
        $data = [
            'employee_id'   => $type=="with_cash" ? $cash->emp_id : $request->emp_id,
            'no_settlement' => 'MEG-ST/'.Carbon::now()->format('my').sprintf("%03d",($sets==null ? '1' : ($sets+1) )).'-'.getInisial(emp_name($type=="with_cash" ? $cash->emp_id : $request->emp_id)),
            'no_ref'        => $type=="with_cash" ? $cash->no_cashadv : null,
            'status_note'   => $type=="with_cash" ? "Cash Advance" : null,
            'mtd_payment'   => $mtd,
            'acc_bank'      => $type=="with_cash" && $cash->mtd_cash=="Transfer" ? $cash->rek_bank : $request->acc_bank, 
            'no_acc_bank'   => $type=="with_cash" && $cash->mtd_cash=="Transfer" ? $cash->no_rek : $request->no_acc_bank, 
            'name_acc'      => $type=="with_cash" && $cash->mtd_cash=="Transfer" ? $cash->nama_rek : $request->name_acc, 
            'cabang_bank'   => $type=="with_cash" && $cash->mtd_cash=="Transfer" ? $cash->cabang_rek : $request->cabang_bank, 
            'status'        => "Pending",
            'lama_hari'     => $type=="with_cash" ? $cash->est_waktu : $request->lama_hari,
            'ref_notes'     => $type=="with_cash" ? "Cash Advance" : null,
            'total_biaya'   => $type=="with_cash" ? $request->hasil : null,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry = FinanceSettlementModel::create($data);
        $histss= [ 
            'activity_name'      => "Menyimpan Data Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi'  => $qry->no_settlement,
        ];
        
        $actss = FinanceHistory::create($histss);
        if($qry)
        {
            $tujuan = $request->tujuan;
            foreach($tujuan as $item => $q){
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$item])){
                    $files    = $request->file('file_set')[$item];
                    $name     = rand()." ".$qry->id."-".$qry->created_by."-".Carbon::parse($qry->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName = null;
            }
            $data_dtl = [
                    'id_settlement'  => $qry->id,
                    'tujuan'         => $request->tujuan[$item],
                    'qty'            => $request->qty[$item], 
                    'est_biaya'      => $request->biaya[$item],
                    'notes'          => $request->note[$item],
                    'file_set'       => !empty($request->file('file_set')[$item]) ? $newName : null,
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'     => Auth::id(),
                ];
                $qry1 = FinanceSettlementDetail::create($data_dtl);
            }
        }


        if($request->has('tujuan_add'))
        {
            $tujuan_add = $request->tujuan_add;
            foreach($tujuan_add as $add => $q){
            $data_dtl = [
                    'id_settlement'  => $qry->id,
                    'tujuan'         => $request->tujuan_add[$add],
                    'qty'            => $request->qty_add[$add], 
                    'est_biaya'      => $request->biaya_add[$add],
                    'notes'          => $request->note_add[$add],
                    'file_set'       => !empty($request->file('file_set_add')[$add]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set_add')[$add], rand()." ".$request->file('file_set_add')[$add]->getClientOriginalName()) : null,
                    'created_at'     => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'     => Auth::id(),
                ];
                $qry1 = FinanceSettlementDetail::create($data_dtl);
            }
        }
        return redirect('finance/settlement')->with('success', 'Created Settlement Successfully');
    } 



    function save_data(Request $request)
    {
        dd($request);
    }
    

    function deleteItem(Request $request)
    {
        $settle = FinanceSettlementDetail::find($request->id);
        $settle->delete();
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
        $mine = getUserEmp(Auth::id());
        $auth = Auth::id();
        $sett = FinanceSettlementModel::where('id', $id)->first();
        $emp  = getEmp($sett->employee_id);
        $usr  = getUserEmp($sett->created_by);
        $dtl  = FinanceSettlementDetail::where('id_settlement', $sett->id)->get();
        $cash = CashAdvance::where('no_cashadv', $sett->no_ref)->first();
        $hist = FinanceHistory::where('activity_refrensi', $sett->no_settlement)->orderBy('id', 'desc')->get();
        return view('finance.settlement.show',[
             'emp'      => $emp,
             'cash'     => $cash,
             'dtls'     => $dtl,
             'hist'     => $hist,
             'usr'      => $usr,
             'mine'     => $mine,
             'set'      => $sett,
             'cdtl'     => count($dtl),
         ]);
    }








    public function approve($id, $user) 
    {
        // dd($id);
        $mine        = getUserEmp(Auth::id());
        $emp         = EmployeeModel::where('spv_id', $mine->id)->first();
        $set         = FinanceSettlementModel::where('id', $id)->first();
         if ($user == "mng" && in_array($mine->id,explode(',',getConfig('app_finance'))) )
         {    
            return $this->approve_finance($id);
         }else if($user == "mng" && in_array($mine->id,explode(',',getConfig('ajaxmng'))))
         {
            return $this->approve_director($id);
         }
         else if(in_array($set->employee_id,explode(',',getConfig('fin_auto_approve')))) {
            return $this->auto_approve($id);
         }else{
            $mine        = getUserEmp(Auth::id());
            $emp         = EmployeeModel::where('spv_id', $mine->id)->first();
            $set         = FinanceSettlementModel::where('id', $id)->first();
            $dtl         = FinanceSettlementDetail::where('id_settlement', $id)->get();
            $app         = FinanceSettlementApp::where('id_settlement', $id)->get();
            
            if(in_array($mine->id,explode(',',getConfig('ajaxmng')))){
                $cash        = [
                        'status'           => "Approved",
                        'app_manage'     => Auth::id(),
                        'tgl_app_manage' => Carbon::now('GMT+7')->toDateTimeString(),
                        'app_director'     => Auth::id(),
                        'tgl_app_director' => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                $qry  = FinanceSettlementModel::where('id', $id)->update($cash);
            }else if(in_array($mine->id,explode(',',getConfig('app_finance'))) ){
                $cash        = [
                        'status'           => "Approved",
                        'app_manage'       => Auth::id(),
                        'tgl_app_manage'   => Carbon::now('GMT+7')->toDateTimeString(),
                        'app_finance'     => Auth::id(),
                        'tgl_app_finance' => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                $qry  = FinanceSettlementModel::where('id', $id)->update($cash);
            }
            else{
                $cash        = [
                    'status'         => "Approved",
                    'app_manage'     => Auth::id(),
                    'tgl_app_manage' => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                $qry  = FinanceSettlementModel::where('id', $id)->update($cash);
            }

        $histss= [
            'activity_name'      => "Menyetujui Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        if($actss)
        {
            SendEmailSettleApproval($set->id, "spv");
        }
        return redirect('finance/settlement')->with('success', 'Approved Successfully');
    }
}


    public function auto_approve($id)
    {
        // dd($id);
        $set         = FinanceSettlementModel::where('id', $id)->first();
         $cash        = [
             'status'          => "Approved",
             'app_manage'      => Auth::id(),
             'app_finance'     => Auth::id(),
             'tgl_app_finance' => Carbon::now('GMT+7')->toDateTimeString(),
             'tgl_app_manage'  => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $qry  = FinanceSettlementModel::where('id', $id)->update($cash);
        // }

        $histss= [
            'activity_name'      => "Menyetujui Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        if($actss)
        {
            SendEmailSettleApproval($set->id, "finance");
        }
        return redirect('finance/settlement')->with('success', 'Approved Successfully');
    }



    public function approve_director($id)
    {
        // dd($id);
        $set         = FinanceSettlementModel::where('id', $id)->first();
         $cash        = [
             'status'           => "Approved",
             'app_director'     => Auth::id(),
             'tgl_app_director' => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $qry  = FinanceSettlementModel::where('id', $id)->update($cash);
        // }

        $histss= [
            'activity_name'      => "Menyetujui Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        if($actss)
        {
            SendEmailSettleApproval($set->id, "finance");
        }
        return redirect('finance/settlement')->with('success', 'Approved Successfully');
    }
    


    public function approve_finance($id)
    {
        // dd($id);
        $set         = FinanceSettlementModel::where('id', $id)->first();
         $cash        = [
             'status'          => "Approved",
             'app_finance'     => Auth::id(),
             'tgl_app_finance' => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $qry  = FinanceSettlementModel::where('id', $id)->update($cash);
        // }

        $histss= [
            'activity_name'      => "Menyetujui Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        if($actss)
        {
            SendEmailSettleApproval($set->id, "finance");
        }
        return redirect('finance/settlement')->with('success', 'Approved Successfully');
    }
    

    public function reject($id, $user) 
    {
        // dd($id);
         $mine        = getUserEmp(Auth::id());
         if ($user == "mng" || in_array($mine->id,explode(',',getConfig('app_finance'))) )
         {    
            return $this->reject_finance($id);
         }else{
         $mine        = getUserEmp(Auth::id());
         $emp         = EmployeeModel::where('spv_id', $mine->id)->first();
         $set         = FinanceSettlementModel::where('id', $id)->first();
         $dtl         = FinanceSettlementDetail::where('id_settlement', $id)->get();
         $app         = FinanceSettlementApp::where('id_settlement', $id)->get();
        //  $approval_by = $user== 'mng' ? "Finance Manager" : "Supervisor";
        //  $app_done    =  $user== 'mng' ? "app_finance" : "app_manage";           
         
        //  $data_apps = [
        //      'id_settlement' => $id,
        //      'approval_by'   => $approval_by,
        //      'status_app'    => "Rejected",
        //      'status_by'     => $mine->id,
        //      'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        //      'created_by'    => Auth::id(),
        //  ];
        //  $app = FinanceSettlementApp::insert($data_apps);
        //  if($app){
         $cash        = [
             'status'         => "Rejected",
             'app_manage'     => Auth::id(),
             'tgl_app_manage' => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $qry    = FinanceSettlementModel::where('id', $id)->update($cash);
        // } 

        $histss= [
            'activity_name'      => "Tidak Menyetujui Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        return redirect('finance/settlement')->with('success', 'Approved Successfully');
    }
}


public function reject_finance($id) 
{
        $set         = FinanceSettlementModel::where('id', $id)->first();
         $cash        = [
             'status'         => "Rejected",
             'app_finance'     => Auth::id(),
             'tgl_app_finance' => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $qry    = FinanceSettlementModel::where('id', $id)->update($cash);
        // } 

        $histss= [
            'activity_name'      => "Tidak Menyetujui Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        return redirect('finance/settlement')->with('success', 'Approved Successfully');
}

public function ajukan($id)
{
        $set  = FinanceSettlementModel::where('id', $id)->first();
        $cash = [
             'status'     => "Need Approval",
             'updated_by' => Auth::id(),
             'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
         ];
        $histss= [
            'activity_name'      => "Mengajukan Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi'  => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        $qry   = FinanceSettlementModel::where('id', $id)->update($cash);
        if($qry)
        {
            // SendEmailSettle($set->id, getUserEmp($set->created_by)->spv_id);
        }
        return redirect('finance/settlement')->with('success', 'Waiting For Approval');
    }


    public function editby_Finance(Request $request)
    {
        $set   = FinanceSettlementModel::where('id', $request->id)->first();
        return view('finance.settlement.attribute.form_finance',[
            'set' => $set,
        ]);
    }


    public function completed(Request $request)
    {
        // dd($request);
         $id    = $request->id;
         $mine  = getUserEmp(Auth::id());
         $set   = FinanceSettlementModel::where('id', $id)->first();
         $cash  = CashAdvance::where('no_cashadv', $set->no_ref)->first();
         $dtl   = FinanceSettlementDetail::where('id_settlement', $id)->get();
         $total = FinanceSettlementDetail::select(DB::raw('(qty*est_biaya) as kali'))->where('id_settlement', $id)->get()->sum('kali');
         $type = $set->no_ref!=null ? "with_cash" : "set";
         $app   = FinanceSettlementApp::where('id_settlement', $id)->get();       
            if ($request->has('doc_finance_settle')) {
                if(!empty($request->file('doc_finance_settle'))){
                    $files    = $request->file('doc_finance_settle');
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName = $set->doc_finance_settle;
            }
         $cash  = [
             'status'             => "Completed",
             'biaya_finance'      => $request->biaya_finance,
             'notes_finance'      => $request->note,
             'tgl_transfer'       => $request->tgl_transfer,
             'status_note'        => $set->no_ref==null && $set->biaya_finance>$total ? "Processed" : "Done Settle",
             "doc_finance_settle" => $newName,
         ];
         
         $data_apps = [
             'id_settlement' => $id,
             'status_app'    => "Completed",
             'status_by'     => $mine->id,
             'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
             'created_by'    => Auth::id(),
         ];

        $text = $request->tgl_transfer !=null ? ", Tanggal Transfer ".$request->tgl_transfer : null;
        $histss= [
            'activity_name'      => "Completed Settlement".$text,
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
            $actss = FinanceHistory::create($histss);
            $app   = FinanceSettlementApp::insert($data_apps);
            $qry   = FinanceSettlementModel::where('id', $id)->update($cash);

        return redirect('finance/settlement/'.$id.'/show')->with('success', 'Completed Successfully');
    }

    public function add_note(Request $request)
    {
        $sett = FinanceSettlementModel::where('id', $request->id)->first();
        $cash = CashAdvance::where('no_cashadv', $sett->no_ref)->first();
        $get  = FinanceSettlementDetail::where('id_settlement', $request->id)->get()->sum('est_biaya');
        
        return view('finance.settlement.attribute.pay_back',[
            'set'    => $request->id,
            'sets'   => $sett,
            'cash'   => $cash,
            'jumlah' => abs($get - $cash->biaya_finance),
        ]);
    }


    public function all_done($id)
    {
        $sett = FinanceSettlementModel::where('id', $id)->first();
        $qry = [
            'status'      => "Completed",
            'status_note' => "Done Settle",
        ];

        $histss= [
            'activity_name'      => "Completed Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $sett->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        $qry   = FinanceSettlementModel::where('id', $id)->update($qry);
        return redirect('finance/settlement/'.$id.'/show')->with('success', 'Completed Successfully');
    }


    public function proccess_completed(Request $request)
    {
        return view('finance.settlement.attribute.form_finance',[
            'set' => $request->id,
        ]);
    }


    public function saveadd_note(Request $request)
    {
        // dd($request);
        $set      = FinanceSettlementModel::where('id', $request->id)->first();
        $cash     = CashAdvance::where('no_cashadv', $set->no_ref)->first();
        $total = FinanceSettlementDetail::select(DB::raw('(qty*est_biaya) as kali'))->where('id_settlement', $request->id)->get()->sum('kali');
        $save = [
            'note_kembali' => $request->note_kembali,
            'sisa_biaya'   => $request->sisa_biaya,
            'status_note'  => $set->no_ref==null && $set->biaya_finance>$total ? "Processed" : "Done Settle",
            'tf_payback'   => $request->tf_payback,
            'doc_pay_back' => !empty($request->file('doc_pay')) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('doc_pay'), $set->id."-".$set->created_by.'-'.Carbon::parse($set->created_at)->format('d').$request->file('doc_pay')->getClientOriginalName()) : $set->doc_pay_back,
            'updated_by'   => Auth::id(),
            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $histss= [
            'activity_name'      => "Biaya Kembali ".$request->sisa_biaya,
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);
        $qrys = FinanceSettlementModel::where('id', $request->id)->update($save);
        return redirect('finance/settlement/'. $request->id.'/show');
    }


    public function print_settlement($id) 
    {
        $set     = FinanceSettlementModel::where('id', $id)->first();
        $dtl     = FinanceSettlementDetail::where('id_settlement', $set->id)->get();
        $cash    = CashAdvance::where('no_cashadv', $set->no_ref)->first();
        $app_set = FinanceSettlementApp::where('id_settlement', $set->id)->where('approval_by','Supervisor')->orderBy('id', 'desc')->first();
        $finance = FinanceSettlementApp::where('id_settlement', $set->id)->where('approval_by','Finance Manager')->orderBy('id', 'desc')->first();
        $pdf  = PDF::loadview('pdf.cash_adv_settlement',[
            'set'     => $set,
            'dtl'     => $dtl,
            'app_set' => $app_set,
            'finance' => $finance,
            'cash'    => $cash,
            'time'    => Carbon::now('GMT+7')->format('d F Y')
        ]);
        return $pdf->download('MEG Settlement-'.$set->no_settlement.'.pdf');        
    }



    public function remove(Request $request) 
    {
        DB::table('finance_cash_adv_detail')->join('finance_cash_adv', 'finance_cash_adv.id', '=', 'finance_cash_adv_detail.id_cash')->where('finance_cash_adv_detail.id', $request->id)->delete();        $cash_dtl = CashAdvance::where('id', $request->id)->first();
    }    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $idfsa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $sets        = FinanceSettlementModel::where('id', $id)->first();
        $set_dtl     = FinanceSettlementDetail::where('id_settlement', $id)->get();
        $cash        = CashAdvance::where('no_cashadv', $sets->no_ref)->first();
        $total_biaya = $cash!=null ? CashAdvanceDesc::where('id_cash', $cash->id)->sum('est_biaya') :null;
        return view('finance.settlement.edit',[
            'set'         => $sets,
            'cash'        => $cash,
            'total_biaya' => $total_biaya,
            'dtl'         => $set_dtl,
            'employee'    => $this->AllEmp(),
            'method'      => "put",
            'action'      => ['Finance\SettlementController@update',$id],
        ]);    
    }


    

    


    public function EditFinance($id)
    { 
        $sets        = FinanceSettlementModel::where('id', $id)->first();
        $set_dtl     = FinanceSettlementDetail::where('id_settlement', $id)->get();
        $cash        = CashAdvance::where('no_cashadv', $sets->no_ref)->first();
        $total_biaya = $cash!=null ? CashAdvanceDesc::where('id_cash', $cash->id)->sum('est_biaya') :null;
        return view('finance.settlement.edit_finance',[
            'set'         => $sets,
            'cash'        => $cash,
            'total_biaya' => $total_biaya,
            'dtl'         => $set_dtl,
            'employee'    => $this->AllEmp(),
            'method'      => "put",
            'action'      => ['Finance\SettlementController@update',$id],
        ]);    
    }


    public function destroy($id)
    { 
    DB::table('finance_settlement')
                ->leftJoin('finance_settlement_app', 'finance_settlement_app.id_settlement', '=', 'finance_settlement.id')
                ->leftJoin('finance_settlement_detail', 'finance_settlement_detail.id_settlement', '=', 'finance_settlement.id')
                ->where([['finance_settlement.id', $id] , ['finance_settlement_detail.id_settlement', $id]])
                ->delete();
                    
    return redirect('finance/settlement')->with('success', 'Deleted Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->edit_request=="inifinance")
        {
        return $this->saveUpdate_Finance($request, 'updated');
        }else if($request->edit_request=="user"){
        return $this->saveUpdate($request, 'updated');
        }
    }

    public function saveUpdate($request, $save, $id=0)
    {   
        $tgl   = $request->tujuan;
        $adds  = $request->tujuan_add;
        $set   = FinanceSettlementModel::where('id', $request->id)->first();
        $dtl   = FinanceSettlementDetail::where('id_settlement', $set->id)->get();
        $cdtl  = count($dtl);
        $creq  = count($request->tujuan);
        $cash  = [
                   'employee_id' => $request->input('emp_id'),
                   'status'      => "Pending",
                   'mtd_payment' => $request->input('mtd_cash'),
                   'acc_bank'    => $request->acc_bank,
                   'no_acc_bank' => $request->no_acc_bank,
                   'name_acc'    => $request->name_acc,
                   'cabang_bank' => $request->cabang_bank,
                   'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            $save.'_by'          => Auth::id(),
        ];
        $qry  = FinanceSettlementModel::where('id',$request->id)->update($cash);
        if ($request->has('tujuan_add') || $creq>$cdtl || $creq<$cdtl)
        { 
        foreach ($adds as $add=>$q){
            foreach ($dtl as $det) {
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$add])){
                    $files    = $request->file('file_set')[$add];
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName=$det->file_set;
            }
            $detail=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan_add[$add],
                'qty'            => $request->qty_add[$add],
                'file_set'       => !empty($request->file('file_set_add')[$add]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set_add')[$add], rand()." ".$request->file('file_set_add')[$add]->getClientOriginalName()) : null,
                'est_biaya'      => $request->biaya_add[$add],
                'notes'          => $request->note_add[$add],
            $save.'_by'          => Auth::id(),
            ];
            } 
        $qry1 = FinanceSettlementDetail::create($detail);
        }
        
        foreach ($tgl as $tgl=>$q){
            foreach ($dtl as $det) {
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$tgl])){
                    $files    = $request->file('file_set')[$tgl];
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName=$set->file_set;
            }
            $detaily=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan[$tgl],
                'qty'            => $request->qty[$tgl],
                'file_set'       => !empty($request->file('file_set')[$tgl]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set')[$tgl], rand()." ".$request->file('file_set')[$tgl]->getClientOriginalName()) : $dtl[$tgl]->file_set,
                'est_biaya'      => $request->biaya[$tgl],
                'notes'          => $request->note[$tgl],
            $save.'_by'          => Auth::id(),
            ];
            $qry1 = FinanceSettlementDetail::where('id',$request->id_dtl[$tgl])->update($detaily);
        } 
        }
    }
    else 
    {
        $co = count($request->tujuan);
        $cash  = FinanceSettlementModel::where('id', $request->id)->first();
        foreach ($tgl as $tgl=>$q){
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$tgl])){
                    $files    = $request->file('file_set')[$tgl];
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName=$dtl[$tgl]->file_set;
            }
            $detail=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan[$tgl],
                'qty'            => $request->qty[$tgl],
                'file_set'       => !empty($request->file('file_set')[$tgl]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set')[$tgl], rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$request->file('file_set')[$tgl]->getClientOriginalName()) : $dtl[$tgl]->file_set,
                'est_biaya'      => $request->biaya[$tgl],
                'notes'          => $request->note[$tgl],
            $save.'_by'          => Auth::id(),
                ];
                // dd($detail);
            $qry1 = FinanceSettlementDetail::where('id',$request->id_dtl[$tgl])->update($detail);
            } 

        }
        $histss= [
            'activity_name'      => "Mengubah Data Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);  
              
        return redirect ('finance/settlement')->with('success', 'Updated Succesfully');              
    } 



    public function saveUpdate_Finance($request, $save, $id=0)
    {   
        $tgl   = $request->tujuan;
        $adds  = $request->tujuan_add;
        $set   = FinanceSettlementModel::where('id', $request->id)->first();
        $dtl   = FinanceSettlementDetail::where('id_settlement', $set->id)->get();
        $cdtl  = count($dtl);
        $creq  = $request->has('tujuan') ? count($request->tujuan) : 0;
        $cash  = [
                   'employee_id' => $request->input('emp_id'),
                   'status'      => $set->status,
                   'mtd_payment' => $request->input('mtd_cash'),
                   'acc_bank'    => $request->acc_bank,
                   'no_acc_bank' => $request->no_acc_bank,
                   'name_acc'    => $request->name_acc,
                   'cabang_bank' => $request->cabang_bank,
                   'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            $save.'_by'          => Auth::id(),
        ];
        $qry  = FinanceSettlementModel::where('id',$request->id)->update($cash);
        if($request->has('tujuan_add'))
        {
        foreach ($adds as $add=>$q){

            $detail=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan_add[$add],
                'qty'            => $request->qty_add[$add],
                'file_set'       => !empty($request->file('file_set_add')[$add]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set_add')[$add], rand()." ".$request->file('file_set_add')[$add]->getClientOriginalName()) : null,
                'est_biaya'      => $request->biaya_add[$add],
                'notes'          => $request->note_add[$add],
            $save.'_by'          => Auth::id(),
            ];
                $qry1 = FinanceSettlementDetail::create($detail);
            } 
        }

        if ($creq>$cdtl || $creq<$cdtl)
        { 
        foreach ($adds as $add=>$q){
            // dd($adds, $q);
            foreach ($dtl as $det) {
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$add])){
                    $files    = $request->file('file_set')[$add];
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName=$det->file_set;
            }
            $detail=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan_add[$add],
                'qty'            => $request->qty_add[$add],
                'file_set'       => !empty($request->file('file_set_add')[$add]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set_add')[$add], rand()." ".$request->file('file_set_add')[$add]->getClientOriginalName()) : null,
                'est_biaya'      => $request->biaya_add[$add],
                'notes'          => $request->note_add[$add],
            $save.'_by'          => Auth::id(),
            ];
                $qry1 = FinanceSettlementDetail::create($detail);
            } 
        }

        if($request->has('tujuan'))
        {
        foreach ($tgl as $tgl=>$q){
            foreach ($dtl as $det) {
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$tgl])){
                    $files    = $request->file('file_set')[$tgl];
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName=$set->file_set;
            }
            $detaily=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan[$tgl],
                'qty'            => $request->qty[$tgl],
                'file_set'       => !empty($request->file('file_set')[$tgl]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set')[$tgl], rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$request->file('file_set')[$tgl]->getClientOriginalName()) : $dtl[$tgl]->file_set,
                'est_biaya'      => $request->biaya[$tgl],
                'notes'          => $request->note[$tgl],
            $save.'_by'          => Auth::id(),
            ];
            $qry1 = FinanceSettlementDetail::where('id',$request->id_dtl[$tgl])->update($detaily);
        } 
        }
        }
    }
    else 
    {
        if($request->has('tujuan'))
        {
        $co = count($request->tujuan);
        $cash  = FinanceSettlementModel::where('id', $request->id)->first();
        foreach ($tgl as $tgl=>$q){
            if ($request->has('file_set')) {
                if(!empty($request->file('file_set')[$tgl])){
                    $files    = $request->file('file_set')[$tgl];
                    $name     = rand()." ".$set->id."-".$set->created_by."-".Carbon::parse($set->created_at)->format('d')." ".$files->getClientOriginalName();
                    $newName = Storage::disk('public')->putFileAs('new_finance/settlement', $files, $name);
                }
            }else {
                $newName=$dtl[$tgl]->file_set;
            }
            $detail=[
                'id_settlement'  => $request->id,
                'tujuan'         => $request->tujuan[$tgl],
                'qty'            => $request->qty[$tgl],
                'file_set'       => !empty($request->file('file_set')[$tgl]) ? Storage::disk('public')->putFileAs('new_finance/settlement', $request->file('file_set')[$tgl], rand()." ".$request->file('file_set')[$tgl]->getClientOriginalName()) : $dtl[$tgl]->file_set,
                'est_biaya'      => $request->biaya[$tgl],
                'notes'          => $request->note[$tgl],
            $save.'_by'          => Auth::id(),
                ];
                // dd($detail);
            $qry1 = FinanceSettlementDetail::where('id',$request->id_dtl[$tgl])->update($detail);
            } 

        }
    }
        $histss= [
            'activity_name'      => "Mengubah Data Settlement",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Settlement",
            'activity_refrensi' => $set->no_settlement,
        ];
        $actss = FinanceHistory::create($histss);  
              
        return redirect ('finance/settlement')->with('success', 'Updated Succesfully');              
    } 
    
    public function PDF_CashAdv($id)
    {
        // dd($id);
        $cash =  CashAdvance::where('id', $id)->first();
        $dtl  =  CashAdvanceDesc::where('id_cash', $cash->id)->get();
        $join =  CashAdvanceDesc::join('finance_cash_adv as c', 'c.id', '=', 'finance_cash_adv_detail.id_cash')->where('id_cash', $id)->get();
        $pdf        = PDF::loadview('pdf.cash_adv',[
            'main' => $cash,
            'dtl'  => $dtl,
            'time' => Carbon::now('GMT+7')->format('d F Y')
        ]);
    	return $pdf->download('MEG.pdf');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function ajax_data(Request $request){
        $mine = getUserEmp(Auth::id());
        $emp  = EmployeeModel::where('id', $mine->spv_id)->first();
        $under= EmployeeModel::where('spv_id', $mine->id)->get()->count();
        $usr  = Auth::id();

        if($mine->division_id==3 || $mine->id==2 || $mine->division_id==7){
            return $this->ajax_finance($request);
        }
        else if($under!=0)
        {
            return $this->ajax_Management($request, $mine, $emp);
        }else {
         $columns = array(
             0 => 'id',
             1 => 'tujuan',
             2 => 'no_ref',
             3 => 'status',
             4 => 'created_at',
             5 => 'employee_id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = FinanceSettlementModel::where('created_by', $usr)->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
         
         if (empty($request->input('search')['value'])) {
             $posts = FinanceSettlementModel::select('*')->where('created_by', $usr)->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" when (status = "Approved" AND app_manage IS NOT NULL AND app_finance IS NULL) then "C" when (status = "Approved" AND app_manage IS NOT NULL AND app_finance IS NOT NULL) then "D" when (status = "Completed") then "F" when (status = "Rejected") then "Z" else "E" end) as status_sort'))
            ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit, $start)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = FinanceSettlementModel::select('*')->where('created_by', $usr)->where('no_settlement', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = FinanceSettlementModel::select('*')->where('created_by', $usr)->where('no_settlement', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
        
        


         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $nominal = FinanceSettlementDetail::select(DB::raw('SUM(qty*est_biaya) as noms'))->where('id_settlement', $post->id)->first();
                $dtls    = FinanceSettlementDetail::where('id_settlement', $post->id)->orderBy('id', 'desc')->first();
                $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'no_set'      => $post->no_settlement,
                    'no_ref'      => $post->no_ref,
                    'nominal'     => $nominal->noms,
                    'dtls'        => $dtls->tujuan,
                    'app'         => $post->app_finance,
                    'status'      => $post->status,
                    'user'        => "other",
                    'created_at'  => Carbon::parse($post->created_at)->format('d F Y'),
                    'id'          => $post->id,
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



public function ajax_finance($request)
{
         $columns = array(
             0 => 'id',
             1 => 'tujuan',
             2 => 'no_ref',
             3 => 'status',
             4 => 'created_at',
             5 => 'employee_id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
        $menu_count    = FinanceSettlementModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

         
         if (empty($request->input('search')['value'])) {
            $posts = FinanceSettlementModel::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" when (status = "Approved" AND app_manage IS NOT NULL AND app_finance IS NULL) then "C" when (status = "Approved" AND app_manage IS NOT NULL AND app_finance IS NOT NULL) then "D" when (status = "Completed") then "F" when (status = "Rejected") then "Z" else "E" end) as status_sort'))
            ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
        } else {
             $search        = $request->input('search')['value'];
             $posts         = FinanceSettlementModel::select('*',DB::raw('finance_settlement.id'))->join('employees', 'employees.id','=', 'finance_settlement.employee_id')->where('emp_name', 'like', '%' . $search . '%')->offset($start)->limit($limit)->get();
             $totalFiltered = FinanceSettlementModel::select('*',DB::raw('finance_settlement.id'))->join('employees', 'employees.id','=', 'finance_settlement.employee_id')->where('emp_name', 'like', '%' . $search . '%')->offset($start)->limit($limit)->count();
         }


         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $nominal = FinanceSettlementDetail::select(DB::raw('SUM(qty*est_biaya) as noms'))->where('id_settlement', $post->id)->first();
                $dtls    = FinanceSettlementDetail::where('id_settlement', $post->id)->orderBy('id', 'desc')->first();
                $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'no_set'      => $post->no_settlement,
                    'no_ref'      => $post->no_ref,
                    'nominal'     => $nominal->noms,
                    'status'      => $post->status,
                    'dtls'        => $dtls->tujuan,
                    'app'         => $post->app_finance,
                    'user'        => getUserEmp(Auth::id())->division_id==3 ? "Finance" : "Management",
                    'by'          => $post->created_by == Auth::id() ? "edit" : 'no_edit',
                    'created_at'  => Carbon::parse($post->created_at)->format('d F Y'),
                    'id'          => $post->id,
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


public function ajax_Management($request, $mine, $usr)
{
         $columns = array(
             0 => 'id',
             1 => 'tujuan',
             2 => 'no_ref',
             3 => 'status',
             4 => 'created_at',
             5 => 'employee_id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = FinanceSettlementModel::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
         
         if (empty($request->input('search')['value'])) {
            $posts = FinanceSettlementModel::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" when (status = "Approved" AND app_manage IS NOT NULL AND app_finance IS NULL) then "C" when (status = "Approved" AND app_manage IS NOT NULL AND app_finance IS NOT NULL) then "D" when (status = "Completed") then "F" when (status = "Rejected") then "Z" else "E" end) as status_sort'))
            ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get(); 
         } else {
             $search        = $request->input('search')['value'];
             $posts         = FinanceSettlementModel::where('no_settlement', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = FinanceSettlementModel::where('no_settlement', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }

         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $nominal = FinanceSettlementDetail::select(DB::raw('SUM(qty*est_biaya) as noms'))->where('id_settlement', $post->id)->first();
                $dtls    = FinanceSettlementDetail::where('id_settlement', $post->id)->orderBy('id', 'desc')->first();
                $created = getUserEmp($post->created_by)->spv_id;
                if($created == getUserEmp(Auth::id())->id || $post->created_by == Auth::id()){
                $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'no_set'      => $post->no_settlement,
                    'no_ref'      => $post->no_ref,
                    'status'      => $post->status,
                    'dtls'        => $dtls->tujuan,
                    'nominal'     => $nominal->noms,
                    'app'         => $post->app_finance,
                    'user'        => getUserEmp(Auth::id())->division_id==3 ? "Finance" : "Management",
                    'by'          => $post->created_by == Auth::id() ? "edit" : 'no_edit',
                    'created_at'  => Carbon::parse($post->created_at)->format('d F Y'),
                    'id'          => $post->id,
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




function auto_value(Request $request)
{
    // dd($request);
    $employee  = EmployeeModel::where('id', $request->id)->first();
    return response()->json($employee);
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
    
         public function AllDivision()
    {

        $data = Role_division::all();
        $arr  = array();
        foreach ($data as $reg) {
           
            
            $arr[$reg->id] = $reg->div_name;
        }
        return $arr;
    }


    public function AllEmp()
    {

        $data = EmployeeModel::all();
        $arr  = array();
        foreach ($data as $reg) {
           
            
            $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
    }

    function AddAct(Request $request)
    {
        return view('finance.cash_advance.cr_form', [
        'n_equ'  => $request->n_equ,
        ]);
    }
}