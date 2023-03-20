<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Upload\UploadModel;
use Carbon\Carbon;
use Storage;
use Auth;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Upload.index');
    }

    public function create()
    {
        //
        return view('Upload.create');
    }

    public function ajax_data(Request $request){

         $columns = array(
             0 => 'created_by',
             1 => 'doc_name',
             2 => 'created_at',
             3 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = UploadModel::select('*')->orderBy('id', 'desc')->groupBy('created_by')->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = UploadModel::select('*')->orderBy('id', 'desc')->groupBy('created_by')
                 ->limit($limit, $start)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = UploadModel::where('emp_id', 'like', '%' . $search . '%')
                 ->orderBy('id', 'desc')->limit($limit, $start)->get();
             $totalFiltered = UploadModel::where('emp_id', 'like', '%' . $search . '%')
                 ->orderBy('id', 'desc')->limit($limit, $start)->count();
         }
        //  dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'created_by' => user_name($post->created_by),
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

    public function new_upload_file(Request $request)
    {
        return view('Upload.more_create',[
            'num'      => $request->n_equ+1,
        ]);
    }


    public function upload_file(Request $request)
    {
        $a = $request->doc_name;
        foreach($a as $value => $y)
        {
        $file = $request->file('files')[$value]->getClientOriginalName();
        $data = [
            'doc_name' => $request->doc_name[$value],
            'files'    => Storage::disk('public')->putFileAs('legalitas', $request->file('files')[$value], $file),
            'created_by'=> Auth::id(),
        ];
        $qry = UploadModel::create($data);
    }
    return redirect('upload/file')->with('success','Uploaded Files Successfully');
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
        // dd($id);
        $ups    = UploadModel::where('id', $id)->first();
        $main   = UploadModel::where('created_by', $ups->created_by)->get();
        return view('Upload.show',[
            'main'  => $main,
            'ups'   => $ups,
        ]);
    }



    public function delete_file(Request $request)
    {
        $qry = UploadModel::find($request->id);
        $qry->delete();
    }


    public function edit_file(Request $request)
    {
        // dd($request);
        $data = UploadModel::where('id', $request->id)->first();
        return view('Upload.edit',[
            'data'  => $data,
        ]);
    }
    

    public function saveUpdate(Request $request)
    {
        // dd($request);
        $data = UploadModel::where('id', $request->id)->first();
        $file = $request->file('files')->getClientOriginalName();
        $val = [
                'doc_name'   => $request->doc_name,
                'files'      => Storage::disk('public')->putFileAs('legalitas', $request->file('files'), $file),
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
            ];
        $qry = UploadModel::where('id', $request->id)->update($val);
        return redirect('upload/file/'.$request->id.'/detail')->with('success','Update Files Successfully');
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
    }}