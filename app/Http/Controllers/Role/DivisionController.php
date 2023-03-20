<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role\Role_division;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    
         return view('Role/division.index');
       }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('Role/division.create');
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
    public function edit( $id)
    { 
        
        $division=Role_division::find($id);
        return view ('Role/division.edit',compact ('division'));
    
        
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
        
        return $this->save($request, 'update', $id) ->with('success', 'Division updated successfully');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $division = Role_division::findOrFail($id);
        $division->delete();
      
        return redirect()->route('division.index')
        ->with('success', 'Division deleted successfully');
      
    }

    public function save($request, $save,$id=0)
    {
    
        $data = [
            'div_name'   => $request->input('div_name'),
             $save . '_by'       => Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? Role_division::create($data) : Role_division::where('id', $id)->update($data);
        if ($qry) {
            return redirect('role/division/')->with('success', 'Division Created successfully');;
        }
    }


    public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'div_name',
             1 => 'created_at',
             2 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Role_division::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Role_division::select('*')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = Role_division::where('div_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = Role_division::where('div_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                     'div_name' => $post->div_name,
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
