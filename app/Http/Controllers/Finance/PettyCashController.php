<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinancePettyCashUpload;
use App\Models\Finance\PettyCashModel;
use App\Models\Finance\PettyCashCode;
use App\Models\HR\EmployeeModel;
use App\Models\Role\Role_cabang;
use Storage;
use Auth;
use DB;
use Carbon\Carbon;

class PettyCashController extends Controller
{
    //
    public function index()
    {
        return view('finance.petty_cash.index');
    }


    public function create($type)
    {
        return view('finance.petty_cash.detail.create', [
            'pay_code' => $this->getCode(),
            'pic'      => $this->getPIC(),
            'type'     => $type,
            'action'   => "Finance\PettyCashController@store",
            'method'   => "post",
        ]);
    }


    public function find_code(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = PettyCashCode::select("id", "code", "type_name")
                ->where('code', 'LIKE', "%$search%")
                ->orWhere('type_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }


    public function modal_call(Request $request)
    {
        return view('finance.petty_cash.detail.form_newcode', [
            'method'   => "post",
            'action'   => 'Finance\PettyCashController@saveNewCode',
        ]);
    }

    public function saveNewCode(Request $request)
    {
        dd($request);
    }


    public function getCode()
    {
        $data = PettyCashCode::all();
        $arr  = array();
        foreach ($data as $reg) {


            $arr[$reg->id] = $reg->code . " - " . $reg->type_name;
        }
        return $arr;
    }

    public function getPIC()
    {
        $data   = EmployeeModel::where('emp_status', 'Active')->get();
        $data1  = Role_cabang::all();
        $arr    = array();
        $arr_in = array();
        $meg    = array(
            'MEG' => 'MEG',
        );
        $adds = array();
        $yohan = EmployeeModel::where('id', 55)->get();
        foreach ($yohan as $reg) {
            $adds[$reg->emp_name] = $reg->emp_name;
        }
        // $other  = array();
        $combine = array();
        foreach ($data as $reg) {
            $arr[$reg->emp_name] = $reg->emp_name;
        }
        foreach ($data1 as $req) {
            $arr_in[$req->cabang_name] = "Cabang " . $req->cabang_name;
        }

        $combine = array_merge($arr, $arr_in, $meg, $adds);

        return $combine;
    }

    public function ajax_data(Request $request)
    {

        $columns = array(
            0 => 'created_by',
            1 => 'nama_dok',
            2 => 'created_at',
            3 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = PettyCashModel::groupBy('month')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;


        if (empty($request->input('search')['value'])) {
            $posts = PettyCashModel::select('*')->addSelect(DB::raw('MONTH(date) bulan'))->groupBy('month')->groupBy('year')
                ->orderby('bulan', $dir)->offset($start)->limit($limit)->get();
        } else {
            $search = $request->input('search')['value'];
            $posts  = PettyCashModel::select('*')->addSelect(DB::raw('MONTH(date) bulan'))->groupBy('month')->groupBy('year')->where('year', 'like', '%' . $search . '%')
                ->orderby('bulan', $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = PettyCashModel::select('*')->addSelect(DB::raw('MONTH(date) bulan'))->groupBy('month')->groupBy('year')->where('year', 'like', '%' . $search . '%')
                ->orderby('bulan', $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'created_by' => user_name($post->created_by),
                    'year'       => $post->year . " " . $post->month,
                    'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'         => $post->id,
                    'group'      => $post->month . '-' . $post->year,
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



    public function ajax_detail(Request $request)
    {
        $exp   = explode('-', $request->what);
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'description',
            3 => 'code',
            4 => 'pic',
            5 => 'nominal',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = PettyCashModel::where([['month', $exp[0]], ['year', $exp[1]]])->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = PettyCashModel::select('*')->where([['month', $exp[0]], ['year', $exp[1]]])
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search = $request->input('search')['value'];
            $posts  = PettyCashModel::where([['month', $exp[0]], ['year', $exp[1]]])->where('year', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = PettyCashModel::where([['month', $exp[0]], ['year', $exp[1]]])->where('year', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        }
        // dd($posts);
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'date'       => Carbon::parse($post->date)->format('d F Y'),
                    'description' => $post->description,
                    'code'       => Codetype($post->code_id)->code . ' - ' . $post->remarks,
                    'id'         => $post->id,
                    'pic'        => $post->pic,
                    'method'     => $post->transaksi,
                    'other_pic'  => $post->other_pic,
                    'receipt'    => $post->receipt,
                    'nominal'    => $post->nominal,
                    'created_at' => user_name($post->created_by),
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


    public function ajax_dokumen(Request $request)
    {
        $exp   = explode('-', $request->what);
        $columns = array(
            0 => 'created_by',
            1 => 'nama_dok',
            2 => 'created_at',
            3 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = FinancePettyCashUpload::where([['month', $exp[0]], ['year', $exp[1]]])->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = FinancePettyCashUpload::select('*')->where([['month', $exp[0]], ['year', $exp[1]]])
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search = $request->input('search')['value'];
            $posts  = FinancePettyCashUpload::where([['month', $exp[0]], ['year', $exp[1]]])->where('nama_dok', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = FinancePettyCashUpload::where([['month', $exp[0]], ['year', $exp[1]]])->where('nama_dok', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'created_by' => user_name($post->created_by),
                    'nama_dok'   => $post->nama_dok,
                    'file'       => $post->dokumen,
                    'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'id'         => $post->id,
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



    public function pettycash_detail($id)
    {
        $dtl   = PettyCashModel::where('id', $id)->first();
        return view('finance.petty_cash.detail.show_detail', [
            'dtl'  => $dtl,
        ]);
    }


    public function edit_detail($id)
    {
        $dtl   = PettyCashModel::where('id', $id)->first();
        return view('finance.petty_cash.detail.edit_detail', [
            'dtl'     => $dtl,
            'getcode' => $this->getCode(),
            'pic'     => $this->getPIC(),
        ]);
    }

    public function save_edit_detail(Request $request)
    {
        // dd($request);
        $detail = PettyCashModel::where('id', $request->id)->first();
        if (!empty($request->file('receipt'))) {
            $file = $request->file('receipt')->getClientOriginalName();
        }

        $data = [
            'date'        => $request->date,
            'description' => $request->description,
            'code_id'     => $request->code_id,
            'pic'         => $request->pic,
            'other_pic'   => $request->other_pic,
            'nominal'     => $request->nominal,
            'transaksi'   => $request->type_payment,
            'remarks'     => Codetype($request->code_id)->type_name,
            'date'        => $request->date,
            'year'        => Carbon::parse($request->date)->format('Y'),
            'month'       => Carbon::parse($request->date)->format('F'),
            'receipt'     => !empty($request->file('receipt')) ? Storage::disk('public')->putFileAs('new_finance/petty_cash', $request->file('receipt'), $file) : $detail->receipt,
            'updated_by'  => Auth::id(),
            'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry = PettyCashModel::where('id', $request->id)->update($data);
        return redirect('finance/pettycash/' . $request->month . '-' . $request->year . '/show')->with('success', 'Updated Successfully');
    }

    public function delete_detail($id)
    {
        $pettcash = PettyCashModel::find($id);
        $pettcash->delete();
        return redirect()->back()->with('success', 'Delete Successfully');
    }



    public function edit_dokumen($id)
    {
        $dok   = FinancePettyCashUpload::where('id', $id)->first();
        return view('finance.petty_cash.dokumen.edit_dokumen', [
            'dok'     => $dok,
        ]);
    }

    public function save_edit_dokumen(Request $request)
    {
        // dd($request);
        if (!empty($request->file('files'))) {
            $file = $request->file('files')->getClientOriginalName();
        }
        $detail = FinancePettyCashUpload::where('id', $request->id)->first();
        $data = [
            'nama_dok'    => $request->doc_name,
            'dokumen'     => !empty($request->file('files')) ? Storage::disk('public')->putFileAs('new_finance/petty_cash', $request->file('files'), $file) : $detail->files,
            'updated_by'  => Auth::id(),
            'updated_at'  => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry = FinancePettyCashUpload::where('id', $request->id)->update($data);
        return redirect('finance/pettycash/' . $request->month . '-' . $request->year . '/show')->with('success', 'Updated Successfully');
    }


    public function delete_dokumen($id)
    {
        $pettcash = FinancePettyCashUpload::find($id);
        $pettcash->delete();
        return redirect()->back()->with('success', 'Delete Successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        if (!empty($request->file('receipt'))) {
            $file = $request->file('receipt')->getClientOriginalName();
        }
        $pays = $request->type_payment == "debit" ? 'debit' : 'credit';


        $redirect = $request->text == "new" ? 'finance/pettycash' : 'finance/pettycash/' . $request->text . '/show';
        $data = [
            'description' => $request->description,
            'code_id'     => $request->code_id,
            'remarks'     => Codetype($request->code_id)->type_name,
            'nominal'     => $request->nominal,
            'pic'         => $request->pic,
            'transaksi'   => $request->type_payment,
            'date'        => $request->date,
            'other_pic'   => $request->other_pic,
            'year'        => Carbon::parse($request->date)->format('Y'),
            'month'       => Carbon::parse($request->date)->format('F'),
            'receipt'     => !empty($request->file('receipt')) ? Storage::disk('public')->putFileAs('new_finance/petty_cash', $request->file('receipt'), $file) : null,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => Auth::id(),
        ];
        $qry = PettyCashModel::create($data);
        return redirect($redirect)->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exp   = explode('-', $id);
        $dok   = FinancePettyCashUpload::where([['month', $exp[0]], ['year', $exp[1]]])->get();
        $dtl   = PettyCashModel::where([['month', $exp[0]], ['year', $exp[1]]])->get();
        return view('finance.petty_cash.show', [
            'dtl'  => $dtl,
            'dok'  => $dok,
            'month' => $exp[0],
            'year' => $exp[1],
            'id'   => $id,
        ]);
    }

    /////////////////////// DOKUMEN //////////////////
    /////////////////////////////////////////////////

    public function create_dokumen($id)
    {
        $ex   = explode('-', $id);
        return view('finance.petty_cash.dokumen.create', [
            'month' => $ex[0],
            'year'  => $ex[1],
        ]);
    }


    public function new_upload_file(Request $request)
    {
        return view('upload.more_create', [
            'num'      => $request->n_equ + 1,
        ]);
    }

    public function upload_file(Request $request)
    {
        $a = $request->doc_name;
        foreach ($a as $value => $y) {
            $file = $request->file('files')[$value]->getClientOriginalName();
            $data = [
                'month'      => $request->month,
                'year'       => $request->year,
                'nama_dok'   => $request->doc_name[$value],
                'dokumen'    => Storage::disk('public')->putFileAs('new_finance/petty_cash', $request->file('files')[$value], $file),
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ];
            // dd($data);
            $qry = FinancePettyCashUpload::create($data);
        }
        return redirect('finance/pettycash/' . $request->month . '-' . $request->year . '/show')->with('success', 'Uploaded Files Successfully');
    }


    public function dokumen_detail($id)
    {
        // dd($id);
        $dok   = FinancePettyCashUpload::where('id', $id)->first();
        return view('finance.petty_cash.dokumen.show_dok', [
            'dok'  => $dok,
            'month' => $dok->month,
            'year' => $dok->year,
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
}
