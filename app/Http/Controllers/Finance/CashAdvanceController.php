<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Models\Finance\CashAdvance;
use App\Models\Finance\CashAdvanceDesc;
use App\Models\Finance\CashAdvanceApp;
use App\Models\Finance\CashAdvanceHistory;
use App\Models\Finance\CashAdvSettlement;
use App\Models\Finance\FinanceSettlementModel;
use App\Models\Finance\FinanceHistory;
use App\Models\Finance\FinanceSettlementDetail;
use App\Models\Finance\FinanceSettlementApp;
use App\Models\Finance\SettlementDetail;
use App\Http\Controllers\Controller;
use App\Models\role_division;
use App\Models\HR\EmployeeModel;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use PDF;
use DB;
use Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CashAdvanceController extends Controller
{
    //
    public function index()
    {
        return view('finance.cash_advance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $emp = EmployeeModel::where('emp_status', '=', 'Active')->get();
        $div = role_division::all();
        return view('finance.cash_advance.create', [
            'employee' => $this->AllEmp(),
            'name'     => user_name(Auth::id()),
            'user'     => getUserEmp(Auth::id())->id,
            'emp_data' => getUserEmp(Auth::id()),
            'division' => $div,
            'province' => $this->get_province(),
            'city'     => [],
            'method'   => "post",
            'action'   => "Finance\CashAdvanceController@store"
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
        // dd($request);
        if ($request->type_cash == "dinas") {
            return $this->save($request, 'created');
        } else {
            return $this->saveBlank($request);
        }
    }


    public function save($request, $save, $id = 0)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $emp = EmployeeModel::where('id', $request->emp_id)->first();
        $tgl = $request->input('tgl_pekerjaan');
        $cash = [
            'emp_id'        => $request->input('emp_id'),
            'des_kota'      => $request->input('des_kota'),
            'des_provinsi'  => $request->input('des_provinsi'),
            'type_cash'     => $request->type_cash,
            'status'        => "Pending",
            'tgl_berangkat' => Carbon::parse($request->input('tgl_berangkat'))->format('Y-m-d'),
            'tgl_pulang'    => Carbon::parse($request->input('tgl_pulang'))->format('Y-m-d'),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'mtd_cash'      => $request->input('mtd_cash'),
            'rek_bank'      => $request->input('bank'),
            'no_rek'        => $request->input('no_rek'),
            'nama_rek'      => $request->input('Nama_rek'),
            'cabang_rek'    => $request->input('cabang'),
            'est_waktu'     => $request->input('est_waktu'),
            $save . '_by'            => Auth::id(),
        ];
        $qry = $save == "created" ? CashAdvance::create($cash) : CashAdvance::where('id', $id)->update($cash);
        if ($qry) {
            foreach ($tgl as $tgl => $q) {
                $detail = [
                    'id_cash'        => $save == "created" ? $qry->id : $request->id,
                    'tgl_pekerjaan'  => Carbon::parse($request->tgl_pekerjaan[$tgl])->format('Y-m-d'),
                    'nama_pekerjaan' => $request->nama_pekerjaan[$tgl],
                    'deskripsi'      => $request->deskripsi[$tgl],
                    'status'         => "Pending",
                    'est_biaya'      => $request->est_biaya[$tgl],
                    $save . '_by'          => Auth::id(),
                ];
                $qry1 = $save == "created" ? CashAdvanceDesc::create($detail) : CashAdvanceDesc::where('id_cash', $id)->update($detail);
            }

            if ($request->has('tgl_blank_add')) {
                $tgl_add = $request->tgl_blank_add;
                foreach ($tgl_add as $tgls => $q) {
                    $detail = [
                        'id_cash'       => $qry->id,
                        'tgl_pekerjaan' => Carbon::parse($request->tgl_blank_add[$tgls])->format('Y-m-d'),
                        'deskripsi'     => $request->desk_blank_add[$tgls],
                        'status'        => "Pending",
                        'est_biaya'     => $request->nominals_add[$tgls],
                        'created_by'    => Auth::id(),
                    ];
                    $qry1 = CashAdvanceDesc::create($detail);
                }
            }

            $hist = [
                'id_cash'       => $qry->id,
                'activity'      => "Menambahkan Cash Advance",
                'activity_user' => $mine->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $act    = CashAdvanceHistory::create($hist);

            $histss = [
                'activity_name'      => "Menambahkan Cash Advance",
                'activity_user'      => $mine->id,
                'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'         => Auth::id(),
                'status_activity'    => "Cash Advance",
                'activity_refrensi'  => $qry->id,
            ];
            $actss    = FinanceHistory::create($histss);
        }
        return redirect('finance/cash_advance')->with('success', 'Created Cash Advance Successfully');
    }


    public function saveBlank($request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $tgl = $request->input('tgl_blank');
        $cash = [
            'emp_id'       => $request->input('emp_id'),
            'des_tujuan'   => $request->input('des_tujuan'),
            'type_cash'    => $request->type_cash,
            'status'       => "Pending",
            'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
            'mtd_cash'     => $request->input('mtd_cash'),
            'rek_bank'     => $request->input('bank'),
            'no_rek'       => $request->input('no_rek'),
            'nama_rek'     => $request->input('Nama_rek'),
            'cabang_rek'   => $request->input('cabang'),
            'created_by'   => Auth::id(),
        ];
        $qry = CashAdvance::create($cash);
        if ($qry) {
            foreach ($tgl as $tgl => $q) {
                $detail = [
                    'id_cash'       => $qry->id,
                    'tgl_pekerjaan' => Carbon::parse($request->tgl_blank[$tgl])->format('Y-m-d'),
                    'deskripsi'     => $request->desk_blank[$tgl],
                    'status'        => "Pending",
                    'est_biaya'     => $request->nominals[$tgl],
                    'created_by'    => Auth::id(),
                ];
                $qry1 = CashAdvanceDesc::create($detail);
            }

            if ($request->has('tgl_blank_add')) {
                $tgl_add = $request->tgl_blank_add;
                foreach ($tgl_add as $tgls => $q) {
                    $detail = [
                        'id_cash'       => $qry->id,
                        'tgl_pekerjaan' => Carbon::parse($request->tgl_blank_add[$tgls])->format('Y-m-d'),
                        'deskripsi'     => $request->desk_blank_add[$tgls],
                        'status'        => "Pending",
                        'est_biaya'     => $request->nominals_add[$tgls],
                        'created_by'    => Auth::id(),
                    ];
                    $qry1 = CashAdvanceDesc::create($detail);
                }
            }

            $hist = [
                'id_cash'       => $qry->id,
                'activity'      => "Menambahkan Cash Advance",
                'activity_user' => $mine->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $act    = CashAdvanceHistory::create($hist);

            $histss = [
                'activity_name'      => "Menambahkan Cash Advance",
                'activity_user'      => $mine->id,
                'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'         => Auth::id(),
                'status_activity'    => "Cash Advance",
                'activity_refrensi'  => $qry->id,
            ];
            $actss    = FinanceHistory::create($histss);
        }
        return redirect('finance/cash_advance')->with('success', 'Created Cash Advance Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mine   = getUserEmp(Auth::id());
        $usr    = Auth::id();
        $cash   = CashAdvance::where('id', $id)->first();
        $emp    = getEmp($cash->emp_id);
        $dtl    = CashAdvanceDesc::where('id_cash', $id)->get();
        $dtls   = CashAdvanceDesc::where('id_cash', $id)->first();
        $capp   = CashAdvanceApp::where('id_cash', $id)->first();
        $app    = CashAdvanceApp::where('id_cash', $id)->get();
        $hist   = CashAdvanceHistory::where('id_cash', $cash->id)->orderBy('id', 'desc')->get();
        $activity = FinanceHistory::where([['status_activity', 'Cash Advance'], ['activity_refrensi', $cash->id]])->orderBy('id', 'desc')->get();
        return view('finance.cash_advance.show', [
            'cash'  => $cash,
            'emp'   => $emp,
            'mine'  => $mine,
            'capp'  => $capp,
            'activity' => $activity,
            'hist'  => $hist,
            'dtls'  => $dtls,
            'app'   => $app,
            'divisi' => div_name($cash->div_id),
            'dtl'   => $dtl,
        ]);
    }


    function calculate(Request $request)
    {
        dd($request);
    }


    public function ajukan_cash($id)
    {
        // dd($id);
        $casht = CashAdvance::where('id', $id)->first();
        $set  = CashAdvSettlement::where('id_cash', $id)->first();
        $url  = url('/');
        $cash = [
            'status'        => "Need Approval",
            'tgl_pengajuan' => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $hist = [
            'id_cash'       => $id,
            'activity'      => "Mengajukan Approval Cash Advance",
            'activity_user' => getUserEmp(Auth::id())->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $histss = [
            'activity_name'      => "Mengajukan Cash Advance Untuk Approval",
            'activity_user'      => getUserEmp(Auth::id())->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi' => $id,
        ];

        $actss = FinanceHistory::create($histss);
        $qry1 = CashAdvance::where('id', $id)->update($cash);
        $qry2 = CashAdvanceHistory::create($hist);
        if ($qry2) {
            SendEmailCash($casht->id, getUserEmp($casht->created_by)->spv_id);
        }

        return redirect('finance/cash_advance')->with('success', 'Created Submission Successfully');
    }


    public function approve($id)
    {
        // dd($id);
        $mine      = getUserEmp(Auth::id());
        $usr       = Auth::id();
        $cash      = CashAdvance::where('id', $id)->first();
        if (in_array($cash->emp_id, explode(',', getConfig('fin_auto_approve'))) || in_array(getEmp($cash->emp_id)->spv_id, explode(',', getConfig('app_finance')))) {
            return $this->auto_approve($id);
        } else {
            $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
            $app       = CashAdvanceApp::where('id_cash', $id)->get();
            $emp       = EmployeeModel::where('id', $cash->emp_id)->first();
            $cashh           = [
                'status'     => "Approved",
                'app_spv'    => Auth::id(),
                'tgl_app_spv' => Carbon::now('GMT+7')->toDateTimeString(),
            ];

            $hist = [
                'id_cash'       => $id,
                'activity'      => "Menyetujui Pengajuan " . $cash->no_cashadv,
                'activity_user' => $mine->id,
                'created_by'    => Auth::id(),
            ];

            $histss = [
                'activity_name'      => "Menyetujui Cash Advance",
                'activity_user'      => $mine->id,
                'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'         => Auth::id(),
                'status_activity'    => "Cash Advance",
                'activity_refrensi' => $id,
            ];

            $actss  = FinanceHistory::create($histss);
            $qry    = CashAdvance::where('id', $id)->update($cashh);
            $hist   = CashAdvanceHistory::create($hist);
            if ($qry) {
                SendEmailCashSPV($cash->id, "from spv");
            }

            return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
        }
    }



    public function auto_approve($id)
    {
        $mine      = getUserEmp(Auth::id());
        $usr       = Auth::id();
        $cash      = CashAdvance::where('id', $id)->first();
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $no_cash   = CashAdvance::max('no_id');
        $emp       = EmployeeModel::where('id', $cash->emp_id)->first();
        $cashh           = [
            'status'      => "Approved",
            'app_spv'     => Auth::id(),
            'tgl_app_spv' => Carbon::now('GMT+7')->toDateTimeString(),
            'app_hr'      => Auth::id(),
            'no_cashadv'  => "MEG-CA" . Carbon::now()->format('my') . sprintf("%03d", ($no_cash + 1)),
            'no_id'       => ($no_cash + 1),
        ];

        $hist = [
            'id_cash'       => $id,
            'activity'      => "Menyetujui Pengajuan",
            'activity_user' => $mine->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $histss = [
            'activity_name'      => "Menyetujui Cash Advance",
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi' => $id,
        ];

        $actss  = FinanceHistory::create($histss);
        $qry    = CashAdvance::where('id', $id)->update($cashh);
        $hist   = CashAdvanceHistory::create($hist);
        if ($qry) {
            SendEmailCashHR($cash->id, "finance");
        }

        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }





    public function approve_hrd($id, $type)
    {
        // dd($id);
        $mine      = getUserEmp(Auth::id());
        $usr       = Auth::id();
        $cash      = CashAdvance::where('id', $id)->first();
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $emp       = EmployeeModel::where('id', $cash->emp_id)->first();
        $no_cash   = CashAdvance::max('no_id');
        $type      = $type == "approval" ? "Approved" : "Rejected";
        $cashh           = [
            'status'     => $type,
            'app_hr'     => Auth::id(),
            'tgl_app_spv' => Carbon::now('GMT+7')->toDateTimeString(),
            'no_cashadv'  => "MEG-CA" . Carbon::now()->format('my') . sprintf("%03d", ($no_cash + 1)),
            'no_id'       => ($no_cash + 1),
        ];

        $detail    = [
            'no_cashadv'  => "MEG-CA" . Carbon::now()->format('my') . sprintf("%03d", ($no_cash + 1)),
        ];


        $qry    = CashAdvance::where('id', $id)->update($cashh);
        $qrys   = CashAdvanceDesc::where('id_cash', $id)->update($detail);

        $hist = [
            'id_cash'       => $id,
            'activity'      => $type . " " . $cash->no_cashadv,
            'activity_user' => $mine->id,
            'created_by'    => Auth::id(),
        ];

        $histss = [
            'activity_name'      => $type . " Cash Advance",
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi'  => $cash->id,
        ];

        $actss  = FinanceHistory::create($histss);
        $hist   = CashAdvanceHistory::create($hist);
        if ($qry) {
            SendEmailCashHR($cash->id, "finance");
        }

        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }



    public function reject($id)
    {
        // dd($id);
        $mine      = getUserEmp(Auth::id());
        $cash      = CashAdvance::where('id', $id)->first();
        $cash_dtl  = CashAdvanceDesc::where('id_cash', $id)->orderBy('id', 'desc')->first();
        $no        = $cash->no_cashadv;
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $emp       = EmployeeModel::where('id', $cash->emp_id)->first();
        //  $approval  = $emp->division_id== 7 ? 'app_manage' : 'app_finance';
        //  $status    = $emp->division_id== 7 ? "Management" : 'Supervisor';
        //  $data_apps = [
        //      'id_cash'     => $id,
        //      'approval_by' => $status,
        //      'status_app'  => "Rejected",
        //      'status_by'   => $mine->id,
        //      'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
        //      'created_by'  => Auth::id(),
        //  ];
        //  $app = CashAdvanceApp::insert($data_apps);
        //  if($app){
        $cash        = [
            'status'     => "Rejected",
            'app_spv'    => Auth::id(),
            'tgl_app_spv' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        //  $dtl = [
        //      'status'     => "Rejected By ".$status,
        //      'updated_by' => Auth::id(),
        //  ];
        $hist = [
            'id_cash'       => $id,
            'activity'      => "Tidak Menyetujui Pengajuan " . $no,
            'activity_user' => getUserEmp(Auth::id())->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $histss = [
            'activity_name'      => "Tidak Menyetujui Cash Advance",
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi' =>  $id,
        ];

        $actss  = FinanceHistory::create($histss);
        // $qry2   = CashAdvanceDesc::where('id_cash', $id)->update($dtl);
        $qry    = CashAdvance::where('id', $id)->update($cash);
        $hist   = CashAdvanceHistory::create($hist);
        // }
        return redirect('finance/cash_advance')->with('success', 'Rejected Successfully');
    }


    public function reject_finance($id)
    {
        $mine      = getUserEmp(Auth::id());
        $cash      = CashAdvance::where('id', $id)->first();
        $cash_dtl  = CashAdvanceDesc::where('id_cash', $id)->orderBy('id', 'desc')->first();
        $no        = $cash->no_cashadv;
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $emp       = EmployeeModel::where('id', $cash->emp_id)->first();
        $cash        = [
            'status'         => "Rejected",
            'app_finance'    => Auth::id(),
        ];
        $qry    = CashAdvance::where('id', $id)->update($cash);

        $hist = [
            'id_cash'       => $id,
            'activity'      => "Tidak Menyetujui Cash Advance",
            'activity_user' => getUserEmp(Auth::id())->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $histss = [
            'activity_name'      => "Tidak Menyetujui Cash Advance",
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi'  =>  $id,
        ];

        $actss  = FinanceHistory::create($histss);
        $hist   = CashAdvanceHistory::create($hist);
        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }



    public function completed(Request $request)
    {
        // dd($request);
        $id   = $request->id;
        $cash = CashAdvance::where('id', $id)->first();
        return view('finance.cash_advance.attribute.complete_form', [
            'id'     => $id,
            'cash'   => $cash,
            'method' => "post",
            'action' => "Finance\CashAdvanceController@saveComplete"
        ]);
    }



    public function saveComplete(Request $request)
    {
        $id    = $request->id;
        $check     = getEmp(Auth::id());
        $cash  = CashAdvance::where('id', $id)->first();
        $mine  = getUserEmp(Auth::id());
        $cash  = CashAdvance::where('id', $id)->first();
        if ($request->has('file_cash')) {
            $files    = $request->file('file_cash');
            $newName = Storage::disk('public')->putFileAs('new_finance/cash_advance', $request->file('file_cash'), $files->getClientOriginalName());
        } else {
            $newName = null;
        }
        $cashh = [
            'status'        => "Completed",
            'file_cash'     => $newName,
            'note'          => $request->note,
            'tgl_transfer'  => $request->tgl_transfer,
            'app_finance'   => Auth::id(),
            'biaya_finance' => $request->nominal,
        ];

        $text = $request->tgl_transfer != null ? ", Tanggal Transfer " . $request->tgl_transfer : null;
        $histss = [
            'activity_name'      => "Cash Advance Completed" . $text,
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi' => $cash->no_cashadv,
        ];

        $hist = [
            'id_cash'       => $id,
            'activity'      => "Cash Advance Completed" . $text,
            'activity_user' => $check->id,
            'created_by'    => Auth::id(),
        ];

        $actss = FinanceHistory::create($histss);
        $qrys  = CashAdvanceHistory::create($hist);
        $qry   = CashAdvance::where('id', $id)->update($cashh);

        return redirect('finance/cash_advance')->with('success', 'Has Been Completed');
    }


    public function manage_approve($id, $user)
    {
        $check     = getEmp($user);
        $cash      = CashAdvance::where('id', $id)->first();
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $cek       = $check->id == 2 || $check->division_id == 8 ? "Approved By HRD" : "Approved By Management";
        $data_apps = [
            'id_cash'     => $id,
            'approval_by' => div_name($check->division_id),
            'status_app'  => "Approved",
            'status_by'   => $check->id,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        $app = CashAdvanceApp::insert($data_apps);
        if ($app) {
            $cashh        = [
                'status'     => "Approved",
                'updated_by' => Auth::id(),
                'app_hrd'    => "Done",
            ];
            $dtl = [
                'status'     => $cek,
                'updated_by' => Auth::id(),
            ];
            $hist = [
                'id_cash'       => $id,
                'activity'      => "Menyetujui Pengajuan " . $cash->no_cashadv,
                'activity_user' => $check->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qry2   = CashAdvanceDesc::where('id_cash', $id)->update($dtl);
            $qry    = CashAdvance::where('id', $id)->update($cashh);
            $hist   = CashAdvanceHistory::create($hist);
        }
        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }

    public function manage_reject($id, $user)
    {
        $check     = getEmp($user);
        $cash      = CashAdvance::where('id', $id)->first();
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $data_apps = [
            'id_cash'     => $id,
            'approval_by' => div_name($check->division_id),
            'status_app'  => "Rejected",
            'status_by'   => $check->id,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),

        ];
        $app = CashAdvanceApp::insert($data_apps);
        if ($app) {
            $cashh        = [
                'status'     => "Rejected",
                'updated_by' => Auth::id(),
                'app_hrd'    => "Done",
            ];
            $dtl = [
                'status'     => "Rejected",
                'updated_by' => Auth::id(),
            ];
            $hist = [
                'id_cash'       => $id,
                'activity'      => "Tidak Menyetujui Pengajuan",
                'activity_user' => $mine->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qry2   = CashAdvanceDesc::where('id_cash', $id)->update($dtl);
            $qry    = CashAdvance::where('id', $id)->update($cashh);
            $hist   = CashAdvanceHistory::create($hist);
        }
        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }


    public function finance_approve(Request $request)
    {
        // dd($request);
        $check     = getUserEmp(Auth::id());
        $cash      = CashAdvance::where('id', $request->id)->first();
        $dtl       = CashAdvanceDesc::where('id_cash', $request->id)->get();
        $app       = CashAdvanceApp::where('id_cash', $request->id)->get();
        $data_apps = [
            'id_cash'     => $request->id,
            'approval_by' => div_name($check->division_id),
            'status_app'  => "Approved",
            'status_by'   => $check->id,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        $app = CashAdvanceApp::insert($data_apps);
        if ($app) {
            $cashh        = [
                'status'      => "Submitted By Finance",
                'updated_by'  => Auth::id(),
                'app_finance' => "Done",
                'note'        => $request->input('note'),
            ];
            $dtl = [
                'status'     => "Submitted By Finance",
                'updated_by' => Auth::id(),
            ];
            $hist = [
                'id_cash'       => $request->id,
                'activity'      => "Menyetujui Pengajuan " . $cash->no_cashadv,
                'activity_user' => $check->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qry2   = CashAdvanceDesc::where('id_cash', $request->id)->update($dtl);
            $qry    = CashAdvance::where('id', $request->id)->update($cashh);
            $hist   = CashAdvanceHistory::create($hist);
        }
        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }



    public function finance_btl($id)
    {
        $check     = getEmp($user);
        $cash      = CashAdvance::where('id', $id)->first();
        $dtl       = CashAdvanceDesc::where('id_cash', $id)->get();
        $app       = CashAdvanceApp::where('id_cash', $id)->get();
        $cek       = $check->id == 2 || $check->division_id == 8 ? "Approved By HRD" : "Approved By Management";
        $data_apps = [
            'id_cash'     => $id,
            'approval_by' => div_name($check->division_id),
            'status_app'  => "Rejected",
            'status_by'   => $check->id,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        $app = CashAdvanceApp::insert($data_apps);
        if ($app) {
            $cashh        = [
                'status'      => "Rejected By Finance",
                'updated_by'  => Auth::id(),
                'app_finance' => "Done",
                'note'        => $request->input('note'),
                //  'other_files' => $request->has('files') ? Storage::disk('public')->put('doc_cashadv', $request->file('files')) : null,
            ];
            $dtl = [
                'status'      => "Rejected By Finance",
                'updated_by' => Auth::id(),
            ];
            $hist = [
                'id_cash'       => $id,
                'activity'      => "Tidak Menyetujui Pengajuan " . $cash->no_cashadv,
                'activity_user' => $mine->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qry2   = CashAdvanceDesc::where('id_cash', $id)->update($dtl);
            $qry    = CashAdvance::where('id', $id)->update($cashh);
            $hist   = CashAdvanceHistory::create($hist);
        }
        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }



    public function remove(Request $request)
    {
        // dd($request);
        DB::table('finance_cash_adv_detail')->where('id', $request->id)->delete();
    }

    public function showDetailSettlement(Request $request)
    {
        // dd($request);
        $sets = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        return view('finance.cash_advance.detail_cash_set.detail_settlement', [
            'cash_set'  => $sets,
        ]);
    }

    public function set_settlement($id)
    {
        // dd($id);
        $mine = getUserEmp(Auth::id());
        $emp  = EmployeeModel::where('spv_id', $mine->id)->first();
        $cash = CashAdvance::where('id', $id)->first();
        $dtl  = CashAdvanceDesc::where('id_cash', $id)->get();
        $dtls = CashAdvanceDesc::where('id_cash', $id)->first();
        $capp = CashAdvanceApp::where('id_cash', $id)->first();
        $app  = CashAdvanceApp::where('id_cash', $id)->get();
        $sett = CashAdvanceDesc::join('finance_cash_adv_settlement', 'finance_cash_adv_settlement.id_cash', '=', 'finance_cash_adv_detail.id_cash')
            ->where('finance_cash_adv_detail.id_cash', 'finance_cash_adv_settlement.id_cash')->get();

        return view('finance.cash_advance.detail_cash_set.create_settlement', [
            'cash'  => $cash,
            'emp'   => $emp,
            'mine'  => $mine,
            'capp'  => $capp,
            'dtls'  => $dtls,
            'set'   => $sett,
            'app'   => $app,
            'divisi' => div_name($cash->div_id),
            'dtl'   => $dtl,
        ]);
    }

    public function saveSettlement(Request $request, $id = 0)
    {
        // dd($request);
        $dtl     = CashAdvanceDesc::where('id', $request->id_dtl)->first();
        $cash    = CashAdvance::where('id', $dtl->id_cash)->first();
        $set_dtl = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        $all_dtl = CashAdvSettlement::all();
        if ($request->detail_sets != null || $request->detail_sets == "detail_settlement") {
            return $this->savedetail_settlement($request);
        } else {
            // dd($request);
            $dtl     = CashAdvanceDesc::where('id', $request->id_dtl)->first();
            $cash    = CashAdvance::where('id', $dtl->id_cash)->first();
            $set_dtl = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
            $est = $request->est_biaya;
            foreach ($est as $ests => $r) {
                $file = $request->file('up_files');
                $dtl = [
                    'up_files'   => $request->has('up_files') ? Storage::disk('public')->put('cash_advance', $file[$ests]) : null,
                ];
                $cash_app = CashAdvanceDesc::where('id', $request->id_dtl[$ests])->update($dtl);
            }
            $cash_arr = [
                'status_set' => 'Submitted',
                'status'     => 'Completed',
                'total_set'  => $request->hasil,
                'sisa_lebih' => $request->sisa,
                'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
                'updated_by' => Auth::id(),
            ];

            $hist = [
                'id_cash'       => $request->id,
                'activity'      => "Menambahkan Settlement",
                'activity_user' => getUserEmp(Auth::id())->id,
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $cashh = CashAdvance::where('id', $request->id)->update($cash_arr);
            $hist  = CashAdvanceHistory::create($hist);
            if ($hist) {
                $settlement = [
                    'employee_id' => $request->id_employee,
                    'no_ref'      => $cash->no_cashadv,
                    'tujuan'      => $cash->no_cashadv,
                    'st_approval' => "Submitted",
                    'ref_notes'   => "Cash Advance",
                    'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'    => Auth::id(),
                ];
                $set_mdl = FinanceSettlementModel::create($settlement);
            }
            if ($set_mdl) {
                $up_set = [
                    'no_settlement'  => 'MEG/FAT-SF/' . Carbon::now()->format('m-y') . '/' . sprintf("%04d", $set_mdl->id),
                ];
                $upset = FinanceSettlementModel::where('id', $set_mdl->id)->update($up_set);
                $dtl   = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->update($up_set);
                foreach ($all_dtl as $set_dtls => $p) {
                    $dtl_settlement = [
                        'id_settlement' => $set_mdl->id,
                        'receipt_doc'   => $all_dtl[$set_dtls]->set_files,
                        'set_qty'       => $all_dtl[$set_dtls]->set_qty,
                        'tujuan'        => $all_dtl[$set_dtls]->items_for,
                        'unit_price'    => $all_dtl[$set_dtls]->set_nominal,
                        'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
                        'created_by'    => Auth::id(),
                    ];
                    $cr_dtl_sett  = FinanceSettlementDetail::create($dtl_settlement);
                }
            }
            return redirect('finance/cash_advance')->with('success', 'Create Settlement Successfully');
        }
    }


    public function show_settlement($id)
    {
        // dd($id);
        $mine = getUserEmp(Auth::id());
        $usr  = getEmp($mine->spv_id);
        $emp  = EmployeeModel::where('spv_id', $mine->id)->first();
        $cash = CashAdvance::where('id', $id)->first();
        $dtl  = CashAdvanceDesc::where('id_cash', $id)->get();
        $dtls = CashAdvanceDesc::where('id_cash', $id)->first();
        $capp = CashAdvanceApp::where('id_cash', $id)->first();
        $app  = CashAdvanceApp::where('id_cash', $id)->get();

        //settlement
        $appr = $mine->spv_id == $usr->id ? "Supervisor"  : "Finance Manager";
        $sett = CashAdvanceDesc::join('finance_cash_adv_settlement as d', 'd.id_cash_dtl', '=', 'finance_cash_adv_detail.id')
            ->where('d.id_cash', $id)->groupby('id_cash_dtl')->get();
        $sets_mod = FinanceSettlementModel::where('no_ref', $cash->no_cashadv)->first();
        $sets_app = FinanceSettlementApp::where('id_settlement', $sets_mod->id)->get();
        $fs_app   = FinanceSettlementApp::where('id_settlement', $sets_mod->id)->where('approval_by', $appr)->first();
        $set      = CashAdvSettlement::where('id_cash', $cash->id)->get()->count();
        $Cset_app = count($sets_app);
        return view('finance.cash_advance.detail_cash_set.show_settlement', [
            'cash'     => $cash,
            'usr'      => $usr,
            'emp'      => $emp,
            'cash_set' => $sett,
            'mine'     => $mine,
            'capp'     => $capp,
            'Cset_cash' => $set,
            'fs_app'   => $fs_app,
            'dtls'     => $dtls,
            'set'      => $sett,
            'app'      => $app,
            'sets_mod' => $sets_mod,
            'sets_app' => $sets_app,
            'Cset_app' => $Cset_app,
            'divisi'   => div_name($cash->div_id),
            'dtl'      => $dtl,
        ]);
    }


    public function detail_settlement(Request $request)
    {
        $dtl  = CashAdvanceDesc::where('id', $request->id_dtl)->first();
        $cash = CashAdvance::where('id', $dtl->id_cash)->first();
        $set  = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        $sets  = DB::table('finance_cash_adv_settlement')->where('id_cash_dtl', $request->id_dtl)->sum('total_nominal');
        return view('finance.cash_advance.detail_cash_set.create_detail_settlement', [
            'dtl'  => $dtl,
            'cash' => $cash,
            'set'  => $set,
            'to_nom' => $sets,
        ]);
    }

    public function savedetail_settlement(Request $request)
    {
        $arr     = $request->set_qty;
        $dtl     = CashAdvanceDesc::where('id', $request->id_dtl)->first();
        $cash    = CashAdvance::where('id', $dtl->id_cash)->first();
        $set_dtl = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        $cdtl    = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get()->count();
        $creq    = count($arr);

        if ($creq > $cdtl || $creq < $cdtl) {
            foreach ($arr as $array => $l) {
                $up_file     = count($set_dtl) == 0 || $creq > count($set_dtl) ? null : $set_dtl[$array]->set_files;
                $file        = $request->file('set_files');
                $set_in_cash = [
                    'id_cash_dtl'   => $dtl->id,
                    'id_cash'       => $cash->id,
                    'items_for'     => $request->items_for[$array],
                    'set_qty'       => $request->set_qty[$array],
                    'set_nominal'   => $request->set_nominal[$array],
                    'note'          => $request->note[$array],
                    'set_files'     => $request->has('set_files') ? Storage::disk('public')->put('cash_advance', $file[$array]) : $up_file,
                    'total_nominal' => $request->set_qty[$array] * $request->set_nominal[$array],
                ];
            }
            $cash_set = CashAdvSettlement::create($set_in_cash);
        } else {
            foreach ($arr as $array => $l) {
                $file        = $request->file('set_files');
                $cfile       = $file == null ? 0 : count($file);
                $up_file     = count($set_dtl) == 0 || $cfile > count($set_dtl) || $cfile < count($set_dtl) ? null :
                    $set_dtl[$array]->set_files;
                $set_in_cash = [
                    'id_cash_dtl'   => $dtl->id,
                    'id_cash'       => $cash->id,
                    'items_for'     => $request->items_for[$array],
                    'set_qty'       => $request->set_qty[$array],
                    'set_nominal'   => $request->set_nominal[$array],
                    'note'          => $request->note[$array],
                    'set_files'     => $request->has('set_files') ? Storage::disk('public')->put('cash_advance', $file[$array]) : $up_file,
                    'total_nominal' => $request->set_qty[$array] * $request->set_nominal[$array],
                ];
                $cash_set = CashAdvSettlement::where('id', $request->id_cash_dtl[$array])->update($set_in_cash);
            }
        }
        $cash1 = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        $sets  = DB::table('finance_cash_adv_settlement')->where('id_cash_dtl', $request->id_dtl)->sum('total_nominal');
        $dtl   = [
            'total_settlement' => $sets,
        ];
        $hist = [
            'id_cash'       => $cash->id,
            'activity'      => "Menambahkan Detail Settlement",
            'activity_user' => getUserEmp(Auth::id())->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        $hist  = CashAdvanceHistory::create($hist);
        $cash_dtl = CashAdvanceDesc::where('id', $request->id_dtl)->update($dtl);
        return redirect('finance/cash_advance/' . $cash->id . '/settlement')->with('success', 'Detail Settlement Created Successfully');
    }


    public function showdetail_settlement(Request $request)
    {
        $dtl     = CashAdvanceDesc::where('id', $request->id_dtl)->first();
        $cash    = CashAdvance::where('id', $dtl->id_cash)->first();
        $set_dtl = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        return view('finance.cash_advance.detail_cash_set.show_detail_settlement', [
            'dtl'     => $dtl,
            'cash'    => $cash,
            'set_dtl' => $set_dtl,
        ]);
    }


    public function delete_detailsettlement(Request $request)
    {
        DB::table('finance_cash_adv_settlement')->where('id', $request->id_set)->delete();
    }


    public function print_settlement($id)
    {
        $cash    = CashAdvance::where('id', $id)->first();
        $dtl     = CashAdvanceDesc::where('id_cash', $cash->id)->first();
        $set_dtl = CashAdvSettlement::where('id_cash', $cash->id)->get();
        $fin_sets = FinanceSettlementModel::where('no_ref', $cash->no_cashadv)->first();
        $spv_app = CashAdvanceApp::where('id_cash', $cash->id)->where('approval_by', 'Supervisor')->first();
        $pdf  = PDF::loadview('pdf.cash_adv_settlement', [
            'main'    => $cash,
            'dtl'     => $dtl,
            'sett'    => $set_dtl,
            'fin_set' => $fin_sets,
            'spv_app' => $spv_app,
            'time'    => Carbon::now('GMT+7')->format('d F Y')
        ]);
        return $pdf->download('MEG Settlement-' . $fin_sets->no_settlement . '.pdf');
    }

    public function settlement_approval($id, $type)
    {
        $cash    = CashAdvance::where('id', $id)->first();
        $dtl     = CashAdvanceDesc::where('id_cash', $cash->id)->first();
        $set_dtl = CashAdvSettlement::where('id_cash', $cash->id)->get();
        $fin_sets = FinanceSettlementModel::where('no_ref', $cash->no_cashadv)->first();
        $app_by  = $type == "Supervisor" ? "Supervisor" : "Finance Manager";
        $st_app  = $type == "Supervisor" ? "spv" : "finance_manager";
        $data = [
            'employee_id'   => $cash->emp_id,
            'id_settlement' => $fin_sets->id,
            'no_settlement' => $fin_sets->no_settlement,
            'approval_by'   => $app_by,
            'status_app'    => $st_app,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $finance_app = FinanceSettlementApp::create($data);
        return redirect('finance/cash_advance')->with('success', 'Approved Successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cash = CashAdvance::where('id', $id)->first();
        $dtl  = CashAdvanceDesc::where('id_cash', $id)->get();
        $dtl1 = CashAdvanceDesc::where('id_cash', $id)->get();
        $emp  = EmployeeModel::all();
        $div  = role_division::all();
        return view('finance.cash_advance.edit', [
            'cash'     => $cash,
            'go'       => Carbon::parse($cash->tgl_berangkat)->format('d-F-Y'),
            'end'      => Carbon::parse($cash->tgl_pulang)->format('d-F-Y'),
            'dtl1'     => $dtl1,
            'dtl'      => $dtl,
            'employee' => $this->AllEmp(),
            'division' => $this->AllDivision(),
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'method'   => "put",
            'action'   => ['Finance\CashAdvanceController@update', $id],
        ]);
    }


    public function edit_finance($id)
    {
        $cash = CashAdvance::where('id', $id)->first();
        $dtl  = CashAdvanceDesc::where('id_cash', $id)->get();
        $dtl1 = CashAdvanceDesc::where('id_cash', $id)->get();
        $emp  = EmployeeModel::all();
        $div  = role_division::all();
        return view('finance.cash_advance.edit_finance', [
            'cash'     => $cash,
            'go'       => Carbon::parse($cash->tgl_berangkat)->format('d-F-Y'),
            'end'      => Carbon::parse($cash->tgl_pulang)->format('d-F-Y'),
            'dtl1'     => $dtl1,
            'dtl'      => $dtl,
            'employee' => $this->AllEmp(),
            'division' => $this->AllDivision(),
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'method'   => "put",
            'action'   => ['Finance\CashAdvanceController@update', $id],
        ]);
    }


    public function destroy($id)
    {
        $cash = CashAdvance::findOrFail($id);
        $query = 'DELETE finance_cash_adv, finance_cash_adv_detail
        FROM finance_cash_adv JOIN finance_cash_adv_detail on finance_cash_adv_detail.id_cash = finance_cash_adv.id WHERE finance_cash_adv.id = ?';
        DB::delete($query, array($id));

        return redirect()->back();
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
        if ($request->type_cash == "dinas") {
            return $this->saveUpdate($request, 'updated');
        } else {
            return $this->saveBlankUpdate($request, 'updated');
        }
    }

    public function saveUpdate($request, $save, $id = 0)
    {
        $chectgl = $request->has('tgl_pekerjaan') ? $request->tgl_pekerjaan : $request->tgl_pekerjaan_add;
        $mine    = getUserEmp(Auth::id());
        $emp     = EmployeeModel::where('id', $request->emp_id)->first();
        $cash    = CashAdvance::where('id', $request->id)->first();
        $dtl     = CashAdvanceDesc::where('id_cash', $cash->id)->get();
        $adds    = $request->input('nama_pekerjaan_add');
        $cdtl    = CashAdvanceDesc::where('id_cash', $cash->id)->get()->count();
        $creq    = count($chectgl);
        $status  = $request->has('type_edit') ? $cash->status : "Pending";
        $cash    = [
            'emp_id'        => $request->input('emp_id'),
            'des_kota'      => $request->input('des_kota'),
            'des_provinsi'  => $request->input('des_provinsi'),
            'status'        => $status,
            'no_cashadv'    => $cash->no_cashadv,
            'tgl_berangkat' => $request->input('tgl_berangkat'),
            'tgl_pulang'    => $request->input('tgl_pulang'),
            'updated_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'mtd_cash'      => $request->input('mtd_cash'),
            'rek_bank'      => $request->input('bank'),
            'no_rek'        => $request->input('no_rek'),
            'nama_rek'      => $request->input('Nama_rek'),
            'cabang_rek'    => $request->input('cabang'),
            'est_waktu'     => $request->input('est_waktu'),
            'updated_by'    => Auth::id(),
        ];
        $hist = [
            'id_cash'       => $request->id,
            'activity'      => "Mengubah Data Cash Advance",
            'activity_user' => $mine->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $act  = CashAdvanceHistory::create($hist);
        $qry  = CashAdvance::where('id', $request->id)->update($cash);
        if ($request->has('nama_pekerjaan_add') || $creq > $cdtl || $creq < $cdtl) {
            $cash = CashAdvance::where('id', $request->id)->first();
            foreach ($adds as $add => $q) {
                $detail = [
                    'id_cash'        => $request->id,
                    'no_cashadv'     => $cash->no_cashadv,
                    'tgl_pekerjaan'  => Carbon::parse($request->tgl_pekerjaan_add[$add])->format('Y-m-d'),
                    'nama_pekerjaan' => $request->nama_pekerjaan_add[$add],
                    'deskripsi'      => $request->deskripsi_add[$add],
                    'status'         => $status,
                    'est_biaya'      => $request->est_biaya_add[$add],
                    'created_by'     => Auth::id(),
                ];
                $qry_create = CashAdvanceDesc::create($detail);
            }
            if($request->has('nama_pekerjaan')){
                $tgls = $request->input('nama_pekerjaan');
                foreach ($tgls as $tgl => $q) {
                    $detail_ups = [
                        'id_cash'        => $request->id,
                        'no_cashadv'     => $cash->no_cashadv,
                        'tgl_pekerjaan'  => Carbon::parse($request->tgl_pekerjaan[$tgl])->format('Y-m-d'),
                        'nama_pekerjaan' => $request->nama_pekerjaan[$tgl],
                        'deskripsi'      => $request->deskripsi[$tgl],
                        'status'         => $status,
                        'est_biaya'      => $request->est_biaya[$tgl],
                        'created_by'     => Auth::id(),
                    ];
                    $qry1 = CashAdvanceDesc::where('id', $request->id_dtl[$tgl])->update($detail_ups);
                }
            }
            
        } else {
            $tgl  = $request->input('nama_pekerjaan');
            $cash = CashAdvance::where('id', $request->id)->first();
            $dtl  = CashAdvanceDesc::where('id_cash', $cash->id)->get();
            foreach ($tgl as $tgl => $q) {
                $detail = [
                    'id_cash'        => $request->id,
                    'no_cashadv'     => $cash->no_cashadv,
                    'tgl_pekerjaan'  => Carbon::parse($request->tgl_pekerjaan[$tgl])->format('Y-m-d'),
                    'nama_pekerjaan' => $request->nama_pekerjaan[$tgl],
                    'deskripsi'      => $request->deskripsi[$tgl],
                    'status'         => $status,
                    'est_biaya'      => $request->est_biaya[$tgl],
                    'updated_by'     => Auth::id(),
                ];
                $qry1 = CashAdvanceDesc::where('id', $request->id_dtl[$tgl])->update($detail);
            }
        }

        $hist = [
            'activity_name'      => "Mengubah Cash Advance",
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi' => $request->id,
        ];
        $act    = FinanceHistory::create($hist);

        return redirect('finance/cash_advance')->with('success', 'Updated Succesfully');
    }


    public function saveBlankUpdate($request, $save, $id = 0)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $emp   = EmployeeModel::where('id', $request->emp_id)->first();
        $tgl   = $request->input('tgl_blank');
        $cash  = CashAdvance::where('id', $request->id)->first();
        $dtl   = CashAdvanceDesc::where('id_cash', $cash->id)->get();
        $adds  = $request->input('tgl_blank_add');
        $cdtl  = CashAdvanceDesc::where('id_cash', $cash->id)->get()->count();
        $creq  = count($request->tgl_blank);
        $status = $request->has('type_edit') ? $cash->status : "Pending";
        $cash  = [
            'emp_id'       => $request->input('emp_id'),
            'des_tujuan'   => $request->input('des_tujuan'),
            'type_cash'    => $request->type_cash,
            'status'        => $status,
            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString(),
            'mtd_cash'     => $request->input('mtd_cash'),
            'rek_bank'     => $request->input('bank'),
            'no_rek'       => $request->input('no_rek'),
            'nama_rek'     => $request->input('Nama_rek'),
            'cabang_rek'   => $request->input('cabang'),
            'updated_by'   => Auth::id(),
        ];
        $hist = [
            'id_cash'       => $request->id,
            'activity'      => "Mengubah Data Cash Advance",
            'activity_user' => $mine->id,
            'created_by'    => Auth::id(),
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $act  = CashAdvanceHistory::create($hist);
        $qry  = CashAdvance::where('id', $request->id)->update($cash);
        if ($request->has('tgl_blank_add') || $creq > $cdtl || $creq < $cdtl) {
            $cash  = CashAdvance::where('id', $request->id)->first();
            foreach ($adds as $add => $q) {
                $detail = [
                    'id_cash'        => $request->id,
                    'no_cashadv'     => $cash->no_cashadv,
                    'tgl_pekerjaan'  => Carbon::parse($request->tgl_blank_add[$add])->format('Y-m-d'),
                    'deskripsi'      => $request->desk_blank_add[$add],
                    'status'         => $status,
                    'est_biaya'      => $request->nominals_add[$add],
                    'created_by'     => Auth::id(),
                ];
                $qry_create = CashAdvanceDesc::create($detail);
            }

            foreach ($tgl as $tgl => $q) {
                $detail_up = [
                    'id_cash'        => $request->id,
                    'no_cashadv'     => $cash->no_cashadv,
                    'tgl_pekerjaan'  => Carbon::parse($request->tgl_blank[$tgl])->format('Y-m-d'),
                    'deskripsi'      => $request->desk_blank[$tgl],
                    'status'         => $status,
                    'est_biaya'      => $request->nominals[$tgl],
                    'created_by'     => Auth::id(),
                ];
                $qry1 = CashAdvanceDesc::where('id', $request->id_dtl[$tgl])->update($detail_up);
            }
        } else {
            $cash  = CashAdvance::where('id', $request->id)->first();
            $dtl   = CashAdvanceDesc::where('id_cash', $cash->id)->get();
            foreach ($tgl as $tgl => $q) {
                $detail = [
                    'id_cash'        => $request->id,
                    'no_cashadv'     => $cash->no_cashadv,
                    'tgl_pekerjaan'  => Carbon::parse($request->tgl_blank[$tgl])->format('Y-m-d'),
                    'deskripsi'      => $request->desk_blank[$tgl],
                    'status'        => $status,
                    'est_biaya'      => $request->nominals[$tgl],
                    $save . '_by'      => Auth::id(),
                ];
                $qry1 = CashAdvanceDesc::where('id', $request->id_dtl[$tgl])->update($detail);
            }
        }

        $hist = [
            'activity_name'      => "Mengubah Cash Advance",
            'activity_user'      => $mine->id,
            'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'         => Auth::id(),
            'status_activity'    => "Cash Advance",
            'activity_refrensi' => $request->id,
        ];
        $act    = FinanceHistory::create($hist);

        return redirect('finance/cash_advance')->with('success', 'Updated Succesfully');
    }


    public function PDF_CashAdv($id)
    {
        // dd($id);
        $cash   = CashAdvance::where('id', $id)->first();
        $dtl    = CashAdvanceDesc::where('id_cash', $cash->id)->get();
        $join   = CashAdvanceDesc::join('finance_cash_adv as c', 'c.id', '=', 'finance_cash_adv_detail.id_cash')->where('id_cash', $id)->get();
        $qrc    = QrCode::format('png')->size(300)->generate('myNote');
        $app    = CashAdvance::where('id', $id)->where('status', 'LIKE', '%Approved%')->first();
        $manage = CashAdvanceApp::where('id_cash', $cash->id)->where('approval_by', '!=', 'Finance')->orderBy('id', 'DESC')->first();
        $financ = CashAdvanceApp::where('id_cash', $cash->id)->where('status_app', 'Completed')->first();
        $view   = $cash->type_cash == "dinas" ? 'pdf.cash_adv' : 'pdf.cash_adv_blank';
        $pdf    = PDF::loadview($view, [
            'main'   => $cash,
            'dtl'    => $dtl,
            'qr'     => $qrc,
            'fins'   => $financ,
            'manage' => $manage,
            'time'   => Carbon::now('GMT+7')->format('d F Y')
        ]);
        return $pdf->download('MEG.pdf');
    }


    public function PDF_CashAdv_lama($id)
    {
        // dd($id);
        $cash   = CashAdvance::where('id', $id)->first();
        $dtl    = CashAdvanceDesc::where('id_cash', $cash->id)->get();
        $join   = CashAdvanceDesc::join('finance_cash_adv as c', 'c.id', '=', 'finance_cash_adv_detail.id_cash')->where('id_cash', $id)->get();
        $qrc    = QrCode::format('png')->size(300)->generate('myNote');
        $app    = CashAdvance::where('id', $id)->where('status', 'LIKE', '%Approved%')->first();
        $manage = CashAdvanceApp::where('id_cash', $cash->id)->where('approval_by', '!=', 'Finance')->orderBy('id', 'DESC')->first();
        $financ = CashAdvanceApp::where('id_cash', $cash->id)->where('status_app', 'Completed')->first();
        $pdf    = PDF::loadview('pdf.cash_adv', [
            'main'   => $cash,
            'dtl'    => $dtl,
            'fins'   => $financ,
            'manage' => $manage,
            'qr'     => $qrc,
            'time'   => Carbon::now('GMT+7')->format('d F Y')
        ]);
        return $pdf->download('MEG.pdf');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function ajax_data(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $emp  = EmployeeModel::where('id', $mine->spv_id)->first();
        $under = EmployeeModel::where('spv_id', $mine->id)->get()->count();
        $usr  = Auth::id();

        if ($mine->division_id == 3 || $mine->id == 2) {
            return $this->ajax_finance($request);
        } else if ($under != 0) {
            return $this->ajax_Management($request, $mine, $emp);
            // }else if($mine->spv_id == $emp->id){
            //     return $this->ajax_spv($request);
        } else {
            $columns = array(
                0 => 'emp_id',
                1 => 'tujuan',
                2 => 'status',
                3 => 'created_at',
                4 => 'id',
                5 => 'no_cashadv',
                6 => 'status_set',
            );

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order')[0]['column']];
            $dir   = $request->input('order')[0]['dir'];

            $menu_count    = CashAdvance::where('created_by', $usr)->get();
            $totalData     = $menu_count->count();
            $totalFiltered = $totalData;

            if (empty($request->input('search')['value'])) {
                $posts = CashAdvance::select('*')->where('created_by', $usr)
                    ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            } else {
                $search        = $request->input('search')['value'];
                $posts         = CashAdvance::where('created_by', $usr)->where('emp_id', 'like', '%' . $search . '%')
                    ->orderby($order, $dir)->offset($start)->limit($limit)->get();
                $totalFiltered = CashAdvance::where('created_by', $usr)->where('emp_id', 'like', '%' . $search . '%')
                    ->orderby($order, $dir)->offset($start)->limit($limit)->count();
            }
            //  dd($posts);

            $data = [];
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $detail = CashAdvanceDesc::where('id_cash', $post->id)->get()->sum('est_biaya');
                    if ($post->no_cashadv != null) {
                        $ref = FinanceSettlementModel::where([
                            ['no_ref', $post->no_cashadv],
                            ['status', 'Completed']
                        ])->first();
                    } else {
                        $ref = null;
                    }

                    if ($post->type_cash == "dinas") {
                        $tujuan = city($post->des_kota) . ', ' . province($post->des_provinsi);
                    } else {
                        $tujuan = $post->des_tujuan;
                    }
                    $data[] = [
                        'emp_id'     => emp_name($post->emp_id),
                        'tujuan'     => $tujuan,
                        'status'     => $post->status,
                        'nominal'    => $detail,
                        'created_at' => Carbon::parse($post->created_at)->format('d F Y'),
                        'id'         => $post->id,
                        'app_hr'     => $post->app_hr == null ? null : user_name($post->app_hr),
                        'user'       => "other",
                        'ref'        => $ref == null ? "none" : $ref->no_settlement,
                        'no_cashadv' => $post->no_cashadv,
                        'status_set' => $post->status_set,
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
            0 => 'emp_id',
            1 => 'tujuan',
            2 => 'status',
            3 => 'created_at',
            4 => 'id',
            5 => 'no_cashadv',
            6 => 'status_set',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = CashAdvance::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = CashAdvance::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" when (status = "Approved" AND app_spv IS NOT NULL AND app_hr IS NULL) then "C" when (status = "Approved" AND app_spv IS NOT NULL AND app_hr IS NOT NULL) then "D" when (status = "Completed") then "E" else "Z" end) as status_sort'))
                ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = CashAdvance::join('employees', 'employees.id', '=', 'finance_cash_adv.emp_id')->where('emp_name', 'like', '%' . $search . '%')
                ->orderby('finance_cash_adv.' . $order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered =  CashAdvance::join('employees', 'employees.id', '=', 'finance_cash_adv.emp_id')->where('emp_name', 'like', '%' . $search . '%')
                ->orderby('finance_cash_adv.' . $order, $dir)->offset($start)->limit($limit)->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $detail = CashAdvanceDesc::where('id_cash', $post->id)->get()->sum('est_biaya');
                if ($post->no_cashadv != null) {
                    $ref = FinanceSettlementModel::where([
                        ['no_ref', $post->no_cashadv],
                        ['status', 'Completed']
                    ])->first();
                } else {
                    $ref = null;
                }
                if ($post->type_cash == "dinas") {
                    $tujuan = city($post->des_kota) . ', ' . province($post->des_provinsi);
                } else {
                    $tujuan = $post->des_tujuan;
                }
                $data[] = [
                    'emp_id'     => emp_name($post->emp_id),
                    'tujuan'     => $tujuan,
                    'status'     => $post->status,
                    'nominal'    => $detail,
                    'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'         => $post->id,
                    'app_hr'     => $post->app_hr == null ? null : user_name($post->app_hr),
                    'by'         => $post->created_by == Auth::id() ? "edit" : "no edit",
                    'user'       => "finance",
                    'ref'        => $ref == null ? "none" : $ref->no_settlement,
                    'no_cashadv' => $post->no_cashadv,
                    'status_set' => $post->status_set,
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


    public function ajax_spv($request)
    {
        $columns = array(
            0 => 'emp_id',
            1 => 'div_id',
            2 => 'tujuan',
            3 => 'status',
            4 => 'created_at',
            5 => 'id',
            6 => 'no_cashadv',
            7 => 'status_set',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = CashAdvance::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = CashAdvance::select('*')
                ->orderby($order, $dir)->limit($limit, $start)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = CashAdvance::where('emp_id', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->limit($limit, $start)->get();
            $totalFiltered = CashAdvance::where('emp_id', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->limit($limit, $start)->count();
        }
        //  dd($posts);
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $detail = CashAdvanceDesc::where('id_cash', $post->id)->get()->sum('est_biaya');
                $data[] = [
                    'emp_id'     => emp_name($post->emp_id),
                    'div_id'     => div_name($post->div_id),
                    'nominal'    => $detail,
                    'tujuan'     => city($post->des_kota) . ', ' . province($post->des_provinsi),
                    'status'     => $post->status,
                    'app_hr'     => $post->app_hr == null ? null : user_name($post->app_hr),
                    'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'         => $post->id,
                    'no_cashadv' => $post->no_cashadv,
                    'status_set' => $post->status_set,
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



    public function ajax_Management($request, $mine, $emp)
    {
        $columns = array(
            0 => 'emp_id',
            1 => 'div_id',
            2 => 'tujuan',
            3 => 'status',
            4 => 'created_at',
            5 => 'id',
            6 => 'no_cashadv',
            7 => 'status_set',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = CashAdvance::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = CashAdvance::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" when (status = "Approved" AND app_spv IS NOT NULL AND app_hr IS NULL) then "C" when (status = "Approved" AND app_spv IS NOT NULL AND app_hr IS NOT NULL) then "D" when (status = "Completed") then "E" else "Z" end) as status_sort'))
                ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = CashAdvance::where('emp_id', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = CashAdvance::where('emp_id', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        //  dd($posts);
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $detail = CashAdvanceDesc::where('id_cash', $post->id)->get()->sum('est_biaya');
                $created = getUserEmp($post->created_by)->spv_id;
                if ($created == getUserEmp(Auth::id())->id || $post->created_by == Auth::id()) {
                    if ($post->no_cashadv != null) {
                        $ref = FinanceSettlementModel::where([
                            ['no_ref', $post->no_cashadv],
                            ['status', 'Completed']
                        ])->first();
                    } else {
                        $ref = null;
                    }
                    if ($post->type_cash == "dinas") {
                        $tujuan = city($post->des_kota) . ', ' . province($post->des_provinsi);
                    } else {
                        $tujuan = $post->des_tujuan;
                    }
                    $data[] = [
                        'emp_id'     => emp_name($post->emp_id),
                        'tujuan'     => $tujuan,
                        'status'     => $post->status,
                        'nominal'    => $detail,
                        'by'         => $post->created_by == Auth::id() ? "edit" : "no edit",
                        'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                        'id'         => $post->id,
                        'app_hr'     => $post->app_hr == null ? null : user_name($post->app_hr),
                        'user'       => "management",
                        'ref'        => $ref == null ? "none" : $ref->no_settlement,
                        'no_cashadv' => $post->no_cashadv,
                        'status_set' => $post->status_set,
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


    function add_kegiatan(Request $request)
    {
        // dd($request);
        return view("finance.cash_advance.attribute.add_kegiatan", [
            'n_equ'  => $request->n_equ,
        ]);
    }


    function addBlank_kegiatan(Request $request)
    {
        return view("finance.cash_advance.attribute.addBlank_kegiatan", [
            'n_equ'  => $request->n_equ,
        ]);
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

    function add_form(Request $request)
    {
        $dtl  = CashAdvanceDesc::where('id', $request->id_dtl)->first();
        $cash = CashAdvance::where('id', $dtl->id_cash)->first();
        $set  = CashAdvSettlement::where('id_cash_dtl', $request->id_dtl)->get();
        return view('finance.cash_advance.detail_cash_set.form_detail_set', [
            'n_equ'  => $request->n_equ,
            'dtl'    => $dtl,
            'cash'   => $cash,


        ]);
    }
}
