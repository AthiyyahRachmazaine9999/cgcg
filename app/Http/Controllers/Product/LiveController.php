<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductLive;
use App\Models\Product\ProPriceModel;
use App\Models\Product\ProSpekModel;
use App\Models\Product\LiveProDescModel;
use App\Models\Product\LiveProToCat;
use App\Models\Product\LiveLengthModel;
use App\Exports\ExportContent;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product\LiveProImage;
use App\Models\Product\LiveProToStore;
use App\Models\Product\LiveProToLayout;
use App\Models\Product\LiveProAtribut;
use App\Models\Product\LiveManModel;
use App\Models\Product\PendingApproval;
use App\Models\Product\ListContent;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProImageModel;
use App\Models\Product\ProStatusHist;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Product\ProductReq;
use App\Models\Product\LiveCatModel;
use App\Models\Product\LiveWeightModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DB;
use Carbon\Carbon;


class LiveController extends Controller
{
    public function index(Request $request)
    {
            $live= ProductLive::all();

            return view('Product.Live.index', [
                'live' => $live,
                'brand' => $this->AllMan(),
            ]);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        // return view('Product/brand.create');
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //return $this->save($request, 'created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($id)
    {        
        $live    = ProductLive::find($id);
        
        $desc    = LiveProDescModel::find($id);
        $cat     = LiveProToCat::where('product_id',$live->product_id)->first();
        $histhrg = ProHargaHist::where('sku', $live->sku)->first();
        $persen  = $live->price * (10/100);
        $hitung  = ($persen + $live->price);
        $textAtr = LiveProAtribut::where('product_id', $live->product_id)->first();
        return view ('Product.Live.show',[
            'text'  => $textAtr->text,
            'hitung'=> $hitung,
            'harga' => ProHargaHist($live->sku),
            'length'=> LengthUnitID($live->length_class_id)->title,
            'live'  => $live,
            'desc'  => $desc,
            'we'    => Weight($live->weight_class_id),
            'cat'   => Category($cat->category_id),
            'man'   => Manufacture($live->manufacturer_id),
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
        // dd($id);
        $live    = ProductLive::where('product_id', $id)->first();
        // dd($live->status);
        $desc    = LiveProDescModel::where('product_id',$id)->first();
        $atribut = LiveProAtribut::where('product_id', $live->product_id)->first();
        $toCat   = LiveProToCat::where('product_id',$live->product_id)->first();
        $length  = LiveLengthModel::where('language_id', '2')->get();
        $list    = ListContent::where('pro_sku', $live->sku)->first();  
        $price   = $list==null?null:ProPriceModel::where('price_id', $list->pro_id)->first();
        $app     = $list==null?null:PendingApproval::where('pro_sku', $live->sku)->first();
        $arr     = array(
            'length'  => round($live->length),
            'width'   => round($live->width),
            'height'  => round($live->height),
        );
        $imp=implode("x", $arr);

        $join_atr    = LiveProAtribut::join('ocbz_attribute_description as pd', 'pd.attribute_id', '=', 'ocbz_product_attribute.attribute_id')->where('ocbz_product_attribute.product_id', $live->product_id)
        ->where('name', '=', 'Lain - Lain')->first();
        $ot_join_atr = LiveProAtribut::join('ocbz_attribute_description as pd', 'pd.attribute_id', '=', 'ocbz_product_attribute.attribute_id')->where('ocbz_product_attribute.product_id', $live->product_id)
        ->where('name', '=', 'Other')->first();
        
        if($ot_join_atr == null && $join_atr!=null){
            $name_atr = $join_atr->text;
            $atr_id   = $join_atr->attribute_id;
        }else if ($ot_join_atr !=null && $join_atr==null){
            $name_atr = $ot_join_atr->text;
            $atr_id   = $ot_join_atr->attribute_id;
        }else{
            $name_atr = null;
            $atr_id   = null;
        }


        // dd($list, $price);
        return view('Product.Live.edit', [
            'toCat'       => $toCat==null ? null : $toCat->category_id,
            'length'      => $length==null ? null : $length,
            'lengthID'    => $live->length_class_id==null ? null : LengthUnitID($live->length_class_id)->title,
            'category'    => $this->AllCat(),
            'manufacture' => $this->AllMan(),
            'weight'      => $this->Weight(),
            'length'      => $this->length(),
            'dimensi'     => $imp,
            'catalog'     => $price==null ? null : $price->catalog_price,
            'price_type'  => $price==null ? null : $price->type_harga,
            'price'       => $price==null ? null : $price,
            'atribut'     => $atribut==null ? null : $atribut,
            'live'        => $live,
            'list'        => $list,
            'pro_id'      => $list==null? "NULL" : $list->pro_id,
            'desc'        => $desc,
            'name_atr'    => $name_atr,
            'marks'       => $ot_join_atr == null && $join_atr==null ? "kosong" : "ada",
            'atr_id'      => $atr_id,
            'method'      => "put",
            'action'      => ['Product\LiveController@update',$id],
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
        if($request->pro_id=="NULL"){
        return $this->EditBaru($request, 'update', $id)->with('Success', 'Data Send to Request Page Successfully');
        }else{
        return $this->EditSave($request, 'update', $id)->with('Success', 'Data Send to Request Page Successfully');
        }
    }

////////////////Edit Save Data yang sudah ada di database////////////////
    public function EditSave($request, $id=0, $save)
    {
        // dd($request);
        $live    = ProductLive::where('product_id', $request->product_id)->first();
        $desc    = LiveProDescModel::where('product_id', $live->product_id)->first();
        $atribut = LiveProAtribut::where('product_id', $live->product_id)->first();
        $ToCat   = LiveProToCat::where('product_id', $live->product_id)->first();
        $app     = PendingApproval::where('pro_sku', $request->sku)->first();
        $list    = ListContent::where('pro_sku', $request->sku)->first();
        $quoPro  = QuotationProduct::where('id_product',$request->sku)->first();

        // dd($atribut, $live);
        //Gambar
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image',$newName);
        }else{
            $newName=$live->image==null ? null : $live->image;
        }
        //Harga
        if($request->price_type=="Harga Normal"){
            $price = $live->price==$request->price ? $live->price : $request->input('price');
        }else{
            $price = $live->price==$request->price ? $live->price : $request->input('catalog');
        }
        if($live->status!=$request->status){
        $upType = "Update Status";
        }else{
        $upType = "Update Data Product";
        }

        if($request->status==1)
        {
            $status="Active";
        }else
        {
            $status="In Active";
        }
        if($request->hidPrice!=$request->price){
                $harga=[
                        'id_pro_live'=> $live->product_id,
                        'id_pro'     => $list->pro_id,
                        'sku'        => $request->sku,
                        'harga'      => $request->price_type=="Harga Normal" ? $request->input('price') : $request->input('catalog'),
                        'status'     => "Update Price",
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
                ]; 
                // dd($harga);
            $qry3=ProHargaHist::create($harga);
        }if($request->hidstatus!=$request->status){
                    if($request->status==1)
                        {
                            $status="Active";
                        }else
                        {
                            $status="In Active";
                        }
            $statushist = [
                    'id_pro_live'=> $live->product_id,
                    'id_pro'     => $list->pro_id,
                    'sku'        => $request->sku,
                    'status'     => $status,
                    'status_App' => "Update Status",
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                ]; 
        $qry9=ProStatusHist::create($statushist);
        }
        $data = [
                'id_quo'             => $list->id_quo==null? null : $request->id_quo,
                'pro_request_id'     => $list->pro_request_id==null? null : $request->pro_request_id, 
                'live_id'            => $request->product_id,
                'id_prolist'         => $list->pro_id,
                'pro_sku'            => $request->sku,
                'pro_image'          => $newName,
                'pro_status'         => "Approved",
                'up_type'            => $upType,
                'pro_berlaku_sampai' => $live->date_available,
                'pro_dimesion'       => $request->pro_dimension,
                'pro_price'          => $price,
                'pro_active'         => $list==null? "Active" : $status,
                'pro_desc'           => $desc->overview==$request->overview ? $desc->overview : $request->input('overview'),
                'pro_spec'           => $atribut->text==$request->pro_spec ? $atribut->text : $request->input('pro_spec'),
                'pro_name'           => $desc->name==$request->name ? $desc->name : $request->input('name'),
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_weight'         => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                'length_unit'        => $live->length_class_id == $request->length_class_id ? LengthUnitID($live->length_class_id)->title : LengthUnitID($request->length_class_id)->title,
                'pro_type'           => $upType,
                'pro_manufacture'    => $live->manufacturer_id==$request->manufacturer_id ? $live->manufacturer_id : $request->input('manufacturer_id'),
                'pro_categories'     => $ToCat->category_id==$request->category_id ? $ToCat->category_id : $request->input('category_id'),                
                'created_by'         => Auth::id(),
        ];
        // dd($data);
        $qry = PendingApproval::create($data);
        if($qry){
            //LIST
        $list = ListContent::where('pro_sku', $live->sku)->first();
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image',$newName);
        }else{
            $newName=$list->pro_image;
        }

        if($request->status==1)
        {
            $status="Active";
        }else
        {
            $status="In Active";
        }
        $price= ProPriceModel::where('price_id', $list->pro_id)->first();
        if($price==null)
        {
            if($request->price_type=="Harga Normal"){
                $retail = $request->input('price');
                $catalog= $request->input('catalog');
            }else{
                $catalog = $request->input('catalog');
                $retail  = $request->input('price');
            }
        }else{
            if($price->type_harga=="Harga Normal"){
                $retail = $price->price_retail==$request->price ? $price->price_retail : $request->input('price');
                $catalog= $price->catalog_price==null ? null : $price->catalog_price;
            }else{
                $catalog = $price->catalog_price==$request->price ? $price->catalog_price : $request->input('catalog');
                $retail   = $price->price_retail==null ? null : $price->price_retail;
            }
        }

        $spek = ProSpekModel::where('pro_spek_id', $list->pro_id)->first();
        $uplist = [
                'id_quo'             => $list->id_quo==null? null : $request->id_quo,
                'pro_request_id'     => $list->pro_request_id==null? null : $request->pro_request_id, 
                'pro_active'         => $list->pro_active==$request->status? $list->pro_active : $status,
                'pro_desc'           => $desc->overview==$request->overview ? $desc->overview : $request->input('overview'),
                'pro_spec'           => $atribut->text==$request->pro_spec ? $atribut->text : $request->input('text'),
                'pro_name'           => $desc->name==$request->name ? $desc->name : $request->input('name'),
                'pro_dimesion'       => $request->pro_dimension,
                'pro_image'          => $newName,
                'pro_weight'         => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                'length_unit'        => $live->length_class_id == $request->length_class_id ? LengthUnitID($live->length_class_id)->title : LengthUnitID($request->length_class_id)->title,
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_manufacture'    => $live->manufacturer_id==$request->manufacturer_id ? $live->manufacturer_id : $request->input('manufacturer_id'),
                'pro_categories'     => $ToCat->category_id==$request->category_id ? $ToCat->category_id : $request->input('category_id'),                
                'created_by'         => Auth::id(),
                'pro_update_date'    => Carbon::now('GMT+7')->toDateTimeString(),
        ];

        if($price==null)
        {
            $upListHarga = [
                    'price_retail' => $request->input('price'),
                    'catalog_price'=> $request->input('catalog'),
                    'price_modal'  => $request->input('price_modal'),
                    'type_harga'   => $request->price_type,
                    'update_by'    => Auth::id(),
            ];        
        }else{
            $upListHarga = [
                    'price_retail' => $price->price_retail==$request->price ? $price->price_retail : $request->input('price'),
                    'catalog_price'=> $price->catalog_price==$request->price ? $price->catalog_price : $request->input('catalog'),
                    'price_modal'  => $price->price_modal==$request->price_modal ? $price->price_modal : $request->input('price_modal'),
                    'type_harga'   => $price->type_harga==$request->price_type ? $price->type_harga : $request->price_type,
                    'update_by'    => Auth::id(),
            ];
        }
        $upImage = [
            'img_image'       => $newName,
            'pro_name'        => $desc->name==$request->name ? $desc->name : $request->input('name'),
            'img_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
            'update_by'       => Auth::id(),
        ];

        $upSpek = [
            'pro_spek_name'    => $atribut->text==$request->pro_spec ? $atribut->text : $request->input('text'),
            'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
            'update_by' => Auth::id(),
        ];
        // dd($upSpek,$upListHarga,$uplist);
        $upImage  = ProImageModel::where('img_id', $list->pro_id)->update($upImage);        
        $proprice = ProPriceModel::where('price_id', $list->pro_id)->update($upListHarga);
        $qry1     = ListContent::where('pro_sku', $live->sku)->update($uplist);
        $prospek  = ProSpekModel::where('pro_spek_id', $list->pro_id)->update($upSpek);
        }if($prospek){
            //LIVE
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= 'catalog/PRIORITAS/'.time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image',$newName);
        }else{
            $newName=$live->image;
        }

        $arr_dm = explode('x' , $request->pro_dimension);
        $upLive = [
                'model'              => $live->model==Manufacture($request->manufacturer_id) ? $live->model : Manufacture($request->manufacturer_id),
                'manufacturer_id'    => $live->manufacturer_id==$request->manufacturer_id ? $live->manufacturer_id : $request->input('manufacturer_id'),
                'price'              => $request->price_type=="Harga Normal" ? $request->input('price') : $request->input('catalog'),
                'status'             => $live->status==$request->status? $live->status : $request->status,
                'image'              => $newName,
                'date_modified'      => Carbon::now('GMT+7')->toDateTimeString(),
                'length_class_id'    => $request->input('length_class_id'),
                'length'             => $arr_dm==null ? null : $arr_dm[0],
                'width'              => $arr_dm==null ? null : $arr_dm[1],
                'height'             => $arr_dm==null ? null : $arr_dm[2],
                'date_available'     => $request->input('pro_berlaku_sampai'),
                'weight'             => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                ];
            $upName = [
                'name'    => $desc->name==$request->name ? $desc->name : $request->input('name'),      
                'overview'=> $desc->overview==$request->overview ? $desc->overview : $request->input('overview'),
            ];
            $upAtr = [
                'text'  => $atribut->text==$request->pro_spec ? $atribut->text : $request->input('pro_spec'),
            ];


        $livetocat = [
                'category_id' => $ToCat->category_id==$request->category_id ? $ToCat->category_id : $request->input('category_id'),                
        ];
        $livetocat = LiveProToCat::where('product_id', $request->product_id)->update($livetocat);        
        $upAtr  = $request->desc_atr=="kosong" ? LiveProAtribut::create($upAtr) : LiveProAtribut::where('attribute_id', $request->atr_id)->where('product_id', $request->product_id)->update($upAtr);
        $upname = LiveProDescModel::where('product_id', $request->product_id)->update($upName);
        $qry2   = ProductLive::where('product_id',$request->product_id)->update($upLive);
    }
    return redirect('product/live')->with('success', 'Data Updated Successfully');   
}


//////////////////////Edit Save Yang Mana SKU lama dan Tidak ada di Tabel produk DKK/////////////////////// 
public function EditBaru($request, $id=0, $save)
    {
        // dd($request);
        $live    = ProductLive::where('product_id', $request->product_id)->first();
        $desc    = LiveProDescModel::where('product_id', $live->product_id)->first();
        $atribut = LiveProAtribut::where('product_id', $live->product_id)->first();
        $ToCat   = LiveProToCat::where('product_id', $live->product_id)->first();
        $app     = PendingApproval::where('pro_sku', $request->sku)->first();
        $list    = ListContent::where('pro_sku', $request->sku)->first();
        $quoPro  = QuotationProduct::where('id_product',$request->sku)->first();
        $status  = $request->status==1? "Active" : "In Active";
        //Gambar
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image',$newName);
        }else{
            $newName=$live->image==null ? null : $live->image;
        }
        //status
        if($request->status==1)
            {
                $status="Active";
            }
        else
            {
                $status="In Active";
            }
  
        //Harga
        if($request->price_type=="Harga Normal"){
            $price = $live->price==$request->price ? $live->price : $request->input('price');
        }else{
            $price = $live->price==$request->price ? $live->price : $request->input('catalog');
        }
        if($live->status!=$request->status){
        $upType = "Update Status";
        }else{
        $upType = "Update Data Product";
        }

        if($request->hidPrice!=$request->price){
                $harga=[
                        'id_pro_live'=> $live->product_id,
                        'id_pro'     => $live->product_id,
                        'sku'        => $request->sku,
                        'harga'      => $request->price_type=="Harga Normal" ? $request->input('price') : $request->input('catalog'),
                        'status'     => "Update Price",
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                        'updated_at' => Carbon::now('GMT+7')->toDateTimeString(),
                ]; 
                // dd($harga);
            $qry3=ProHargaHist::create($harga);
        }if($request->hidstatus!=$request->status){
                    if($request->status==1)
                        {
                            $status="Active";
                        }else
                        {
                            $status="In Active";
                        }
            $statushist = [
                    'id_pro_live'=> $live->product_id,
                    'id_pro'     => $live->product_id,
                    'sku'        => $request->sku,
                    'status'     => $status,
                    'status_App' => "Update Status",
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                ]; 
        $qry9=ProStatusHist::create($statushist);
        }
        $data = [
                'live_id'            => $request->product_id,
                'id_prolist'         => $live->product_id,
                'pro_sku'            => $request->sku,
                'pro_image'          => $newName,
                'pro_status'         => "Approved",
                'up_type'            => $upType,
                'pro_berlaku_sampai' => $live->date_available,
                'pro_dimesion'       => $request->pro_dimension,
                'pro_price'          => $price,
                'pro_active'         => $list==null? "Active" : $status,
                'pro_desc'           => $desc->overview==$request->overview ? $desc->overview : $request->input('overview'),
                'pro_spec'           => $request->input('pro_spec'),
                'pro_name'           => $desc->name==$request->name ? $desc->name : $request->input('name'),
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_weight'         => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                'length_unit'        => $live->length_class_id == $request->length_class_id ? LengthUnitID($live->length_class_id)->title : LengthUnitID($request->length_class_id)->title,
                'pro_type'           => $upType,
                'pro_manufacture'    => $live->manufacturer_id==$request->manufacturer_id ? $live->manufacturer_id : $request->input('manufacturer_id'),
                'pro_categories'     => $ToCat->category_id==$request->category_id ? $ToCat->category_id : $request->input('category_id'),                
                'created_by'         => Auth::id(),
        ];
        // dd($data);
        $qry = PendingApproval::create($data);
        if($qry){
        $uplist = [
                'pro_sku'            => $request->sku,
                'pro_active'         => $status,
                'pro_desc'           => $desc->overview==$request->overview ? $desc->overview : $request->input('overview'),
                'pro_spec'           => $request->input('pro_spec'),
                'pro_name'           => $desc->name==$request->name ? $desc->name : $request->input('name'),
                'pro_dimesion'       => $request->pro_dimension,
                'pro_image'          => $newName,
                'pro_weight'         => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                'length_unit'        => $live->length_class_id == $request->length_class_id ? LengthUnitID($live->length_class_id)->title : LengthUnitID($request->length_class_id)->title,
                'pro_berlaku_sampai' => $request->input('pro_berlaku_sampai'),
                'pro_manufacture'    => $live->manufacturer_id==$request->manufacturer_id ? $live->manufacturer_id : $request->input('manufacturer_id'),
                'pro_categories'     => $ToCat->category_id==$request->category_id ? $ToCat->category_id : $request->input('category_id'),                
                'created_by'         => Auth::id(),
                'pro_created_date'   => Carbon::now('GMT+7')->toDateTimeString(),
            ];
        $qry1  = ListContent::create($uplist);
        }
        if($qry1){
            //LIST
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image',$newName);
        }else{
            $newName=$live->image;
        }

        if($request->status==1)
        {
            $status="Active";
        }else
        {
            $status="In Active";
        }
        $upListHarga = [
                'price_id'     => $qry1->pro_id,
                'price_retail' => $request->input('price'),
                'catalog_price'=> $request->input('catalog'),
                'price_modal'  => $request->input('price_modal'),
                'type_harga'   => $request->price_type,
                'update_by'    => Auth::id(),
        ];
        $upImage = [
            'img_id'          => $qry1->pro_id,
            'img_image'       => $newName,
            'pro_name'        => $desc->name==$request->name ? $desc->name : $request->input('name'),
            'img_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
            'update_by'       => Auth::id(),
        ];

        $upSpek = [
            'pro_spek_id'          => $qry1->pro_id,
            'pro_spek_name'        => $request->input('pro_spec'),
            'pro_spek_update_date' => Carbon::now('GMT+7')->toDateTimeString(),
            'update_by'            => Auth::id(),
        ];
        // dd($upSpek,$upListHarga,$uplist);
        $upImage  = ProImageModel::create($upImage);        
        $proprice = ProPriceModel::create($upListHarga);
        $prospek  = ProSpekModel::create($upSpek);
        }if($prospek){
            //LIVE
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= 'catalog/PRIORITAS/'.time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/post-image',$newName);
        }else{
            $newName=$live->image;
        }

        $arr_dm = explode('x' , $request->pro_dimension);
        $upLive = [
                'model'              => $live->model==Manufacture($request->manufacturer_id) ? $live->model : Manufacture($request->manufacturer_id),
                'manufacturer_id'    => $live->manufacturer_id==$request->manufacturer_id ? $live->manufacturer_id : $request->input('manufacturer_id'),
                'price'              => $request->price_type=="Harga Normal" ? $request->input('price') : $request->input('catalog'),
                'status'             => $live->status==$request->status? $live->status : $request->status,
                'image'              => $newName,
                'date_modified'      => Carbon::now('GMT+7')->toDateTimeString(),
                'length_class_id'    => $request->input('length_class_id'),
                'length'             => $arr_dm==null ? null : $arr_dm[0],
                'width'              => $arr_dm==null ? null : $arr_dm[1],
                'height'             => $arr_dm==null ? null : $arr_dm[2],
                'date_available'     => $request->input('pro_berlaku_sampai'),
                'weight'             => $request->input('pro_weight'),
                'weight_class_id'    => $request->input('weight_class_id'),
                ];
            $upName = [
                'name'    => $desc->name==$request->name ? $desc->name : $request->input('name'),      
                'overview'=> $desc->overview==$request->overview ? $desc->overview : $request->input('overview'),
            ];
            $upAtr = [
                'text'  => $request->input('pro_spec'),
            ];
            $crAtr = [
                'product_id'   => $live->product_id,
                'attribute_id' => 347,
                'language_id'  => 2,
                'text'         => $request->input('pro_spec')==null ? "Spesifikasi Belum Terisi" : $request->input('pro_spec'),
            ];

            // dd($crAtr, $upAtr, $atribut, $request->desc_atr);
        $livetocat = [
                'category_id' => $ToCat->category_id==$request->category_id ? $ToCat->category_id : $request->input('category_id'),                
        ];
        $livetocat = LiveProToCat::where('product_id', $request->product_id)->update($livetocat);
        $upAtr  = $atribut==null ? LiveProAtribut::create($crAtr) : LiveProAtribut::where('attribute_id', $request->atr_id)->where('product_id', $request->product_id)->update($upAtr);
        $upname = LiveProDescModel::where('product_id', $request->product_id)->update($upName);
        $qry2   = ProductLive::where('product_id',$request->product_id)->update($upLive);
    }
    return redirect('product/live')->with('success', 'Data Updated Successfully');   
}


