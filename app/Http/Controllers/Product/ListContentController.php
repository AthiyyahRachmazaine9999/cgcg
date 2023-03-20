<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ListContent;
use App\Models\Product\ProSpekModel;
use App\Models\Product\ProImageModel;
use App\Models\Product\ProPriceModel;
use App\Models\Product\PendingApproval;
use App\Models\Product\ProductLive;
use App\Models\Product\LiveProToCat;
use App\Models\Product\LiveProImage;
use App\Models\Product\LiveProToStore;
use App\Models\Product\LiveProToLayout;
use App\Models\Product\LiveProAtribut;
use App\Models\Product\LiveProDescModel;
use App\Models\Product\Product_brand;
use App\Models\Product\LiveManModel;
use App\Models\Activity\ActQuoModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use App\Models\Product\Product_category;
use App\Models\Sales\QuotationModel;
use App\Models\Product\ProductReq;
use App\Models\Product\LiveCatModel;
use App\Models\Product\LiveWeightModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AllProductsImport;
use DB;
use Carbon\Carbon;

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
        $id     = PendingApproval::select('pro_id')->orderBy('pro_id', 'DESC')->first();
        $cat    = LiveCatModel::all();
        $man    = LiveManModel::all();
        $brand  = Product_brand::all();
        $weight = LiveWeightModel::where('language_id', '2')->get();
        $token  = Carbon::now('GMT+7')->format('Yis');
        $type   = "New Content";
        return view('Product.ListContent.create', [
            'pro_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'id'        => $id == null ? null + 1 : $id->pro_id + 1,
            'type'      => $type,
            'cat'       => $cat,
            'token_con' => $token,
            'man'       => $man,
            'weight'    => $weight,
            'method'    => "post",
            'action'    => "Product\ListContentController@store"
        ]);
    }






    public function new_content(Request $request, $id)
    {
        // dd($request, $id);
        $cat     = LiveCatModel::all();
        $man    = LiveManModel::all();
        $brand  = Product_brand::all();
        $weight1 = LiveWeightModel::all();
        $weight  = LiveWeightModel::where('language_id', '2')->get();
        $main    = QuotationProduct::where('id', $id)->first();
        $id      = PendingApproval::select('pro_id')->orderBy('pro_id', 'DESC')->first();
        $Mquo    = QuotationModel::where('id', $main->id_quo)->first();
        $pro_req = ProductReq::where('id', $main->id_product_request)->first();
        $token   = Carbon::now('GMT+7')->format('Yis');
        // $typequo    = $Mquo->quo_type;
        $type  = "Request New SO";
        return view('Product.ListContent.form', [
            'pro_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'req_id'    => $main,
            'id'        => $id == null ? null + 1 : $id->pro_id + 1,
            'typequo'   => $Mquo->quo_type,
            'type'      => $type,
            'quo'       => $main->id_quo,
            'ProReq'    => $main->id_product_request,
            'req'       => $pro_req,
            'token_con' => $token,
            'cat'       => $cat,
            'man'       => $man,
            'weight'    => $weight,
            'method'    => "post",
            'action'    => "Product\ListContentController@store"
        ]);
    }


    public function getProduct()
    {

        $data = LiveManModel::all();
        $arr1 = array();
        foreach ($data as $reg) {
            $arr[$reg->manufacturer_id] = $reg->name;
        }

        $data2 = Product_brand::all();
        $arr2 = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->brand_name;
        }
        return array_combine($arr1, $arr2);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        // dd($request->type);
        if ($request->type == "New Content") {
            return $this->saveContent($request, 'created')->with('Save Content Successfully');
        } else {
            return $this->saveSO($request, 'created');
            // dd($request);
        }
    }

    ///////////////////////Save Part//////////////////////////////////////
    public function saveContent($request, $save, $id = 0)
    {
        $arr_dm = explode('x', $request->pro_dimension);
        $sku    = "SKU" . $request->pro_categories . $request->pro_manufacture . sprintf("%06d", $request->pro_id);
        // dd($sku);
        //IMAGE
        if ($request->has('pro_image')) {
            $new = $request->file('pro_image');
            $newName = time() . '-' . $new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image', $newName);
        } else {
            $newName = null;
        }

        //PRICE
        if ($request->pro_priceType == "Harga Normal") {
            $price = $request->input('price_retail');
        } else {
            $price = $request->input('catalog_price');
        }

        // $act1   = $qry->pro_active;
        // $arr_img= explode('/' , $data->pro_image);
        $data = [
            'model'           => Manufacture($request->pro_manufacture),
            'sku'             => $sku,
            'manufacturer_id' => $request->input('pro_manufacture'),
            'price'           => round($price),
            'image'           => $newName == null ? null : "catalog/PRIORITAS/" . $newName,
            'weight'          => $request->input('pro_weight'),
            'weight_class_id' => $request->input('weight_class_id'),
            'date_available'  => $request->pro_berlaku_sampai == null ? null : $request->input('pro_berlaku_sampai'),
            'status'          => 1,
            'length'          => $arr_dm[0],
            'width'           => $arr_dm[1],
            'height'          => $arr_dm[2],
            'tax_class_id'    => 11,
            'stock_status_id' => 7,
            'length_class_id' => LengthUnit($request->length_unit)->length_class_id,
            'date_added'      => Carbon::now('GMT+7')->toDateTimeString(),
            'date_modified'   => Carbon::now('GMT+7')->toDateTimeString(),
            'unspsc'          => "",
            'upc'             => "",
            'ean'             => "",
            'jan'             => "",
            'isbn'            => "",
            'mpn'             => "",
            'location'        => "",
            'quantity'        => 0,
            'points'          => 0,
            'subtract'        => 1,
            'minimum'         => 1,
            'sort_order'      => 0,
            'shipping'        => 1,
            'viewed'          => 0,
        ];
        // dd($data);
        $qry = ProductLive::create($data);
        if ($qry) {
            $data   = [
                'pro_sku'            => $qry->sku,
                'pro_vn'             => $request->input('pro_vn'),
                'pro_image'          => $newName,
                'pro_name'           => $request->input('pro_name'),
                'pro_manufacture'    => $request->input('pro_manufacture'),
                'pro_categories'     => $request->input('pro_categories'),
                'pro_active'         => 'Active',
                'pro_dimesion'       => $request->input('pro_dimension'),
                'pro_status'         => 'Approved',
                'pro_weight'         => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_spec'           => $request->input('pro_spec'),
                'pro_desc'           => $request->input('pro_desc'),
                'length_unit'        => $request->input('length_unit'),
                'pro_created_date'   => Carbon::now('GMT+7')->toDateTimeString(),
                'token'              => $request->input('token'),
                $save . '_by'        => Auth::id()
            ];
            $qry1 = ListContent::create($data);
        }
        if ($qry1) {
            $spek = [
                'pro_spek_id'          => $qry1->pro_id,
                'pro_spek_name'        => $request->input('pro_spec'),
                'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
                $save . '_by'                => Auth::id()
            ];
            $imagedat  = [
                'img_id'    => $qry1->pro_id,
                'pro_name'  => $request->input('pro_name'),
                'img_image' => $newName,
                $save . '_by'     => Auth::id()
            ];
            $qry2 = ProSpekModel::create($spek);
            $qry3 = $save  == 'created' ? ProImageModel::create($imagedat) : ProImageModel::where('img_id', $id)->update($imagedat);
        }
        if ($qry3) {
            $price1 = [
                'price_id'     => $qry1->pro_id,
                'price_modal'  => $request->input('price_modal'),
                'price_retail' => $request->input('price_retail'),
                'catalog_price' => $request->input('catalog_price'),
                'type_harga'   => $request->input('pro_priceType'),
                $save . '_by'        => Auth::id(),
            ];
            $qry4 = ProPriceModel::create($price1);
        }
        if ($qry4) {
            $apply = [
                'id_prolist'         => $qry1->pro_id,
                'pro_spec'           => $request->input('pro_spec'),
                'pro_vn'             => $request->input('pro_vn'),
                'pro_name'           => $request->input('pro_name'),
                'pro_sku'            => $qry->sku,
                'pro_image'          => $newName,
                'pro_status'         => 'Approved',
                'pro_dimesion'       => $request->input('pro_dimension'),
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_active'         => 'Active',
                'pro_weight'         => $request->input('pro_weight'),
                'pro_type'           => $request->input('type'),
                'up_type'            => "Create New Content",
                'weight_class_id'    => $request->input('weight_class_id'),
                'pro_categories'     => $request->input('pro_categories'),
                'pro_manufacture'    => $request->input('pro_manufacture'),
                'pro_price'          => round($price),
                'length_unit'        => $request->input('length_unit'),
                'pro_desc'           => $request->input('pro_desc'),
                $save . '_by'            => Auth::id(),
            ];
            
            $qry5 = PendingApproval::create($apply);
        }
        if ($qry5) {
            $que = $qry->product_id;
            $data = [
                'product_id'             => $que,
                'name'                   => $request->input('pro_name'),
                'overview'               => $request->input('pro_desc'),
                'description'            => "",
                'language_id'            => 2,
                'fimage'                 => "",
                'video1'                 => "",
                'html_product_shortdesc' => "",
                'html_product_right'     => "",
                'html_product_tab'       => "",
                'tab_title'              => "",
                'meta_title'             => $request->input('pro_name'),
                'meta_description'       => "",
                'meta_keyword'           => "",
                "tag"                    => LiveCat($request->pro_categories)->name,
            ];
            $cat = [
                'product_id' => $que,
                'category_id' => $request->input('pro_categories'),
            ];
            $liveImg = [
                'product_id' => $que,
                'image'      => "",
                'sort_order' => 0,

            ];
            $liveStore = [
                'product_id' => $que,
                'store_id'   => 0,
            ];
            $liveLayout = [
                'product_id' => $que,
                'store_id'   => 0,
                'layout_id'  => 0,
            ];
            $liveAtr = [
                'product_id'   => $que,
                'attribute_id' => 347,
                'language_id'  => 2,
                'text'         => $request->input('pro_spec'),
            ];
            $historyprice = [
                'id_pro'         => $qry1->pro_id,
                'id_pro_live'    => $que,
                'harga'          => round($price),
                'status'         => "Approved New Content",
                'sku'            => $sku,
                'created_by'     => Auth::id(),
            ];
            $historystatus = [
                'id_pro'         => $qry1->pro_id,
                'id_pro_live'    => $que,
                'sku'            => $sku,
                'status_App'     => "Approved New Content",
                'status'         => $qry->pro_active == 'Active' ? 'Active' : 'In Active',
                'created_by'     => Auth::id(),
            ];
            $qry7      = LiveProDescModel::create($data);
            $tblImage  = LiveProImage::create($liveImg);
            $tblStore  = LiveProToStore::create($liveStore);
            $tblLayout = LiveProToLayout::create($liveLayout);
            $tblAtr    = LiveProAtribut::create($liveAtr);
            $qry8      = LiveProToCat::create($cat);
            $qry9      = ProHargaHist::create($historyprice);
            $qry10     = ProStatusHist::create($historystatus);
        }
        if ($qry10) {
            return redirect('product/content/listcontent')
                ->with('success', 'Saved Successfully');
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    public function saveSO($request, $save, $id = 0)
    {
        // dd($request);
        $arr_dm = explode('x', $request->pro_dimension);
        $sku    = "SKU" . $request->pro_categories . $request->pro_manufacture . sprintf("%06d", $request->pro_id);
        //IMAGE
        if ($request->has('pro_image')) {
            $new = $request->file('pro_image');
            $newName = time() . '-' . $new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image', $newName);
        } else {
            $newName = null;
        }

        //PRICE
        if ($request->pro_priceType == "Harga Normal") {
            $price = $request->input('price_retail');
        } else {
            $price = $request->input('catalog_price');
        }
        // $act1    = $qry->pro_active;
        $data = [
            'model'           => Manufacture($request->pro_manufacture),
            'sku'             => $sku,
            'manufacturer_id' => $request->input('pro_manufacture'),
            'price'           => round($price),
            'image'           => $newName == null ? null : "catalog/PRIORITAS/" . $newName,
            'weight'          => $request->input('pro_weight'),
            'weight_class_id' => $request->input('weight_class_id'),
            'date_available'  => $request->pro_berlaku_sampai == null ? null : $request->input('pro_berlaku_sampai'),
            'status'          => 1,
            'length'          => $arr_dm[0],
            'width'           => $arr_dm[1],
            'height'          => $arr_dm[2],
            'tax_class_id'    => 11,
            'stock_status_id' => 7,
            'length_class_id' => LengthUnit($request->length_unit)->length_class_id,
            'date_added'      => Carbon::now('GMT+7')->toDateTimeString(),
            'date_modified'   => Carbon::now('GMT+7')->toDateTimeString(),
            'unspsc'          => "",
            'upc'             => "",
            'ean'             => "",
            'jan'             => "",
            'isbn'            => "",
            'mpn'             => "",
            'location'        => "",
            'quantity'        => 0,
            'points'          => 0,
            'subtract'        => 1,
            'minimum'         => 1,
            'sort_order'      => 0,
            'shipping'        => 1,
            'viewed'          => 0,
        ];
        // dd($data);
        $qry = ProductLive::create($data);
        if ($qry) {
            $data   = [
                'id_quo'             => $request->id_quo,
                'pro_request_id'     => $request->pro_request_id,
                'pro_sku'            => $qry->sku,
                'pro_vn'             => $request->input('pro_vn'),
                'pro_image'          => $newName,
                'pro_name'           => $request->input('pro_name'),
                'pro_manufacture'    => $request->input('pro_manufacture'),
                'pro_categories'     => $request->input('pro_categories'),
                'pro_active'         => 'Active',
                'pro_dimesion'       => $request->input('pro_dimension'),
                'pro_status'         => 'Approved',
                'pro_weight'         => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_spec'           => $request->input('pro_spec'),
                'pro_desc'           => $request->input('pro_desc'),
                'length_unit'        => $request->input('length_unit'),
                'pro_created_date'   => Carbon::now('GMT+7')->toDateTimeString(),
                'token'              => $request->input('token'),
                $save . '_by'        => Auth::id()
            ];
            // dd($data);
            $qry1 = ListContent::create($data);
        }
        if ($qry1) {
            $spek = [
                'pro_spek_id'          => $qry1->pro_id,
                'pro_spek_name'        => $request->input('pro_spec'),
                'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
                $save . '_by'          => Auth::id()
            ];
            // dd($spek);
            $imagedat  = [
                'img_id'    => $qry1->pro_id,
                'pro_name'  => $request->input('pro_name'),
                'img_image' => $newName,
                $save . '_by'     => Auth::id()
            ];
            // dd($imagedat);
            $qry2 = ProSpekModel::create($spek);
            $qry3 = $save  == 'created' ? ProImageModel::create($imagedat) : ProImageModel::where('img_id', $id)->update($imagedat);
        }
        if ($qry3) {
            $price1 = [
                'price_id'     => $qry1->pro_id,
                'price_modal'  => $request->input('price_modal'),
                'price_retail' => $request->input('price_retail'),
                'catalog_price'=> $request->input('catalog_price'),
                'type_harga'   => $request->input('pro_priceType'),
                $save . '_by'        => Auth::id(),
            ];
            // dd($price1);
            $qry3 = ProPriceModel::create($price1);
        }
        if ($qry3) {
            $apply = [
                'id_quo'             => $request->id_quo,
                'pro_request_id'     => $request->pro_request_id,
                'id_prolist'         => $qry1->pro_id,
                'pro_spec'           => $request->input('pro_spec'),
                'pro_vn'             => $request->input('pro_vn'),
                'pro_name'           => $request->input('pro_name'),
                'pro_sku'            => $qry->sku,
                'pro_image'          => $newName,
                'pro_status'         => 'Approved',
                'pro_dimesion'       => $request->input('pro_dimension'),
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_active'         => 'Active',
                'pro_weight'         => $request->input('pro_weight'),
                'pro_type'           => $request->input('type'),
                'up_type'            => "Create Request SKU From SO",
                'weight_class_id'    => $request->input('weight_class_id'),
                'pro_categories'     => $request->input('pro_categories'),
                'pro_manufacture'    => $request->input('pro_manufacture'),
                'pro_price'          => round($price),
                'length_unit'        => $request->input('length_unit'),
                'pro_desc'           => $request->input('pro_desc'),
                $save . '_by'            => Auth::id(),
            ];
            // dd($apply);
            $qry4 = PendingApproval::create($apply);
        }
        if ($qry4) {
            $que = $qry->product_id;
            $data = [
                'product_id'             => $que,
                'name'                   => $request->input('pro_name'),
                'overview'               => $request->input('pro_desc'),
                'description'            => "",
                'language_id'            => 2,
                'fimage'                 => "",
                'video1'                 => "",
                'html_product_shortdesc' => "",
                'html_product_right'     => "",
                'html_product_tab'       => "",
                'tab_title'              => "",
                'meta_title'             => $request->input('pro_name'),
                'meta_description'       => "",
                'meta_keyword'           => "",
                "tag"                    => LiveCat($request->pro_categories)->name,
            ];
            $cat = [
                'product_id' => $que,
                'category_id' => $request->input('pro_categories'),
            ];
            $liveImg = [
                'product_id' => $que,
                'image'      => "",
                'sort_order' => 0,

            ];
            $liveStore = [
                'product_id' => $que,
                'store_id'   => 0,
            ];
            $liveLayout = [
                'product_id' => $que,
                'store_id'   => 0,
                'layout_id'  => 0,
            ];
            $liveAtr = [
                'product_id'   => $que,
                'attribute_id' => 347,
                'language_id'  => 2,
                'text'         => $request->input('pro_spec'),
            ];
            $quo_req = QuotationProduct::where('id_product_request', $request->pro_request_id)->first();
            // dd($quo_req);
            $data2 = [
                'id_product'         => $qry->sku,
                'det_quo_harga_live' => $qry->price,
                'det_quo_harga_order' => $quo_req->det_quo_harga_req,
            ];
            $historyprice = [
                'id_pro'         => $qry1->pro_id,
                'id_pro_live'    => $que,
                'harga'          => round($price),
                'id_pro_live'    => $que,
                'id_quo'         => $request->id_quo,
                'pro_request_id' => $request->pro_request_id,
                'status'         => "Approved Request From SO",
                'sku'            => $sku,
                'created_by'     => Auth::id(),
            ];
            $historystatus = [
                'id_pro'         => $qry1->pro_id,
                'id_pro_live'    => $que,
                'pro_request_id' => $request->pro_request_id,
                'id_quo'         => $request->id_quo,
                'sku'            => $sku,
                'status_App'     => "Approved Request From SO",
                'status'         => $qry->pro_active == 'Active' ? 'Active' : 'In Active',
                'created_by'     => Auth::id(),
            ];
            // dd($historystatus,$historyprice,$data2,$liveAtr,$liveLayout,$liveStore,$liveImg,$cat,$data);
            $qry7      = LiveProDescModel::create($data);
            $tblImage  = LiveProImage::create($liveImg);
            $tblStore  = LiveProToStore::create($liveStore);
            $tblLayout = LiveProToLayout::create($liveLayout);
            $tblAtr    = LiveProAtribut::create($liveAtr);
            $quo1      = QuotationProduct::where('id_product_request', $request->pro_request_id)->update($data2);
            $qry8      = LiveProToCat::create($cat);
            $qry9      = ProHargaHist::create($historyprice);
            $qry10     = ProStatusHist::create($historystatus);
        }
        if ($qry10) {
            $data = [
                'activity_id_quo'       => $request->id_quo,
                'activity_name'         => "Produk " . $request->pro_name . " Telah di Tambahkan & Telah Tayang dengan SKU : " . $sku,
                'activity_id_user'      => Auth::id(),
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $qryLast = ActQuoModel::insert($data);
        }
        // dd($qryLast);
        return redirect('product/content/listcontent')->with('success', 'Saved Successfully');
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
        $list  = ListContent::where('pro_id', $id)->first();
        $image = ProImageModel::where('img_id', $id)->first();
        $price = ProPriceModel::where('price_id', $id)->first();
        $cat   = Product_category::where('id', $id)->first();
        $desc   = LiveProDescModel::where('product_id', $id)->first();
        $histhrg = ProHargaHist::where('id_pro', $id)->first();
        $arr_dm = explode('x', $list->pro_dimesion);

        return view('Product.ListContent.show', [
            'harga' => ProHargaHist($list->pro_sku),
            'arr' => $arr_dm,
            'spek' => $spek,
            'list' => $list,
            'image' => $image,
            'price' => $price,
            'desc' => $desc,
            'we'   => Weight($list->weight_class_id),
            'length' => LengthUnit($list->length_unit)->title,
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
        $list  = ListContent::where('pro_id', $id)->first();
        $image = ProImageModel::where('img_id', $id)->first();
        $price = ProPriceModel::where('price_id', $id)->first();
        $type = $price->catalog_price;
        return view('Product.ListContent.edit', [
            'type'           => $type,
            // 'id_quo'         => $id_quo,
            'spek'           => $spek,
            'list'           => $list,
            'img'            => $image,
            'price'          => $price,
            'pro_categories' => $this->AllCat(),
            'pro_manufacture' => $this->AllMan(),
            'pro_weight'     => $this->Weight(),
            'method'         => "put",
            'action'         => ['Product\ListContentController@update', $id],
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
        // dd($request);
        return $this->saveUpdateList($request, 'update', $id)->with('success', 'Data updated successfully');
    }

    public function saveUpdateList($request, $save, $id)
    {

        $list = ListContent::where('pro_id', $request->pro_id)->first();
        if ($request->has('pro_image')) {
            $new = $request->file('pro_image');
            $newName = time() . '-' . $new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image', $newName);
        } else {
            $newName = $list->pro_image;
        }
        // dd($request);
        $status = $request->input('pro_status') == '' ? 'Pending' : ListContent::where('pro_id', $request->input('pro_status'))->first();
        $active = $request->input('pro_active') == '' ? 'Active' : ListContent::where('pro_id', $request->input('pro_active'))->first();
        $data   = [
            'pro_request_id'     => $list->pro_request_id,
            'id_quo'             => $list->id_quo,
            'pro_vn'             => $request->input('pro_vn'),
            'pro_image'          => $newName,
            'pro_name'           => $request->input('pro_name'),
            'pro_manufacture'    => $request->input('pro_manufacture'),
            'pro_categories'     => $request->input('pro_categories'),
            'pro_active'         => $active,
            'pro_dimesion'       => $request->input('pro_dimension'),
            'pro_status'         => $status,
            'pro_weight'         => $request->input('pro_weight'),
            'weight_class_id'    => $request->input('weight_class_id'),
            'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
            'pro_spec'           => $request->input('pro_spec'),
            'pro_desc'           => $request->input('pro_desc'),
            'length_unit'        => $request->input('length_unit'),
            'pro_created_date'   => Carbon::now('GMT+7')->toDateTimeString(),
            $save . '_by'              => Auth::id()
        ];
        $imagedat  = [
            'img_id'    => $request->pro_id,
            'pro_name'  => $request->input('pro_name'),
            'img_image' => $newName,
            $save . '_by'     => Auth::id(),
        ];
        $price2 = [
            'price_modal'  => $request->input('price_modal'),
            'price_retail' => $request->input('price_retail'),
            'catalog_price' => $request->input('catalog_price'),
            'price_gov'    => $request->input('price_gov'),
            $save . '_by'        => Auth::id(),
        ];
        $qry3 = ProPriceModel::where('price_id', $id)->update($price2);
        $qry2 = ProImageModel::where('img_id', $id)->update($imagedat);
        $qry  = ListContent::where('pro_id', $id)->update($data);
        if ($qry) {
            $spek = [
                'pro_spek_id'          => $request->pro_id,
                'pro_spek_name'        => $request->input('pro_spec'),
                'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
                $save . '_by'                => Auth::id()
            ];
            $qry1 = ProSpekModel::where('pro_spek_id', $id)->update($spek);
        }
        if ($request->id_quo != null) {
            if ($qry1) {
                $id_quo = ActQuoModel::where('activity_id', $request->id_quo)->first();
                $qry = $request->pro_name;
                $data   = [
                    'activity_id_quo'       => $request->id_quo,
                    'activity_name'         => "Update Data Request SKU Product " . $qry,
                    'activity_id_user'      => Auth::id(),
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                $qry4 = ActQuoModel::insert($data);
            }
        }
        return redirect('product/content/listcontent');
    }



    public function destroy($id)
    {
        $query = 'DELETE product, product_spesifikasi,product_price, product_image
        FROM product JOIN product_spesifikasi ON product_spesifikasi.pro_spek_id = product.pro_id
        JOIN product_price ON product_price.price_id = product.pro_id
        JOIN product_image on product_image.img_id = product.pro_id WHERE product.pro_id = ?';
        DB::delete($query, array($id));

        return redirect()->route('listcontent.index')
            ->with('success', 'Data deleted successfully');
    }


    public function save($request, $save, $id = 0)
    {
        // dd($request);
        if ($request->has('pro_image')) {
            $new = $request->file('pro_image');
            $newName = time() . '-' . $new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image', $newName);
        } else {
            $newName = null;
        }
        // dd($test);
        $status = $request->input('pro_status') == '' ? 'Pending' : ListContent::where('pro_id', $request->input('pro_status'))->first();
        $active = $request->input('pro_active') == '' ? 'Active' : ListContent::where('pro_id', $request->input('pro_active'))->first();
        $data   = [
            'pro_request_id'     => $request->pro_request_id,
            'id_quo'             => $request->id_quo,
            'pro_vn'             => $request->input('pro_vn'),
            'pro_image'          => $newName,
            'pro_name'           => $request->input('pro_name'),
            'pro_manufacture'    => $request->input('pro_manufacture'),
            'pro_categories'     => $request->input('pro_categories'),
            'pro_active'         => $active,
            'pro_dimesion'       => $request->input('pro_dimension'),
            'pro_status'         => $status,
            'pro_weight'         => $request->input('pro_weight'),
            'weight_class_id'    => $request->input('weight_class_id'),
            'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
            'pro_spec'           => $request->input('pro_spec'),
            'pro_desc'           => $request->input('pro_desc'),
            'length_unit'        => $request->input('length_unit'),
            'pro_created_date'   => Carbon::now('GMT+7')->toDateTimeString(),
            'token'              => $request->input('token'),
            $save . '_by'        => Auth::id()
        ];
        $qry = ListContent::create($data);
        if ($qry) {
            $spek = [
                'pro_spek_id'          => $qry->pro_id,
                'pro_spek_name'        => $request->input('pro_spec'),
                'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
                $save . '_by'                => Auth::id()
            ];
            $qry1 = ProSpekModel::create($spek);
        }
        if ($qry1) {
            $imagedat  = [
                'img_id'    => $qry->pro_id,
                'pro_name'  => $request->input('pro_name'),
                'img_image' => $newName,
                $save . '_by'     => Auth::id()
            ];
            $qry2 = $save  == 'created' ? ProImageModel::create($imagedat) : ProImageModel::where('img_id', $id)->update($imagedat);
        }
        if ($qry2) {
            $price1 = [
                'price_id'     => $qry->pro_id,
                'price_modal'  => $request->input('price_modal'),
                'price_retail' => $request->input('price_retail'),
                'catalog_price' => $request->input('catalog_price'),
                'price_gov'    => $request->input('price_gov'),
                $save . '_by'        => Auth::id(),
            ];
            $qry3 = ProPriceModel::create($price1);
            if ($request->id_quo != null) {
                if ($qry3) {
                    $id_quo = ActQuoModel::where('activity_id', $request->id_quo)->first();
                    $qry = $request->pro_name;
                    $data   = [
                        'activity_id_quo'       => $request->id_quo,
                        'activity_name'         => "Menambahkan Request SKU Product " . $qry,
                        'activity_id_user'      => Auth::id(),
                        'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString(),
                    ];
                    $qry4 = ActQuoModel::insert($data);
                }
            }
            return redirect('product/content/listcontent');
        }
    }

    public function ajax_data(Request $request)
    {
        $columns = array(

            0 => 'pro_name',
            1 => 'pro_categories',
            2 => 'pro_stock',
            3 => 'pro_active',
            4 => 'pro_spec',
            5 => 'pro_price',
            6 => 'pro_status',
            7 => 'pro_id',

        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = ListContent::join('product_price', 'product_price.price_id', '=', 'product.pro_id')->where('pro_status', 'Approved')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = ListContent::join('product_price', 'product_price.price_id', '=', 'product.pro_id')
                ->where('pro_status', 'Approved')->orWhere('pro_status', 'Waiting')
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
        //  dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($post->type_harga == "Harga Normal") {
                    $price = $post->price_retail;
                } else {
                    $price = $post->catalog_price;
                }
                $data[] = [
                    'pro_name'           => $post->pro_name,
                    'pro_active'         => $post->pro_active,
                    'pro_berlaku_sampai' => $post->pro_berlaku_sampai,
                    'pro_categories'     => LiveCat($post->pro_categories)->name,
                    'pro_manufacture'    => LiveMan($post->pro_manufacture)->name,
                    'pro_status'         => $post->pro_status,
                    'pro_price'          => $price,
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

    function price($angka)
    {
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

    public function imported(Request $request)
    {
        Excel::import(new AllProductsImport, $request->file('file'));
        return redirect()->route('listcontent.index')
            ->with('success', 'Product Content import successfully');
    }

    public function export_formats()
    {

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->getStyle('A1:P1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(25);
        $sheet->getColumnDimension('J')->setWidth(40);
        $sheet->getColumnDimension('K')->setWidth(40);
        $sheet->getColumnDimension('L')->setWidth(25);
        $sheet->getColumnDimension('M')->setWidth(25);
        $sheet->getColumnDimension('N')->setWidth(25);
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(30);
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getDefaultRowDimension()->setRowHeight(35);

        $sheet->setCellValue('A1', 'part_number');
        $sheet->setCellValue('B1', 'product_name');
        $sheet->setCellValue('C1', 'category');
        $sheet->setCellValue('D1', 'brand');
        $sheet->setCellValue('E1', 'dimensi');
        $sheet->setCellValue('E2', '0x0x0');
        $sheet->setCellValue('F1', 'satuan_panjang');
        $sheet->setCellValue('G1', 'berat');
        $sheet->setCellValue('H1', 'satuan_berat');
        $sheet->setCellValue('I1', 'date_available');
        $sheet->setCellValue('I2',  Carbon::now()->format('Y-m-d'));
        $sheet->setCellValue('J1', 'overview');
        $sheet->setCellValue('K1', 'spesifikasi');
        $sheet->setCellValue('L1', 'type_harga');
        $sheet->setCellValue('M1', 'harga_modal');
        $sheet->setCellValue('N1', 'harga_produk');
        $sheet->setCellValue('O1', 'harga_catalog');
        $sheet->setCellValue('P1', 'image');
        // $left = \PhpOffice\PhpSpreadsheet\Calculation\TextData\Extract::LEFT;
        // dd($left);

        $rows   = $i= 2;
        $id_pro = ListContent::select('*')->max('pro_id');
            // $rows++;
            $form =  $sheet->SetCellValue('P' . $i, '=CONCATENATE(' .Carbon::now()->format('Y') . ',"_",LEFT(B' . $i . ',3),"_",LEFT(C' . $i . ',5),"_",ROW(B' . $i .'),"_",(ROW(B' . $i .')+'.$id_pro.'),"_MassUpload.jpg")');
            $sheet->getStyle('I')
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            $sheet->getStyle('J')->getAlignment()->setWrapText(false);
            $sheet->getStyle('K')->getAlignment()->setWrapText(false);

        $validation  = $sheet->getCell('L2')->getDataValidation()->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setFormula1('"Harga Normal, Harga Ecatalog"')->setAllowBlank(false)->setShowDropDown(true)
            ->setShowInputMessage(true)->setPromptTitle('Note')
            ->setPrompt('Must select one from the drop down options.')->setShowErrorMessage(true)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)->setErrorTitle('Invalid option')
            ->setError('Select one from the drop down list.');

        $validation1 = $sheet->getCell('H2')->getDataValidation()->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setFormula1('"Gram, Pound, Kilogram, Ounce"')->setAllowBlank(false)->setShowDropDown(true)
            ->setShowInputMessage(true)->setPromptTitle('Note')
            ->setPrompt('Must select one from the drop down options.')->setShowErrorMessage(true)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)->setErrorTitle('Invalid option')
            ->setError('Select one from the drop down list.');

        $validation2 = $sheet->getCell('F2')->getDataValidation()->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setFormula1('"Centimeter, Millimeter, Inch"')->setAllowBlank(false)->setShowDropDown(true)
            ->setShowInputMessage(true)->setPromptTitle('Note')
            ->setPrompt('Must select one from the drop down options.')->setShowErrorMessage(true)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)->setErrorTitle('Invalid option')
            ->setError('Select one from the drop down list.');

        $sheet->setDataValidation("L2:L1000", $validation);
        $sheet->setDataValidation("F2:F1000", $validation2);
        $sheet->setDataValidation("H2:H1000", $validation1);


        $writer = new Xlsx($excel);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Dokumen Format Import.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xls');
        $writer->save('php://output');
    }


    public function import(Request $request)
    {
        // dd($request);
        if ($request->has('resp')) {
            dd($request);
        } else {
            $file        = $request->file('file');
            $newName     = Storage::disk('public')->putFileAs('documents', $request->file('file'), $file->getClientOriginalName());
            $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($newName);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($newName);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $html        = view(
                'Product.ListContent.show_excel',
                ['sheet' => $sheetData, 'file' => $newName]
            )->render();
            return response()->json([
                'sheet' => $sheetData,
                'file'  => $newName,
                'view'  => $html,
            ]);
        }
    }

    public function saveImport(Request $request)
    {
        $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($request->file);
        $sheetData   = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $sku         = PendingApproval::select('*')->max('pro_id');
        $row         = 1;
        foreach ($sheetData as $sh => $x) {
            if ($row != $sh && LikeCatID($sheetData[$sh]["C"]) != null) {
                $v_skuid       = $sku++;
                $part_num      = $sheetData[$sh]["A"];
                $pro_name      = $sheetData[$sh]["B"];
                $category      = $sheetData[$sh]["C"];
                $brand         = $sheetData[$sh]["D"];
                $dimensi       = $sheetData[$sh]["E"];
                $sat_panjang   = $sheetData[$sh]["F"];
                $berat         = $sheetData[$sh]["G"];
                $sat_berat     = $sheetData[$sh]["H"];
                $exp_date      = $sheetData[$sh]["I"];
                $overview      = $sheetData[$sh]["J"];
                $spesifikasi   = $sheetData[$sh]["K"];
                $type_harga    = $sheetData[$sh]["L"];
                $harga_modal   = $sheetData[$sh]["M"];
                $harga_produk  = $sheetData[$sh]["N"];
                $harga_catalog = $sheetData[$sh]["O"];
                $image         = $sheetData[$sh]["P"];

                $search   = array("[", "]", "\n");
                $replace  = array('<b>', '</b>', '<br>');
                $str_spek = str_replace($search, $replace, $spesifikasi, $count);
                $str_ovw  = str_replace($search, $replace, $overview, $count);

                $pr_pro     = str_replace(",", "", $harga_produk);
                $pr_produk  = str_replace(".", ",", $pr_pro);


                if($harga_catalog!=0 || $harga_catalog!=''){
                $cat1       = str_replace(",", "", $harga_catalog);
                $persens    = $cat1 * (3 / 100);
                $hitung     = $persens + $cat1;
                $pr_catalog = str_replace(".", ",", $hitung);
                }

                $pr_mod   = str_replace(",", "", $harga_modal);
                $pr_modal = str_replace(".", ",", $pr_mod);

                // Harga 
                if ($type_harga == "Harga Normal") {
                    $str1     = str_replace(",", "", $harga_produk);
                    $price    = $str1;
                } else {
                    $str1       = str_replace(",", "", $harga_catalog);
                    $format     = number_format($str1,2);
                    $str_format = str_replace(",", "", $format);
                    $persen     = $str_format * (3 / 100);
                    $calc       = $persen + $str_format;
                    $price      = $calc;
                }

                // Dimensi
                $ex_dimensi = explode('x', $dimensi);

                $list       = [
                    'pro_sku'            => "SKU" . LikeCatID($category)->category_id . LikeManID($brand)->manufacturer_id . sprintf("%06d", $v_skuid),
                    'pro_vn'             => $part_num,
                    'pro_name'           => $pro_name." [".$part_num."]",
                    'pro_categories'     => LikeCatID($category)->category_id,
                    'pro_manufacture'    => LikeManID($brand)->manufacturer_id,
                    'pro_active'         => "Active",
                    'pro_dimesion'       => $dimensi,
                    'pro_status'         => "Approved",
                    'pro_weight'         => $berat,
                    'pro_image'          => $image,
                    'weight_class_id'    => getWeightClass($sat_berat)->weight_class_id,
                    'pro_berlaku_sampai' => $exp_date,
                    'pro_desc'           => $str_ovw,
                    'pro_spec'           => $str_spek,
                    'length_unit'        => $sat_panjang,
                    'pro_created_date'   => Carbon::now('GMT+7')->toDateTimeString(),
                ];
                $lists  = ListContent::create($list);
                if($lists){
                $appr= [
                    'pro_sku'            => $lists->pro_sku,
                    'pro_vn'             => $lists->pro_vn,
                    'id_prolist'         => $lists->pro_id,
                    'pro_categories'     => LikeCatID($category)->category_id,
                    'pro_manufacture'    => LikeManID($brand)->manufacturer_id,
                    'pro_name'           => $pro_name." [".$part_num."]",
                    'pro_desc'           => $str_ovw,
                    'pro_spec'           => $str_spek,
                    'pro_dimesion'       => $dimensi,
                    'pro_weight'         => $berat,
                    'weight_class_id'    => getWeightClass($sat_berat)->weight_class_id,
                    'pro_berlaku_sampai' => $exp_date,
                    'pro_active'         => "Active",
                    'pro_type'           => "Mass Upload",
                    'pro_image'          => $image,
                    'created_at'         => Carbon::now(),
                    'created_by'         => Auth::id(),
                ];

                $img = [
                    'pro_name' => $pro_name." [".$part_num."]",
                    'img_id'   => $lists->pro_id,
                    'img_image' => $image,
                ];
                $spek = [
                    'pro_spek_name'        => $str_spek,
                    'pro_spek_id'          => $lists->pro_id,
                    'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
                ];

                $prices = [
                    'price_id'      => $lists->pro_id,
                    'price_modal'   => $harga_modal!=0 ? $pr_modal : $harga_modal,
                    'price_retail'  => $harga_produk==0 || $harga_produk=='' ? $cat1 : $pr_pro,
                    'catalog_price' => $harga_catalog==0 || $harga_catalog=='' ? $harga_catalog : $price,
                    'type_harga'    => $type_harga,
                ];
                
                $live = [
                    'model'           => $brand,
                    'sku'             => "SKU" . LikeCatID($category)->category_id . LikeManID($brand)->manufacturer_id . sprintf("%06d", $v_skuid),
                    'manufacturer_id' => LikeManID($brand)->manufacturer_id,
                    'price'           => $price,
                    'image'           => 'catalog/PRIORITAS/' . $image,
                    'weight'          => $berat,
                    'weight_class_id' => getWeightClass($sat_berat)->weight_class_id,
                    'date_available'  => $exp_date,
                    'status'          => 1,
                    'length'          => $ex_dimensi[0],
                    'width'           => $ex_dimensi[1],
                    'height'          => $ex_dimensi[2],
                    'tax_class_id'    => 11,
                    'stock_status_id' => 7,
                    'length_class_id' => Lengthunit($sat_panjang)->length_class_id,
                    'date_added'      => Carbon::now('GMT+7')->toDateTimeString(),
                    'date_modified'   => Carbon::now('GMT+7')->toDateTimeString(),
                    'unspsc'          => "",
                    'upc'             => "",
                    'ean'             => "",
                    'jan'             => "",
                    'isbn'            => "",
                    'mpn'             => "",
                    'location'        => "",
                    'quantity'        => 0,
                    'points'          => 0,
                    'subtract'        => 1,
                    'minimum'         => 1,
                    'sort_order'      => 0,
                    'shipping'        => 1,
                    'viewed'          => 0,
                ];
                $apprs  = PendingApproval::create($appr);
                $prices = ProPriceModel::create($prices);
                $speks  = ProSpekModel::create($spek);
                $imgs   = ProImageModel::create($img);
                $lives  = ProductLive::create($live);
                }if ($lives) {
                    $pro_desc = [
                        'product_id'             => $lives->product_id,
                        'name'                   => $pro_name." [".$part_num."]",
                        'overview'               => $str_ovw,
                        'description'            => '',
                        'language_id'            => 2,
                        'fimage'                 => "",
                        'video1'                 => "",
                        'html_product_shortdesc' => "",
                        'html_product_right'     => "",
                        'html_product_tab'       => "",
                        'tab_title'              => "",
                        'meta_title'             => $pro_name." [".$part_num."]",
                        'meta_description'       => "",
                        'meta_keyword'           => "",
                        "tag"                    => $category,
                    ];
                    $live_image = [
                        'product_id' => $lives->product_id,
                        'image'      => 'catalog/' . $image,
                        'sort_order' => 0,
                    ];
                    $cat = [
                        'product_id'  => $lives->product_id,
                        'category_id' => LikeCatID($category)->category_id,
                    ];
                    $live_store = [
                        'product_id' => $lives->product_id,
                        'store_id'   => 0,
                    ];
                    $liveLayout = [
                        'product_id' => $lives->product_id,
                        'store_id'   => 0,
                        'layout_id'  => 0,
                    ];
                    $liveAtr = [
                        'product_id'   => $lives->product_id,
                        'attribute_id' => 347,
                        'language_id'  => 2,
                        'text'         => $str_spek,
                    ];

                    $historyprice = [
                        'id_pro'         => $lists->pro_id,
                        'id_pro_live'    => $lives->pro_id,
                        'harga'          => $price,
                        'status'         => "Approved New Content",
                        'sku'            => "SKU" . LikeCatID($category)->category_id . LikeManID($brand)->manufacturer_id . sprintf("%06d", $v_skuid),
                        'created_by'     => Auth::id(),
                    ];
                    $historystatus = [
                        'id_pro'         => $lists->pro_id,
                        'id_pro_live'    => $lives->product_id,
                        'sku'            => $lives->sku,
                        'status_App'     => "Approved New Content",
                        'status'         => "Active",
                        'created_by'     => Auth::id(),
                    ];
                    $pro_desc    = LiveProDescModel::create($pro_desc);
                    $live_img    = LiveProImage::create($live_image);
                    $live_stores = LiveProToStore::create($live_store);
                    $liveLayouts = LiveProToLayout::create($liveLayout);
                    $tblAtrs     = LiveProAtribut::create($liveAtr);
                    $cats        = LiveProToCat::create($cat);
                    $prices      = ProHargaHist::create($historyprice);
                    $statuss     = ProStatusHist::create($historystatus);
                }
            }
        }
        return redirect('product/content/listcontent')->with('success', 'Import Data Successfully');
    }



    public function import_zip(Request $request)
    {
        $fileName = carbon::now()->format('y H-s') . '.zip';
        $new = $request->file('file');
        $newName = Carbon::now()->format('Y-m-d')."_MassUpload.zip";
        $request->file->storeAs('storage/zip_file', $newName);
        return redirect('product/content/listcontent')->with('success', 'Upload Zip File Successfully');
    }

    public function apply(Request $request, $id)
    {

        $lead  = ListContent::find($id);
        $price = ProPriceModel::find($id);
        $ap   = ListContent::where('pro_id', $id)->first();
        if ($lead->id_quo != null) {
            $type = "Request New SKU";
        } else {
            $type = "Content New SKU";
        }

        if ($price->catalog_price != null) {
            $price = $price->catalog_price;
        } else {
            $price = $price->price_retail;
        }
        $apply = [
            'id_prolist'         => $lead->pro_id,
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
            'pro_price'          => $price,
            'pro_request_id'     => $lead->pro_request_id,
            'length_unit'        => $lead->length_unit,
            'pro_desc'           => $lead->pro_desc,
            'id_quo'             => $lead->id_quo,
        ];
        $qry = PendingApproval::create($apply);
        if ($qry) {
            $ap   = ListContent::where('pro_id', $id)->first();
            $data = [
                'pro_status' => 'Waiting',
            ];
            $qry2 = ListContent::where('pro_id', $id)->update($data);
        }
        if ($ap->pro_request_id != null || $ap->pro_id != null) {
            if ($qry2) {
                $pricedesc = ProPriceModel::where('price_id', $ap->pro_id)->first();
                $QP = [
                    'det_quo_harga_modal' => $pricedesc->price_modal,
                ];
                $qry3 = QuotationProduct::where('id_product_request', $ap->pro_request_id)->update($QP);
            }
            if ($qry3) {
                $act  = ActQuoModel::where('activity_id_quo', $lead->id_quo)->first();
                $data = [
                    'activity_id_quo'       => $lead->id_quo,
                    'activity_name'         => "Mengajukan Approval Product " . $lead->pro_name,
                    'activity_id_user'      => Auth::id(),
                    'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
                ];
                $qry4 = ActQuoModel::insert($data);
            }
        }
        return redirect('product/content/listcontent/')->with('success', 'Send Data successfully');
    }

    public function getImg()
    {
        return view('Product.listcontent.photo');
    }

    public function PostImg(Request $request)
    {
        if ($request->hasFile('image')) {
            $resorce = $request->file('image');
            $name    = $resorce->getClientOriginalName();
            $resorce->move(\storage_path() . "/public/images", $name);
            // $save = DB::table('images')->insert(['image' => $name]);
            echo $resource;
        } else {
            echo "Gagal upload gambar";
        }
    }
}
