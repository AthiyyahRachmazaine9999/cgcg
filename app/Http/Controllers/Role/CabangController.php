<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role\Role_cabang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $cabang = Role_cabang::all();
        return view('Role/cabang.index', compact('cabang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view ('Role/cabang.create');

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $cabang=Role_cabang::find($id);
        return view ('Role/cabang.edit',compact ('cabang'));
        
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
       
        return $this->save($request, 'update', $id)->with('success', 'Cabang updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $cabang=Role_cabang::find($id);
        $cabang->delete();
        return redirect()->route('cabang.index')
            ->with('success', 'Cabang deleted successfully');
    }

    public function save($request, $save,$id=0)
    {
        $data = [
                  'nama_perusahaan' => $request->input('nama_perusahaan'),
                  'email_cabang'    => $request->input('email_cabang'),
                  'cabang_name'     => $request->input('cabang_name'),
                  'cabang_phone'    => $request->input('cabang_phone'),
                  'cabang_address'  => $request->input('cabang_address'),
                  'is_active'       => $request->input('is_active'),
            $save . '_by'           => Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? Role_cabang::create($data) : Role_cabang::where('id', $id)->update($data);
        if ($qry) {
            return redirect('role/cabang/')->with('success', 'Cabang created successfully');
        }

    }
     // other functional

     public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'cabang_name',
             1 => 'cabang_phone',
             2 => 'cabang_address',
             3 => 'is_active',
             4 => 'created_at',
             5 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Role_cabang::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Role_cabang::select('*')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = Role_cabang::where('cabang_name', 'like', '%' . $search . '%')
                 ->orWhere('cabang_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = Role_cabang::where('cabang_name', 'like', '%' . $search . '%')
                 ->orWhere('cabang_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                     'cabang_name' => $post->cabang_name,
                     'cabang_phone'=> $post->cabang_phone,
                     'cabang_address'  => $post->cabang_address,
                     'is_active'   =>  $post->is_active,
                     'created_at' => $post->created_at->format('Y-m-d'),
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
 
    
 
    
}
