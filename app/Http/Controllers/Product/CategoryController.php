<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product_category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
       // $allcat = Product_category::where('parent_id', '=', 0)->get();
      return view ('Product.category.index');
       }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $child = Product_category::whereNull('child_id')->first();
        return view('Product.category.create', [
            'name'   => $this->check_parent(),
            'child'    => $this->check_child(),
            'method' => "post",
            'action' => 'Product\CategoryController@store',
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
    public function edit( Product_category $category)
    { 
        $data = Product_category::where('id',$category->id)->first();
        // dd($data);
        return view('Product.category.edit', [
            'getdata'=> $data,
            'name'   => $this->check_parent(),
            'child'    => $this->check_child(),
            'method' => "put",
            'action' => ['Product\CategoryController@update',$category->id],
        ]);
        //return view ('Product/category.edit',compact ('category'));
   
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
        
        return $this->save($request, 'update', $id)->with('success', 'Category Updated successfully');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $category = Product_category::findOrFail($id);
        $category->delete();
      
        return redirect()->route('category.index')
        ->with('success', 'Category deleted successfully');
      
    }

    public function save($request, $save,$id=0)
    {

        $data = [
            'cat_name'   => $request->input('cat_name'),
            'child_id'   => $request->input('child_id'),
            'parent_id'   => $request->input('parent_id'),
             $save . '_by'=> Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? Product_category::create($data) : Product_category::where('id', $id)->update($data);
        if ($qry) {
            return redirect('product/content/category')->with('success', 'Category Created successfully');
        }
    }


    public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'cat_name',
             1 => 'parent_id',
            //  2 => 'child_id',
             2 => 'created_at',
             3 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Product_category::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Product_category::select('*')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = Product_category::where('cat_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = Product_category::where('cat_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'cat_name'   => $post->cat_name,
                    'parent_id'  => $this->show_parent($post->parent_id,$post->id),
                   // 'child_id'   => $this->subcat($post->child_id,$post->id),
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

     public function show_parent($parent_id,$id)
     {
            $cp = Product_category::where('id', $parent_id)->first();
            $cc = Product_category::where('child_id', '=', null)->first();
            $dd = Product_category::where('id',$id)->first();
            $gt = is_null($parent_id)  ? '' : $cp->cat_name.'/'.$cc->cat_name.'/'.$dd->cat_name;
            return $gt;
     }
     
     public function check_parent()
     {  
        $data = Product_category::all();
        $arr = array();
        foreach ($data as $reg) {
            $cm = Product_category::where('parent_id', '!=', null)->first();
            //dd($cm);
            $gt = is_null($reg->parent_id)  ? $reg->cat_name : $reg->cat_name;
            $arr[$reg->id] = $gt;
        }
        return $arr;
        
     }

     public function check_child()
     {  
        $data = Product_category::all();
        $arr = array();
        foreach ($data as $reg) {
            $cm = Product_category::where('child_id', '!=', null)->first();
            //dd($cm);
            $gt = is_null($reg->child_id)  ? $reg->cat_name : $reg->cat_name;
            $arr[$reg->id] = $gt;
        }
        return $arr;
        
     }


     //  public function showAll($parent_id, $child_id)
    //  {
    //     $data = Product_category::all();
    //     $arr = array();
    //     foreach ($data as $req){
            
    //         $cp = Product_category::where('id', $parent_id)->first();
    //         $cc = Product_category::where('id', $child_id)->first();
    //         //$dd = Product_category::where('id',$id)->first();
    //         if(is_null($req->parent_id | $req->child_id)){
    //             $gt = $cp->cat_name.'/'. $cc->cat_name.'/'. $req->cat_name;
                      
    //     } else {
    //         $gt= is_null($child_id) ? '' : $cp->cat_name.'/'.$cc->cat_name.'/'.$req->cat_name;
            
    //     } 
    // } return $gt;
     
    // }
   


}


