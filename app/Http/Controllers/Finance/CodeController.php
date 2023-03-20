<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\PettyCashCode;
use App\Models\Finance\FinancePettyCashUpload;
use App\Models\Finance\PettyCashModel;
use Storage;
use Auth;
use Carbon\Carbon;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('finance.acc_code.index');
    }

    public function ajax_code(Request $request)
    {

         $columns = array(
             0 => 'id',
             1 => 'type',
             2 => 'created_at',
             3 => 'code',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = PettyCashCode::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
         
 
         if (empty($request->input('search')['value'])) {
             $posts = PettyCashCode::select('*')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = PettyCashCode::where('code', 'like', '%' . $search . '%')->orWhere('type_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = PettyCashCode::where('code', 'like', '%' . $search . '%')->orWhere('type_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'code'       => $post->code,
                    'type'       => $post->type_name,
                    'created_at' => Carbon::parse($post->created_at)->format('d F Y'),
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('finance.acc_code.create',[
            'method'  => "POST",
            'action'  => 'Finance\CodeController@store',
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
        // //
        $data = [
            'type_name'  => $request->type,
            'code'       => $request->code,
            'created_by' => Auth::id(),
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qrys = PettyCashCode::create($data);
        return redirect('finance/code_accounting')->with('success', 'Created Successfully');
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
        $codes = PettyCashCode::where('id', $id)->first();
        $petty = PettyCashModel::where('code_id', $id)->get();
        return view('finance.acc_code.show',[
            'data'   => $codes,
            'petty'  => $petty,
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
        $codes = PettyCashCode::where('id', $id)->first();
        $petty = PettyCashModel::where('code_id', $id)->get();
        return view('finance.acc_code.edit',[
            'data'   => $codes,
            'petty'  => $petty,
            'method' => "put",
            'action' => ['Finance\CodeController@update',$id],
        ]);
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
        $data = [
            'type_name'  => $request->type,
            'code'       => $request->code,
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qrys = PettyCashCode::where('id', $request->id)->update($data);
        return redirect('finance/code_accounting')->with('success', 'Created Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $codes = PettyCashCode::where('id', $id)->first();
        $petty = PettyCashModel::where('code_id', $id)->get();

        if(count($petty)==0)
        {
            $datas=PettyCashCode::find($id);
            $datas->delete();
            return redirect()->back()->with('success', 'Delete Successfully');
        }else{
            return redirect()->back()->with("info","Ups !! Can't delete ".$codes->code." - ".$codes->type_name);
        }
    }
}