public function filter(Request $request)
{
    // dd($request);
        $columns = array(
             0 => 'model',
             1 => 'sku',
             2 => 'price',
             3 => 'name',
             4 => 'status',
             5 => 'product_id',
             6 => 'date_added',
         );
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
        
         $status = $request->segment(4);  //kosong
         $brand  = $request->segment(5);  //11
         $sdate  = $request->segment(6);  //11
         $edate  = $request->segment(7);  //kosong
        //  dd($brand, $status);
        $menu_count = ProductLive::filtersearch($brand,$status,$sdate,$edate);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = ProductLive::filtersearchlimit($brand,$status,$sdate,$edate,$start,$limit,$order, $dir)->get();
            // dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = ProductLive::filtersearchfind($brand,$status,$sdate,$edate,$start,$limit,$order, $dir, $search)->get();
            $totalFiltered = count(ProductLive::filtersearchfind($brand,$status,$start,$limit,$order, $dir, $search)->get());
        }
        // dd($posts);

         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                // dd($post);  
                 $data[] = [
                     'model'      => $post->model,
                     'sku'        => $post->sku,
                     'date_added' => Carbon::parse($post->date_added)->format('Y-m-d'),
                     'price'      => $this->price($post->price,$post->product_id),
                     'name'       => $post->name,
                     'status'     => $post->status,
                     'product_id' => $post->product_id,
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


public function export($brand, $status){
    // dd($brand, $status);
    return (new ExportContent($brand, $status))->download('Content_Export.xlsx');

}

public function ex_pro1($brand, $status){
    // dd($brand, $status);
    return (new ExportContent($brand, $status))->download('Content_Export.xlsx');

}

public function ex_pro(Request $request)
{
         $status = $request->segment(4); //kosong
         $brand  = $request->segment(5);  //11
         $sdate  = $request->segment(6);  //11
         $edate  = $request->segment(7);  //kosong
         $all    = $request->segment(8);  //kosong
        //  dd($all);

    if($all=="true")
    {
    $query = ProductLive::join('ocbz_product_description as p','p.product_id','=','ocbz_product.product_id')
        ->join('ocbz_product_attribute as at', 'at.product_id', '=', 'ocbz_product.product_id')->get();
    }
    else{
    $query = ProductLive::filterexport($brand,$status,$sdate, $edate)->get();
        // dd($query);
    }
    $j=1;
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:N1')->getFont()->setBold(TRUE);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(25);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(25);
        $sheet->getColumnDimension('M')->setWidth(25);
        $sheet->getColumnDimension('N')->setWidth(25);

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Model');
        $sheet->setCellValue('C1', 'No.SKU');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Image');
        $sheet->setCellValue('F1', 'Weight');
        $sheet->setCellValue('G1', 'Weight Class');
        $sheet->setCellValue('H1', 'Date Available');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Overview');
        $sheet->setCellValue('K1', 'Spesification');
        $sheet->setCellValue('L1', 'PxLxT');
        $sheet->setCellValue('M1', 'Length Class');
        $sheet->setCellValue('N1', 'Date Added');
        $rows = 2;
    foreach($query as $qp){
        $array  = array(
            'length' => round($qp->length),
            'weight' => round($qp->weight),
            'height' => round($qp->height),
        );
        $imp=implode("x", $array);
        $sheet->setCellValue('A' . $rows, $j++);  
        $sheet->setCellValue('B' . $rows, $qp['model']);  
        $sheet->setCellValue('C' . $rows, $qp['sku']);  
        $sheet->setCellValue('D' . $rows, $this->price($qp['price']));  
        $sheet->setCellValue('E' . $rows, $qp['image']); 
        $sheet->setCellValue('F' . $rows, $qp['weight']);
        $sheet->setCellValue('G' . $rows, Weight($qp['weight_class_id']));
        $sheet->setCellValue('H' . $rows, $qp['date_available']);  
        $sheet->setCellValue('I' . $rows, $qp['status']==1 ? "Active" : "In Active");
        $sheet->setCellValue('J' . $rows, preg_replace('/&lt;([\s\S]*?)&gt;/s','',$qp['overview']));  
        $sheet->setCellValue('K' . $rows, preg_replace('/&lt;([\s\S]*?)&gt;/s','',$qp['text']));  
        $sheet->setCellValue('L' . $rows, $imp);  
        $sheet->setCellValue('M' . $rows, LengthUnitID($qp['length_class_id'])->title);  
        $sheet->setCellValue('N' . $rows, $qp['date_added']);  
        $rows++;  
    }
        $writer = new Xlsx($spreadsheet);
        $writer->save('Product.xlsx');
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Product.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
}



public function save($request, $save, $id=0)
    {   
        $live    = ProductLive::where('product_id', $id)->first();
        $desc    = LiveProDescModel::where('product_id', $live->product_id)->first();
        $atribut = LiveProAtribut::where('product_id', $live->product_id)->first();
        $cat    = LiveProToCat::where('product_id' , $live->product_id)->first();
        $status = $request->input('status');
        $array  = array(
            'length' =>$live->length,
            'weight' => $live->weight,
            'height' => $live->height,
        );
        $imp=implode("x", $array);
        if($status==1)
        {
            $status="Active";
        }else
        {
            $status="In Active";
        }
        $data = [
                'live_id'            => $live->product_id,
                'pro_sku'            => $live->sku,
                'pro_image'          => $live->image,
                'pro_status'         => "Pending",
                'pro_berlaku_sampai' => $live->date_available,
                'pro_dimesion'       => $imp,
                'pro_price'          => $request->input('price'),
                'pro_active'         => $status,
                'pro_desc'           => $atribut->text,
                'pro_spec'           => $request->input('pro_spec'),
                'pro_name'           => $request->input('name'),
                'pro_weight'         => $live->weight,
                'weight_class_id'    => $live->weight_class_id,
                'length_unit'        => $live->length_class_id != 0 ? LengthUnitID($live->length_class_id)->title : 0,
                'pro_type'           => $request->input('pro_type'),
                'pro_manufacture'    => $live->length_class_id != 0 ? $live->manufacturer_id : 0,
                'pro_categories'     => $live->length_class_id != 0 ? $cat->category_id : 0,                
                'created_by'         => Auth::id(),
        ];

        $name=[
            'name' => $request->input('pro_name'),
        ];
        $qry = PendingApproval::create($data);
        if($qry){
            if ($request->pro_type=="Price"){
            $live = ProductLive::where('product_id', $id)->first();
            $historyprice = [
                        'id_pro_live'    => $live->product_id,
                        'harga'          => $live->price,
                        'sku'            => $live->sku,
                        'status'         => "Mengajukan Update Harga",
                        'created_by'     => Auth::id(),
            ];
            $qry1 = ProHargaHist::create($historyprice);
            }
            else if($request->pro_type=="Status"){
            $live = ProductLive::where('product_id', $id)->first();
                if($live->status==1)
                {
                    $status1="Active";
                }else
                {
                    $status1="Inactive";
                }
            $historystatus= [
                        'id_pro_live'    => $live->product_id,
                        'sku'            => $live->sku,
                        'status'         => $status1,
                        'status_App'     => "Mengajukan Update Status",
                        'created_by'     => Auth::id(),
            ]; 
            $qry1 = ProStatusHist::create($historystatus);
        }
        return redirect('product/live')->with('success', 'Succesfully, Data Send to Approval Page');   
        }
    }



    public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'model',
             1 => 'sku',
             2 => 'price',
             3 => 'name',
             4 => 'status',
             5 => 'product_id',
             6 => 'date_added'
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
         
         $menu_count    = ProductLive::select('*')->join('ocbz_product_description','ocbz_product.product_id','=','ocbz_product_description.product_id')->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
         if (empty($request->input('search')['value'])) {
             $posts = ProductLive::select('*')->join('ocbz_product_description','ocbz_product.product_id','=','ocbz_product_description.product_id')
            ->orderby($order, $dir)->offset($start)->limit($limit)->get(); 
         } else {
             $search        = $request->input('search')['value'];
             $posts         = ProductLive::select('*')->join('ocbz_product_description','ocbz_product.product_id','=','ocbz_product_description.product_id')
                 ->where('ocbz_product.sku', 'like', '%' . $search . '%')
                 ->orwhere('ocbz_product_description.name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
                 
             $totalFiltered = ProductLive::select('*')->join('ocbz_product_description','ocbz_product.product_id','=','ocbz_product_description.product_id')
                 ->where('ocbz_product.sku', 'like', '%' . $search . '%')
                 ->orwhere('ocbz_product_description.name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {

                 $data[] = [
                     'model'      => $post->model,
                     'sku'        => $post->sku,
                     'date_added' => Carbon::parse($post->date_added)->format('Y-m-d'),
                     'price'      => $this->price($post->price,$post->product_id),
                     'name'       => $post->name,
                     'status'     => $post->status,
                     'product_id' => $post->product_id,
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
        $hasil = number_format($angka, 2, ",", ".");
        return $hasil;
    }


        public function ajax_price(Request $request)
     {
         $columns = array(
             0 => 'created_at',
             1 => 'hrg_lama',
             2 => 'created_by',
             3 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
         
         $employee_count = ProHargaHist::all();
         $totalData     = $employee_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = ProHargaHist::select('*')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = ProHargaHist::where('emp_name', 'like', '%' . $search . '%')
                 ->orWhere('emp_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = ProHargaHist::where('emp_name', 'like', '%' . $search . '%')
                 ->orWhere('emp_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                     'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                     'hrg_lama'   => $this->price($post->hrg_lama,$post->id),
                     'created_by' => emp_name($post->created_by),
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

    public function Length()
    {

        $data = LiveLengthModel::where('language_id', '2')->get();
        $arr  = array();
        foreach ($data as $reg) {
        $arr[$reg->length_class_id] = $reg->title;
        }
        return $arr;
    }


}