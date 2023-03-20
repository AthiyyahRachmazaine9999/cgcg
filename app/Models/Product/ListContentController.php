<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ListContent;
use App\Models\Product\ProSpekModel;
use App\Models\Product\ProImageModel;
use App\Models\Product\ProPriceModel;
use App\Models\Product\Product_category;
use App\Models\Product\PendingApproval;
use App\Models\Product\ProductLive;
use App\Models\Sales\QuotationProduct;
use App\Models\Product\ProductReq;
use App\Models\Product\LiveCatModel;
use App\Models\Product\LiveWeightModel;
use App\Models\Product\LiveManModel;
use App\Models\Product\LiveProDescModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AllProductsImport;
use DB;

class ListContentController extends Controller
{
    public function index()
    {

        //untuk tampilan list product
         return view('Product.ListContent.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $cat    = LiveCatModel::all();
        $man    = LiveManModel::all();
        $weight = LiveWeightModel::all();
        $main   = QuotationProduct::where('id_product_request', $request->req_id)->first();
        return view('Product.ListContent.create', [
           'pro_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
           'req_id'    => $main,
           'cat'       => $cat,
           'man'       => $man,
           'weight'    => $weight,
           'method'    => "post",
           'action'    => "Product\ListContentController@store"
            ]);
    }

    public function new_content(Request $request, $id){
        $cat    = LiveCatModel::all();
        $man    = LiveManModel::all();
        $weight = LiveWeightModel::all();
        $main   = QuotationProduct::where('id', $id)->first();
        $pro_req= ProductReq::where('id', $main->id_product_request)->first();
        return view('Product.ListContent.form', [
           'pro_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
           'req_id'    => $main,
           'name_req'  => $pro_req,
           'cat'       => $cat,
           'man'       => $man,
           'weight'    => $weight,
           'method'    => "post",
           'action'    => "Product\ListContentController@store"
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
        $spek  = ProSpekModel::where('pro_spek_id', $id)->first();
        $list  = ListContent::where('pro_id',$id)->first();
        $image = ProImageModel::where('img_id', $id)->first();
        $price = ProPriceModel::where('price_id',$id)->first();
        $cat   = Product_category::where('id',$id)->first();
        // dd($price,$image);
        return view ('Product.ListContent.show', [
            'spek' => $spek,
            'list' => $list,
            'image'=> $image,
            'price'=> $price,
            'we'   => Weight($list->weight_class_id),
            'cat'  => Category($list->pro_categories),
            'man'  => Manufacture($list->pro_manufacture),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    { 
        $spek  = ProSpekModel::where('pro_spek_id', $id)->first();
        $list  = ListContent::where('pro_id',$id)->first();
        $image = ProImageModel::where('img_id', $id)->first();
        $price = ProPriceModel::where('price_id',$id)->first();
        return view('Product.ListContent.edit', [
            'spek'           => $spek,
            'list'           => $list,
            'img'            => $image,
            'price'          => $price,
            'pro_categories' => $this->AllCat(),
            'pro_manufacture'=> $this->AllMan(),
            'pro_weight'     => $this->Weight(),
            'method'         => "put",
            'action'         => ['Product\ListContentController@update',$id],
        ]);     

    }  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request,$id)
    {      
        //dd($request);
        return $this->save($request, 'update', $id)->with('success', 'Data updated successfully');
    }

    public function destroy($id)
    {   
        $query='DELETE product, product_spesifikasi,product_price, product_image
        FROM product JOIN product_spesifikasi ON product_spesifikasi.pro_spek_id = product.pro_id
        JOIN product_price ON product_price.price_id = product.pro_id
        JOIN product_image on product_image.img_id = product.pro_id WHERE product.pro_id = ?';
        DB:: delete($query, array($id));

        return redirect()->route('listcontent.index')
        ->with('success', 'Data deleted successfully');
      
    }


    public function save($request, $save, $id=0)
    {   
        // dd($request);
       $image = $request->pro_image;
       $imgup=$request->file('pro_image');
       $folder = 'public/catalog';
       $path = Storage::putFile($folder,$imgup); 
       $status = $request->input('pro_status') == '' ? 'Pending' : ListContent::where('pro_id', $request->input('pro_status'))->first();
       $active = $request->input('pro_active') == '' ? 'Active' : ListContent::where('pro_id', $request->input('pro_active'))->first();
       $data   = [
                        'pro_request_id'     => $request->pro_request_id,
                        'pro_vn'             => $request->input('pro_vn'),
                        'pro_image'          => $path,
                        'pro_name'           => $request->input('pro_name'),
                        'pro_manufacture'    => $request->input('pro_manufacture'),
                        'pro_stock'          => $request->input('pro_stock'),
                        'pro_categories'     => $request->input('pro_categories'),
                        'pro_active'         => $active,
                        'pro_dimesion'       => $request->input('pro_dimension'),
                        'pro_status'         => $status,
                        'pro_weight'         => $request->input('pro_weight'),
                        'weight_class_id'    => $request->input('weight_class_id'),
                        'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                        'pro_spec'           => $request->input('pro_spec'),
                  $save . '_by'              => Auth::id()
        ]; 
        $qry = $save  == 'created' ? ListContent::create($data) : ListContent::where('pro_id', $id)->update($data);
        if($qry){
            $spek = [
                       'pro_spek_name' => $request->input('pro_spec'),
                 $save . '_by'         => Auth::id()
        ];
        $qry1 = $save == 'created' ? ProSpekModel::create($spek) : ProSpekModel::where('pro_spek_id', $id)->update($spek);
        }   
        if($qry1){
            $image  = $request->pro_image;
            $imgup  = $request->file('pro_image');
            $folder = 'public/catalog';
            $path   = Storage::putFile($folder,$imgup);
            $image  = [
                        'img_image' => $path,
                  $save . '_by'     => Auth::id()
        ];
        $qry2 = $save == 'created' ? ProImageModel::create($image) : ProImageModel::where('img_id', $id)->update($image);
        }
         if($qry2){
           $price = [
                        'price_modal'  => $request->input('price_modal'),
                        'price_retail' => $request->input ('price_retail'),
                        'price_gov'    => $request->input ('price_gov'),
                  $save . '_by'        => Auth::id(),
           ];
        $qry3 = $save == 'created' ? ProPriceModel::create($price) : ProPriceModel::where('price_id', $id)->update($price);
        return redirect('product/content/listcontent');
        }
    }  

    public function ajax_data(Request $request)
     {
         $columns = array(

             0 => 'pro_vn',
             1 => 'pro_lokal',
             2 => 'pro_name',
             3 => 'pro_categories',
             4 => 'pro_stock',
             5 => 'pro_spec',
             6 => 'pro_status',
             7 => 'pro_id',
            
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = ListContent::where('pro_status','Pending')->orWhere('pro_status','Waiting')->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = ListContent::where('pro_status','Pending')->orWhere('pro_status','Waiting')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = ListContent::where('pro_vn', 'like', '%' . $search . '%')
                 ->orWhere('pro_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = ListContent::where('pro_sku', 'like', '%' . $search . '%')
                 ->orWhere('pro_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                    'pro_vn'             => $post->pro_vn,
                    'pro_lokal'          => $post->pro_lokal,
                    'pro_name'           => $post->pro_name,
                    'pro_stock'          => $post->pro_stock,
                    'pro_berlaku_sampai' => $post->pro_berlaku_sampai,
                    'pro_categories'     => $post->pro_categories,
                    'pro_status'         => $post->pro_status,
                    'pro_id'             => $post->pro_id,
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

    function price ($angka) {
        $hasil = 'Rp ' . number_format($angka, 3, ",", ".");
        return $hasil;
    }

    public function AllCat()
    {

        $data = LiveCatModel::all();
        $arr  = array();
        foreach ($data as $reg) {
        $arr[$reg->category_id] = $reg->name;
        }
        return $arr;
    }

    public function AllMan()
    {

        $data = LiveManModel::all();
        $arr  = array();
        foreach ($data as $reg) {
        $arr[$reg->manufacturer_id] = $reg->name;
        }
        return $arr;
    }

    public function Weight()
    {

        $data = LiveWeightModel::all();
        $arr  = array();
        foreach ($data as $reg) {
        $arr[$reg->weight_class_id] = $reg->title;
        }
        return $arr;
    }

    public function import(Request $request){
        Excel::import (new AllProductsImport, $request->file('file'));
        return redirect()->route('listcontent.index')
        ->with('success', 'Product Content import successfully');
    }

    public function apply(Request $request, $id){
        $lead = ListContent::find($id); 
        $price= ProPriceModel::find($id);
        // $quo_req= QuotationProduct::find($id);
        $type = $request->input('pro_type') == '' ? 'New' : ListContent::where('pro_id', $request->input('pro_type'))->first();
        $apply =[
            'pro_spec'           => $lead->pro_spec,
            'pro_vn'             => $lead->pro_vn,
            'pro_name'           => $lead->pro_name,
            'pro_image'          => $lead->pro_image,
            'pro_status'         => $lead->pro_status,
            'pro_dimesion'       => $lead->pro_dimesion,
            'pro_berlaku_sampai' => $lead->pro_berlaku_sampai,
            'pro_active'         => $lead->pro_active,
            'pro_weight'         => $lead->pro_weight,
            'pro_type'           => $type,
            'weight_class_id'    => $lead->weight_class_id,
            'pro_categories'     => $lead->pro_categories,
            'pro_manufacture'    => $lead->pro_manufacture,
            'pro_stock'          => $lead->pro_stock,
            'pro_price'          => $price->price_retail,
            'pro_request_id'     => $lead->pro_request_id,
        ];
        // dd($apply);
        $qry= PendingApproval::create($apply);
        if($qry){
            $ap=ListContent::where('pro_id', $id)->first();
            $data=[
                'pro_status' => 'Waiting',
            ];
            $qry2=ListContent::where('pro_id', $id)->update($data);
        }

        return redirect('product/content/listcontent/')->with('success', 'Send Data successfully');
       
    }

}
