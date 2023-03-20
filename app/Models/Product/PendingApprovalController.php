<?php


namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ListContent;
use App\Models\Product\PendingApproval;
use App\Models\Product\ProPriceModel;
use App\Models\Product\ProductLive;
use App\Models\Product\LiveProDescModel;
use App\Models\Product\LiveManModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use DB;

class PendingApprovalController extends Controller
{
    public function index(){
        return view ('Product.PendingApproval.index');
    }

    public function ajax_data(Request $request)
     {
         $columns = array(
                0 => 'pro_manufacture',
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
 
         $menu_count    = PendingApproval::where('pro_status','Pending')->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = PendingApproval::where('pro_status','Pending')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = PendingApproval::where('pro_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = PendingApproval::where('pro_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                    'pro_manufacture'    => Manufacture($post->pro_manufacture),
                    'pro_price'          => $this->price($post->pro_price, $post->pro_id),
                    'pro_active'         => $post->pro_active,
                    'pro_name'           => $post->pro_name,
                    'pro_type'           => $post->pro_type,
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

 public function destroy($id)
    {
        $App = PendingApproval::findOrFail($id);
        $App->delete();
      
        return redirect()->route('waiting.index')
        ->with('success', 'Data deleted successfully');
      
    }

    public function show($pro_id)
    { 
        $app=PendingApproval::where('pro_id',$pro_id)->first();
        $status=PendingApproval::where('pro_status', $app->pro_status)->first();
        $data=$status->pro_status;
        if ($data=="Approved") {
            // dd($status);
            $info = array(
                "BtnApp" => "disabled",
                "BtnRej"=>"",
            );
        } elseif ($data=="Reject") {
            //   dd($status);
            $info = array(
                "BtnApp" => "",
                "BtnRej"=>"disabled",
            );
        } else {
            $info = array(
                "BtnApp" => "",
                "BtnRej"=>"",
            );
        }
        $app  = PendingApproval::where('pro_id', $pro_id)->first();
        return view ('Product.PendingApproval.show',[
            'app' => $app,
            'info'=> $info,
            'we'   => Weight($app->weight_class_id),
            'cat'  => Category($app->pro_categories),
            'man'  => Manufacture($app->pro_manufacture),

        ]);
        // return redirect()->back(); 
    }

   public function Reject(Request $request, $id)
{   
    $getId=$request->pro_id;
    $data= PendingApproval::where('pro_id',$id)->first();
    $data1= ListContent::where('pro_id',$id)->first();
        $app=[
            'pro_status'=>"Reject",
        ];
        $qry= PendingApproval::where('pro_sku',$data->pro_sku)->update($app);
        if($qry){
            $data= PendingApproval::where('pro_id',$id)->first();
       // dd($data1);
            $list=[
                'pro_status'=>"Reject",
            ];
        $qry1= ListContent::where('pro_sku',$data->pro_sku)->update($list);
        }
        return redirect()->back(); 
}


public function approve(PendingApproval $approval, $id)
{
    $app= PendingApproval::where('pro_id', $id)->first();
    $type=$app->pro_type;
    if($type=="Status")
    {
        return $this->ApprovalStatus($approval, $id)->with('Successfully');

    }elseif($type=="price"){

       return $this->ApprovalPrice($approval, $id)->with('Successfully');

    }else{

    return $this->ApprovalContent($approval, $id)->with('Successfully');

    }

}


public function ApprovalStatus ($approval,$id)
{
    $app=PendingApproval::where('pro_id', $id)->first();
    // $app1=$app->pro_sku;
    $data =[

        'status'  => $app->pro_active,
    ];
    $qry =ProductLive::where('sku', $app->pro_sku)->update($data);
    if($qry)
        {
        $data= PendingApproval::where('pro_id',$id)->first();
        $app=[
            'pro_status'=>"Approved",
        ]; 
        $qry2= PendingApproval::where('pro_id',$data->pro_id)->update($app);
    } 
    if($qry2)
    {
        $app= PendingApproval::where('pro_id', $id)->first();
        $data = [
            'sku'        => $app->pro_sku,
            'status'     => $app->pro_active,
            'Created_by' => Auth::id()
        ];
        $qry3=ProStatusHist::create($data);
    }
    return redirect()->back(); 
}

public function ApprovalPrice ($live, $id)
{
    $app=PendingApproval::where('pro_id', $id)->first();
    $app= PendingApproval::where('pro_id', $id)->first();
    $live=ProductLive::where('sku', $app->pro_sku)->first();
    $hrg= $live->price;
    $live=[
        'price'  => $app->pro_price,
    ];
    $harga=[
            'sku'        => $app->pro_sku,
            'hrg_lama'   => $hrg,
    ];
    $qry =ProductLive::where('sku', $app->pro_sku)->update($live);
    $qry1=ProHargaHist::create($harga);
    if($qry)
    {
        $data= PendingApproval::where('pro_id',$id)->first();
        $app=[
            'pro_status'=>"Approved",
        ]; 
        $qry2= PendingApproval::where('pro_status',$data->pro_status)->update($app);
    } if($qry2)
    {   
        $app= PendingApproval::where('pro_id', $id)->first();
        $data = [
            'hrg_baru'   => $app->pro_price,
            'Created_by' => Auth::id()
        ];
        $qry3=ProHargaHist::where('sku', $app->pro_sku)->update($data);
    }

    return redirect()->back(); 
}


public function ApprovalContent($approval, $id)
{ 
    // dd($approval);
        $data= PendingApproval::where('pro_id',$id)->first();
        $arr_dm=explode(',' , $data->pro_dimesion);
        $sku= "SKU".$data->pro_categories.$data->pro_manufacture.sprintf("%06d",$data->pro_id);
        $active=PendingApproval::where('pro_active', $data->pro_active)->first();
        $act1=$active->pro_active;
        if($act1=='Active'){
            $act1 = 1;
        }else{
            $act1 = 0;
        }
        $data=[
                       'model'           => Manufacture($data->pro_manufacture),
                       'sku'             => $sku,
                       'manufacturer_id' => $data->pro_manufacture,
                       'price'           => $data->pro_price,
                       'image'           => $data->pro_image,
                       'weight'          => $data->pro_weight,
                       'weight_class_id' => $data->weight_class_id,
                       'date_available'  => $data->pro_berlaku_sampai,
                       'status'          => $act1,
                       'length'          => $arr_dm[0],
                       'width'           => $arr_dm[1],
                       'height'          => $arr_dm[2],
        ];
        // dd($data);
        $qry =ProductLive::create($data);
        if($qry){
        $app=PendingApproval::where('pro_id',$id)->first();
        $data = [
                        'product_id'  => $qry->id,
                        'name'        => $app->pro_name,
                        'description' => $app->pro_spec,
        ];
        $data2=[
                    'id_product'         => $qry->sku,
                    'det_quo_harga_live' => $app->pro_price
                ];
        $qry1 =LiveProDescModel::create($data);
        $qry2= QuotationProduct::where('id_product_request', $app->pro_request_id)->update($data2);
        }if($qry2)
            {
                $data= PendingApproval::where('pro_id',$id)->first();
                $app=[
                    'pro_status'=>"Approved",
                ];
                $qry3= PendingApproval::where('pro_id',$data->pro_id)->update($app);
                $qry4= Listcontent::where('pro_name', $data->pro_name)->update($app);
        } return redirect()->back(); 
}


   public function Button($id)
   {
        $id=PendingApproval::where('pro_id',$id)->first();
        $sku=PendingApproval::where('pro_sku', $id->pro_sku)->first();
        $cek_status = PendingApproval::where(['pro_sku' => $sku])
                            ->get()
                            ->first();
        if ($cek_status=="Reject") {
            $info = array(
                "BtnApp" => "",
                "BtnRej"=>"disabled",
            );
        } elseif ($cek_status=="Approved") {
            $info = array(
                "BtnApp" => "disabled",
                "BtnRej"=>"",
            );
        } else {
            $info = array(
                "BtnApp" => "disabled",
                "BtnRej"=>"disabled",
            );
        }
       // dd($data_absen);
        return view('Product.PendingApproval.show', compact('info'));
    }
   
        public function price ($angka) {
        $hasil = number_format($angka, 2, ",", ".");
        return $hasil;
    }


}
