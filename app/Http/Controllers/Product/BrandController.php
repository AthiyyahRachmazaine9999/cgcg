<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product_brand;
use App\Models\Product\LiveManModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
   
         return view('Product/brand.index');
       }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('Product/brand.create');
    
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
        
        $brand=Product_brand::find($id);
        return view ('Product/brand.edit',compact ('brand'));
    
        
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
       
        return $this->save($request, 'update', $id)->with('success', 'Brand Updated successfully');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Product_brand::select('*')->join('ocbz_manufacturer','ocbz_manufacturer.manufacturer_id','=','product_brand.id_live_brand')->first();
        $brand->delete();
      
        return redirect()->route('brand.index')
        ->with('success', 'Brand deleted successfully');
      
    }

    public function save($request, $save,$id=0)
    {
        $livebrand = [
                'name' => $request->input('brand_name'),
                'image'=> "",
                'sort_order' => 0,
            ];
            // dd($livebrand);
        $qry = $save == 'created' ? LiveManModel::create($livebrand) : LiveManModel::where('manufacturer_id', $request->id_live_brand)->update($livebrand);
        if ($qry) {
            if($save=="created"){
                $id= $qry->manufacturer_id;
            }
            else{
                $id= $request->id_live_brand;
            }
        $data = [
                  'brand_name'    => $request->input('brand_name'),
                  'id_live_brand' => $id,
            $save . '_by'         => Auth::id()
        ];
        $qry1 = $save == 'created' ? Product_brand::create($data) : Product_brand::where('id_live_brand', $id)->update($data);
            return redirect('product/content/brand/')->with('success', 'Brand Created successfully');
        }
    }


    public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'brand_name',
             1 => 'created_at',
             2 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Product_brand::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Product_brand::select('*')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = Product_brand::where('brand_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = Product_brand::where('brand_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                     'brand_name' => $post->brand_name,
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
