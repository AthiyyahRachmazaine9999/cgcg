<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance\TaxModel;
use Illuminate\Support\Facades\Session;
use DB;
use Carbon\Carbon;
use Storage;


class TaxController extends Controller
{
    //
    public function index()
    {
        return view('finance.tax.index',[
            'usr'    => Auth::id(),
        ]);
    }


    public function create()
    {
        return view('finance.tax.create',[
            'action'   => "Finance\TaxController@store",
            'method'   => "post",
        ]);
    }


    public function store(Request $request)
    {
        // dd($request);
        $red = $request->type_tax == "ppn" ? 'tax' : 'tax_pph';
        $data = [
            'date'       => $request->date,
            'year'       => Carbon::parse($request->date)->format('Y'),
            'month'      => Carbon::parse($request->date)->format('m'),
            'no_faktur'  => $request->no_faktur,
            'text'       => $request->text,
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
            'file'       => $request->has('file') ? Storage::disk('public')->put('tax', $request->file('file')) : null,   
            'type_tax'   => $request->type_tax,
        ];
        $qrys = TaxModel::create($data);
        return redirect('finance/'.$red)->with('success','Created Successfully');
    }


    public function ajax_data(Request $request)
     {
        $mine = getUserEmp(Auth::id());
        $columns = array(
             0 => 'id',
             1 => 'date',
             2 => 'no_faktur',
        );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = TaxModel::where('type_tax','ppn')->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = TaxModel::select('*')->where('type_tax','ppn')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = TaxModel::where('no_faktur', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = TaxModel::where('no_faktur', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                // dd($post);
                $data[] = [
                    'year'      => $post->year,
                    'no_faktur' => $post->no_faktur,
                    'created_at'=> Carbon::parse($post->created_at)->format('d F Y'),
                    'id'        => $post->id,
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


    public function edit($type,$id)
    {
        $tax = TaxModel::where('id', $id)->first();
        return view ('finance.tax.edit',[
            'getdata' => $tax,
            'type'    => $type,
            'method'  => 'put',
            'action'  => ['Finance\TaxController@update',$id],
        ]);
    }

    public function update(Request $request)
    {
        $id   = $request->ids;
        $red  = $request->redirect == "ppn" ? 'tax' : 'tax_pph';
        $tax  = TaxModel::where('id', $id)->first();
        $data = [
            'date'       => $request->date,
            'year'       => Carbon::parse($request->date)->format('Y'),
            'month'      => Carbon::parse($request->date)->format('m'),
            'no_faktur'  => $request->no_faktur,
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
            'file'       => $request->has('file') ? Storage::disk('public')->put('tax', $request->file('file')) : $tax->file,   
            'type_tax'   => $request->type_tax,
            'text'       => $request->text,
        ];
        $qrys = TaxModel::where('id', $id)->update($data);
        return redirect('finance/'.$red)->with('success','Updated Succesfully');
    }


    public function show($type,$id)
    {
        $tax = TaxModel::where('id', $id)->first();
        return view('finance.tax.show',[
             'getdata'     => $tax,
             'type'        => $type,
         ]);
    }

     public function destroy($id)
     {
        $qry = TaxModel::find($id);
        $qry->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
     }
    
}