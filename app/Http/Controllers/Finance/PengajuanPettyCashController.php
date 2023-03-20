<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinancePettyCashUpload;
use App\Models\Finance\PettyCashModel;
use App\Models\Finance\PettyCashCode;
use App\Models\Finance\PengajuanPettyCash;
use App\Models\Finance\PengajuanPettyCashDetail;
use Carbon\Carbon;
use App\Models\Finance\FinanceHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDF;
use DB;
use Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PengajuanPettyCashController extends Controller
{
    public function index()
    {
        return view('finance.petty_cash.pengajuan.index');
    }

    public function create()
    {
        return view('finance.petty_cash.pengajuan.create', [
            'action'   => "Finance\PengajuanPettyCashController@store",
            'method'   => "post",
        ]);
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'created_by',
            1 => 'month',
            2 => 'created_at',
            3 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = PengajuanPettyCash::groupBy('created_at')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;


        if (empty($request->input('search')['value'])) {
            $posts = PengajuanPettyCash::select('*')
                ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" else "Y" end) as status_sort'))
                ->groupBy('created_at')
                ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->limit($limit, $start)->get();
        } else {
            $search = $request->input('search')['value'];
            $posts  = PengajuanPettyCash::select("*")
                ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" else "Y" end) as status_sort'))
                ->groupBy('month')->where('year', 'like', '%' . $search . '%')
                ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->limit($limit, $start)->get();
            $totalFiltered = PengajuanPettyCash::select("*")
                ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Need Approval") then "B" else "Y" end) as status_sort'))
                ->groupBy('month')->where('year', 'like', '%' . $search . '%')
                ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->limit($limit, $start)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $mine = getUserEmp(Auth::id());
                if ($mine->division_id == "3") {
                    $user = "finance";
                } else if (in_array($mine->id, explode(',', getConfig('ajaxmng')))) {
                    $user = "manage";
                }else{
                    $user = "finance";                    
                }
                $data[] = [
                    'created_by' => user_name($post->created_by),
                    'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'         => $post->id,
                    'user'       => $user,
                    'status'     => $post->status,
                    'month'      => Carbon::parse($post->start_date)->format('F') . '-' . Carbon::parse($post->end_date)->format('F'),
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

    public function store(Request $request)
    {
        // dd($request);
        if (!empty($request->file('receipt'))) {
            $file = $request->file('receipt')->getClientOriginalName();
        }
        $pengajuan = [
            'start_date' => $request->from_date,
            'end_date'   => $request->end_date,
            'status'     => "Pending",
            'receipt'    => !empty($request->file('receipt')) ? Storage::disk('public')->putFileAs('new_finance/petty_cash/pengajuan', $request->file('receipt'), $file) : null,
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
        ];
        $qry = PengajuanPettyCash::create($pengajuan);
        if ($qry) {
            $detail = [
                'id_pengajuan' => $qry->id,
                'purpose'     => $request->purpose,
                'nominal'     => $request->nominal,
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'  => Auth::id(),
            ];
            $qry2 = PengajuanPettyCashDetail::create($detail);

            if ($request->has('purpose_add')) {
                $add_purpose = $request->purpose_add;
                foreach ($add_purpose as $add => $c) {
                    $datas = [
                        'id_pengajuan' => $qry->id,
                        'purpose'      => $request->purpose_add[$add],
                        'nominal'      => $request->nominal_add[$add],
                        'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                        'created_by'   => Auth::id(),
                    ];
                    $qry3 = PengajuanPettyCashDetail::create($datas);
                }
            }
        }
        return redirect('finance/pengajuan_pettycash')->with('success', 'Created Successfully');
    }


    public function add_purpose(Request $request)
    {
        if ($request->type == "tambah_datas") {
            return view('finance.petty_cash.pengajuan.attribute.add_purpose', [
                'n_equ' => $request->equ,
            ]);
        } else {
            DB::table('finance_pengajuan_detail_pettycash')->where('id', $request->id_dtl)->delete();
        }
    }


    public function edit($id)
    {
        $pengajuan     = PengajuanPettyCash::where('id', $id)->first();
        $dtl_pengajuan = PengajuanPettyCashDetail::where('id_pengajuan', $pengajuan->id)->get();

        return view('finance.petty_cash.pengajuan.edit', [
            'getdata' => $pengajuan,
            'get_dtl' => $dtl_pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request);
        if (!empty($request->file('receipt'))) {
            $file = $request->file('receipt')->getClientOriginalName();
        }
        $pengajuan = [
            'start_date' => $request->from_date,
            'end_date'   => $request->end_date,
            'receipt'    => !empty($request->file('receipt')) ? Storage::disk('public')->putFileAs('new_finance/petty_cash/pengajuan', $request->file('receipt'), $file) : null,
            'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'updated_by' => Auth::id(),
        ];
        $qry = PengajuanPettyCash::where('id', $request->id)->update($pengajuan);

        if ($request->has('purpose')) {
            $purpose = $request->purpose;
            foreach ($purpose as $ed => $l) {
                $detail = [
                    'id_pengajuan' => $request->id,
                    'purpose'     => $request->purpose[$ed],
                    'nominal'     => $request->nominal[$ed],
                    'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                    'updated_by'  => Auth::id(),
                ];
                $qry2 = PengajuanPettyCashDetail::where('id', $request->id_dtl[$ed])->update($detail);
            }
        }

        if ($request->has('purpose_add')) {
            if ($request->has('purpose')) {
                $purpose = $request->purpose;
                foreach ($purpose as $ed => $l) {
                    $detail = [
                        'id_pengajuan' => $request->id,
                        'purpose'     => $request->purpose[$ed],
                        'nominal'     => $request->nominal[$ed],
                        'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
                        'updated_by'  => Auth::id(),
                    ];
                    $qry2 = PengajuanPettyCashDetail::where('id', $request->id_dtl[$ed])->update($detail);
                }
            }

            $add_purpose = $request->purpose_add;
            foreach ($add_purpose as $add => $c) {
                $datas = [
                    'id_pengajuan' => $request->id,
                    'purpose'      => $request->purpose_add[$add],
                    'nominal'      => $request->nominal_add[$add],
                    'updated_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                    'upadated_by'  => Auth::id(),
                ];
                $qry3 = PengajuanPettyCashDetail::create($datas);
            }
        }
        return redirect('finance/pengajuan_pettycash/' . $request->id . '/edit')->with('success', 'Edit Successfully');
    }


    public function show($id)
    {
        $pengajuan     = PengajuanPettyCash::where('id', $id)->first();
        $dtl_pengajuan = PengajuanPettyCashDetail::where('id_pengajuan', $pengajuan->id)->get();
        $mine          = getUserEmp(Auth::id());
        $hist          = FinanceHistory::where('activity_refrensi', $id)->orderBy('id', 'desc')->get();
        return view('finance.petty_cash.pengajuan.show', [
            'getdata' => $pengajuan,
            'get_dtl' => $dtl_pengajuan,
            'main'    => $mine,
            'hist'    => $hist,
        ]);
    }


    public function add_files(Request $request)
    {
        return view ('finance.petty_cash.pengajuan.attribute.add_file',[
           'id'     => $request->id,
           'method' => "post",
           'action' => "Finance\PengajuanPettyCashController@saveFile",
        ]);
    }


    public function saveFile(Request $request)
    {
        // dd($request);
        $data = [
            'additional_file' => !empty($request->file('additional_file')) ? Storage::disk('public')->putFileAs('new_finance/petty_cash/pengajuan', $request->file('additional_file'), $request->file('additional_file')->getClientOriginalName()) : null,
        ];
        $qry = PengajuanPettyCash::where('id', $request->id)->update($data);
        return redirect()->back();
    }



    public function approvals($id, $type, $usr)
    {
        if($usr=="finance_mng")
        {
            return $this->approval_finance($id, $type, $usr);
        }else{
            if($type=="approve")
            {
                $text = "Approved";
                $data = [
                    'app_by'       => Auth::id(),
                    'status'       => "Approval Completed",
                    'approve_date' => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                $qrys = PengajuanPettyCash::where('id', $id)->update($data);

                $history = [
                    'activity_name'      => "Menyetujui Pengajuan Petty Cash",
                    'activity_user'      => getUserEmp(Auth::id())->id,
                    'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'         => Auth::id(),
                    'status_activity'    => "Pengajuan PettyCash",
                    'activity_refrensi'  => $id,
                ];
                $qry_hist = FinanceHistory::create($history);
            
            }else if($type=="need_approval"){
                $text = "Need Approval";
                $data = [
                    'status'       => "Need Approval",
                ];
                $qrys = PengajuanPettyCash::where('id', $id)->update($data);
            
                $history = [
                    'activity_name'      => "Mengajukan Permintaan Approval",
                    'activity_user'      => getUserEmp(Auth::id())->id,
                    'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'         => Auth::id(),
                    'status_activity'    => "Pengajuan PettyCash",
                    'activity_refrensi'  => $id,
                ];
                $qry_hist = FinanceHistory::create($history);
            }else{
                $text = "Rejected";
                $data = [
                    'reject_by'    => Auth::id(),
                    'status'       => "Rejected",
                    'reject_date'  => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                $qrys = PengajuanPettyCash::where('id', $id)->update($data);

                $history = [
                    'activity_name'      => "Tidak Menyetujui Pengajuan Petty Cash",
                    'activity_user'      => getUserEmp(Auth::id())->id,
                    'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'         => Auth::id(),
                    'status_activity'    => "Pengajuan PettyCash",
                    'activity_refrensi'  => $id,
                ];
                $qry_hist = FinanceHistory::create($history);
            }
            return redirect()->back()->with('success', $text.' Successfully');
        }   
    }


    public function approval_finance($id, $type, $usr)
    {
        if($type=="approve")
        {
            $text = "Approved";
            $data = [
                'app_finance'      => Auth::id(),
                'status'           => "Approved",
                'app_finance_date' => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qrys = PengajuanPettyCash::where('id', $id)->update($data);
            $history = [
                    'activity_name'      => "Menyetujui Pengajuan Petty Cash",
                    'activity_user'      => getUserEmp(Auth::id())->id,
                    'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'         => Auth::id(),
                    'status_activity'    => "Pengajuan PettyCash",
                    'activity_refrensi'  => $id,
                ];
            $qry_hist = FinanceHistory::create($history);
        }
        else{
            $text = "Rejected";
            $data = [
                'app_finance'      => Auth::id(),
                'status'           => "Rejected",
                'app_finance_date' => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qrys = PengajuanPettyCash::where('id', $id)->update($data);
            
            $history = [
                    'activity_name'      => "Tidak Menyetujui Pengajuan Petty Cash",
                    'activity_user'      => getUserEmp(Auth::id())->id,
                    'created_at'         => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'         => Auth::id(),
                    'status_activity'    => "Pengajuan PettyCash",
                    'activity_refrensi'  => $id,
                ];
            $qry_hist = FinanceHistory::create($history);
        }
        return redirect()->back()->with('success', $text.' Successfully');
    }


    public function pdf_pettycash(Request $request)
    {
        // dd($request);
        $pengajuan     = PengajuanPettyCash::where('id', $request->id)->first();
        $dtl_pengajuan = PengajuanPettyCashDetail::where('id_pengajuan', $pengajuan->id)->get();
        $mine          = getUserEmp(Auth::id());
        $qrc           = QrCode::format('png')->size(300)->generate('myNote');
        $title         = Carbon::parse($pengajuan->start_date)->format('F Y') == Carbon::parse($pengajuan->end_date)->format('F Y') ? Carbon::parse($pengajuan->start_date)->format('F Y') : Carbon::parse($pengajuan->start_date)->format('F Y') . ' - ' . Carbon::parse($pengajuan->end_date)->format('F Y');
        $pdf           = PDF::loadview('pdf.pengajuan_pettycash', [
            'getdata' => $pengajuan,
            'detail'  => $dtl_pengajuan,
            'qr'      => $qrc,
            'time'    => Carbon::now('GMT+7')->format('d F Y'),
            'month'   => $title,
        ]);
        return $pdf->download('Pengajuan Petty Cash' . ' ' . Carbon::now()->format('d/m/Y') . '.pdf');
    }

    public function delete($id)
    {
        PengajuanPettyCash::where('id',$id)->delete();
        $text = "Pengajuan berhasil di delete";
        return redirect()->back()->with('success', $text.' Successfully');
    }
}
