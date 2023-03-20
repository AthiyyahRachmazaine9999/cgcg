<?php


namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ListContent;
use App\Models\Product\PendingApproval;
use App\Models\Product\ProPriceModel;
use App\Models\Product\ProductLive;
use App\Models\Product\LiveProToCat;
use App\Models\Product\LiveProImage;
use App\Models\Product\LiveProToStore;
use App\Models\Product\LiveProToLayout;
use App\Models\Product\LiveProAtribut;
use App\Models\Product\LiveProDescModel;
use App\Models\Product\LiveManModel;
use App\Models\Activity\ActQuoModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;


class PendingApprovalController extends Controller
{
    public function index()
    {
        return view('Product.PendingApproval.index');
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'pro_manufacture',
            1 => 'pro_name',
            2 => 'pro_type',
            3 => 'pro_price',
            4 => 'pro_active',
            5 => 'pro_berlaku_sampai',
            6 => 'created_by',
            7 => 'pro_id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = PendingApproval::where('pro_status', '!=', 'Reject')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = PendingApproval::where('pro_status', '!=', 'Reject')
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
                    'pro_price'          => $post->pro_price,
                    'pro_active'         => $post->pro_active,
                    'pro_name'           => $post->pro_name,
                    'pro_type'           => $post->pro_type,
                    'pro_berlaku_sampai' => $post->pro_berlaku_sampai,
                    'pro_categories'     => $post->pro_categories,
                    'created_by'         => getUserEmp($post->created_by)->name,
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

        return redirect()->route('approval.index')
            ->with('success', 'Data deleted successfully');
    }

    public function show($pro_id)
    {
        $app     = PendingApproval::where('pro_id', $pro_id)->first();
        $status  = PendingApproval::where('pro_status', $app->pro_status)->first();
        $histhrg = ProHargaHist::where('id_pro', $pro_id)->first();
        $data    = $status->pro_status;
        if ($data == "Approved") {
            $info = array(
                "BtnApp" => "disabled",
                "BtnRej" => "",
            );
        } elseif ($data == "Reject") {
            //   dd($status);
            $info = array(
                "BtnApp" => "",
                "BtnRej" => "disabled",
            );
        } else {
            $info = array(
                "BtnApp" => "",
                "BtnRej" => "",
            );
        }
        if ($app->length_unit == null) {
            $length = "";
            $arr_dm = "0-0-0";
        } else if ($app->length_unit == null && $app->pro_dimesion == null) {
            $length = "";
            $arr_dm = "0-0-0";
        } else {
            $length = LengthUnit($app->length_unit)->title;
            $arr_dm = explode('x', $app->pro_dimesion);
        }

        return view('Product.PendingApproval.show', [
            'arr'    => $arr_dm,
            'app'    => $app,
            'info'   => $info,
            'length' => $length,
            'harga'  => ProHargaHist($app->pro_sku),
            'we'     => Weight($app->weight_class_id),
            'cat'    => Category($app->pro_categories),
            'man'    => Manufacture($app->pro_manufacture),

        ]);
        // return redirect()->back(); 
    }

    public function Reject(Request $request, $id)
    {
        // dd($request);
        $app = PendingApproval::where('pro_id', $id)->first();
        $type = $app->pro_type;
        // dd($type);
        if ($type == "Status") {
            return $this->RejectStatus($request, $id)->with('Reject Successfully');
        } elseif ($type == "Price") {

            return $this->RejectPrice($request, $id)->with('Reject Successfully');
        } elseif ($type == "Spesifikasi") {
            return $this->RejectSpek($request, $id)->with('Reject Successfully');
        } else if ($app->id_quo != null) {

            return $this->RejectProSO($request, $id)->with('Reject Successfully');
        } else {

            return $this->RejectContent($request, $id)->with('Reject Successfully');
        }
    }


    public function approve(Request $request, $id)
    {
        // dd($id);
        $app = PendingApproval::where('pro_id', $id)->first();
        $type = $app->pro_type;
        // dd($type);
        if ($type == "") {
            return $this->ApprovalSpek($request, $id)->with('Successfully');
        } else if ($app->pro_request_id != null) {

            return $this->ApprovalProSO($request, $id)->with('Successfully');
        } else {

            return $this->ApprovalContent($request, $id)->with('Successfully');
        }
    }


    public function ApprovalStatus($approval, $id)
    {
        // dd($id);
        $app = PendingApproval::where('pro_id', $id)->first();
        $live = ProductLive::where('sku', $app->pro_sku)->first();
        if ($app->pro_active == "Inactive") {
            $status = 0;
        } else {
            $status = 1;
        }
        $data = [

            'status'  => $status,
            'date_modified' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $desc = [
            'name' => $app->pro_name,
        ];
        $desc = LiveProDescModel::where('product_id', $live->product_id)->update($desc);
        $qry = ProductLive::where('sku', $app->pro_sku)->update($data);
        if ($qry) {
            $app = PendingApproval::where('pro_id', $id)->first();
            $data = [
                'sku'        => $app->pro_sku,
                'status'     => $app->pro_active,
                'status_App' => "Update Status Approved",
                'Created_by' => Auth::id()
            ];
            $qry3 = ProStatusHist::create($data);
        }
        if ($qry3) {
            $data = PendingApproval::where('pro_id', $id)->first();
            $app = [
                'pro_status' => "Approved",
            ];
            $qry4 = PendingApproval::where('pro_id', $data->pro_id)->update($app);
        }
        return redirect()->route('approval.index')
            ->with('success', 'Change Product Status Successfully Approved');
    }


    public function ApprovalPrice($live, $id)
    {
        $app = PendingApproval::where('pro_id', $id)->first();
        $live = ProductLive::where('sku', $app->pro_sku)->first();
        $hrg = $live->price;
        $live = [
            'price'  => $app->pro_price,
            'date_modified' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $harga = [
            'sku'        => $app->pro_sku,
            'harga'      => $app->pro_price,
            'status'     => "Update Price Approved",
            'created_by' => Auth::id(),
        ];
        $qry = ProductLive::where('sku', $app->pro_sku)->update($live);
        $qry1 = ProHargaHist::create($harga);
        if ($qry1) {
            $data = PendingApproval::where('pro_id', $id)->first();
            $app = [
                'pro_status' => "Approved",
            ];
            $qry2 = PendingApproval::where('pro_id', $data->pro_id)->update($app);
        }
        if ($qry2) {
            return redirect()->route('approval.index')
                ->with('success', 'Change Product Price Successfully Approved');
        }
    }


    public function ApprovalSpek($request, $id)
    {
        //    dd($id);
        $data   = PendingApproval::where('pro_id', $id)->first();
        $qry = ProductLive::where('sku', $data->pro_sku)->first();
        // dd($data, $qry);
        $spek = [
            'product_id'    => $data->live_id,
            'attribute_id'  => 347,
            'language_id'   => 2,
            'text'          => $data->pro_spec,
        ];
        $qry = LiveProAtribut::where('product_id', $data->live_id)->update($spek);
        if ($qry) {
            $data = PendingApproval::where('pro_id', $id)->first();
            $app = [
                'pro_status' => "Approved",
            ];
            $qry2 = PendingApproval::where('pro_id', $data->pro_id)->update($app);
        }
        return redirect()->route('approval.index')
            ->with('success', 'Change Product Spesifikasi Successfully Approved');
    }


    public function RejectSpek($request, $id)
    {
        // dd($id, $request);
        $app = [
            'pro_status' => "Reject",
        ];
        $qry = PendingApproval::where('pro_id', $id)->update($app);
        if ($qry) {
            return redirect()->route('approval.index')
                ->with('success', 'Rejected Succesfully');
        }
    }

    public function ApprovalContent($request, $id)
    {
        $data   = PendingApproval::where('pro_id', $id)->first();
        $arr_dm = explode('x', $data->pro_dimesion);
        $sku    = "SKU" . $data->pro_categories . $data->pro_manufacture . sprintf("%06d", $data->pro_id);
        $active = PendingApproval::where('pro_active', $data->pro_active)->first();
        $act1   = $active->pro_active;
        if ($act1 == 'Active') {
            $act1 = 1;
        } else {
            $act1 = 0;
        }
        if ($data->pro_berlaku_sampai == null) {
            $av = null;
        } else {
            $av = $data->pro_berlaku_sampai;
        }
        // $arr_img= explode('/' , $app->pro_image);
        $arr_img = $data->pro_image;
        // dd($arr_img);
        $data = [
            'model'           => Manufacture($data->pro_manufacture),
            'sku'             => $sku,
            'manufacturer_id' => $data->pro_manufacture,
            'price'           => $data->pro_price,
            'image'           => $data->pro_image == null ? null : "catalog/PRIORITAS/" . $arr_img,
            'weight'          => $data->pro_weight,
            'weight_class_id' => $data->weight_class_id,
            'date_available'  => $av,
            'status'          => $act1,
            'length'          => $arr_dm[0],
            'width'           => $arr_dm[1],
            'height'          => $arr_dm[2],
            'tax_class_id'    => 11,
            'stock_status_id' => 7,
            'length_class_id' => LengthUnit($data->length_unit)->length_class_id,
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
        $qry = ProductLive::create($data);
        if ($qry) {
            $que = $qry->product_id;
            $app = PendingApproval::where('pro_id', $id)->first();
            // $arr_img= explode('/' , $app->pro_image);
            $arr_img = $app->pro_image;
            $data = [
                'product_id'             => $que,
                'name'                   => $app->pro_name,
                'overview'               => $app->pro_desc,
                'description'            => "",
                'language_id'            => 2,
                'fimage'                 => "",
                'video1'                 => "",
                'html_product_shortdesc' => "",
                'html_product_right'     => "",
                'html_product_tab'       => "",
                'tab_title'              => "",
                'meta_title'             => $app->pro_name,
                'meta_description'       => "",
                'meta_keyword'           => "",
                'tag'                    => LiveCat($app->pro_categories)->name,

            ];
            $cat = [
                'product_id' => $que,
                'category_id' => $app->pro_categories,
            ];
            $liveImg = [
                'product_id' => $que,
                'image' => "",
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
                'product_id' => $que,
                'attribute_id' => 347,
                'language_id' => 2,
                'text'       => $app->pro_spec,
            ];
            $historyprice = [
                'id_pro'         => $app->pro_id,
                'id_pro_live'    => $que,
                'pro_request_id' => $app->pro_request_id,
                'id_quo'         => $app->id_quo,
                'harga'          => $app->pro_price,
                'sku'            => $qry->sku,
                'status'         => "Approved New Product",
                'created_by'     => Auth::id(),
            ];
            $historystatus = [
                'id_pro'         => $app->pro_id,
                'id_pro_live'    => $que,
                'pro_request_id' => $app->pro_request_id,
                'id_quo'         => $app->id_quo,
                'sku'            => $sku,
                'status'         => $app->pro_active,
                'status_App'     => "Approved New Product",
                'created_by'     => Auth::id(),
            ];
            $qry1      = LiveProDescModel::create($data);
            $tblImage  = LiveProImage::create($liveImg);
            $tblStore  = LiveProToStore::create($liveStore);
            $tblLayout = LiveProToLayout::create($liveLayout);
            $tblAtr    = LiveProAtribut::create($liveAtr);
            $qry9      = LiveProToCat::create($cat);
            $qry3      = ProHargaHist::create($historyprice);
            $qry4      = ProStatusHist::create($historystatus);
        }
        if ($qry4) {
            $data = PendingApproval::where('pro_id', $id)->first();
            $app  = [
                'pro_status' => "Approved",
                'pro_sku'    => $qry->sku,
                'update_by' => Auth::id(),
            ];
            $qry5 = PendingApproval::where('pro_id', $data->pro_id)->update($app);
            $qry6 = Listcontent::where('pro_name', $data->pro_name)->update($app);
        }
        return redirect()->route('approval.index')
            ->with('success', 'New Data Successfully Approved');
    }

    public function ApprovalProSO($request, $id)
    {
        $data   = PendingApproval::where('pro_id', $id)->first();
        $arr_dm = explode('x', $data->pro_dimesion);
        $sku    = "SKU" . $data->pro_categories . $data->pro_manufacture . sprintf("%06d", $data->pro_id);
        $active = PendingApproval::where('pro_active', $data->pro_active)->first();
        $act1   = $active->pro_active;
        if ($act1 == 'Active') {
            $act1 = 1;
        } else {
            $act1 = 0;
        }

        if ($data->pro_berlaku_sampai == null) {
            $av = null;
        } else {
            $av = $data->pro_berlaku_sampai;
        }
        // $arr_img= explode('/' , $data->pro_image);
        $arr_img = $data->pro_image;
        $data = [
            'model'           => Manufacture($data->pro_manufacture),
            'sku'             => $sku,
            'manufacturer_id' => $data->pro_manufacture,
            'price'           => $data->pro_price,
            'image'           => $data->pro_image == null ? null : "catalog/PRIORITAS/" . $arr_img,
            'weight'          => $data->pro_weight,
            'weight_class_id' => $data->weight_class_id,
            'date_available'  => $av,
            'status'          => $act1,
            'length'          => $arr_dm[0],
            'width'           => $arr_dm[1],
            'height'          => $arr_dm[2],
            'tax_class_id'    => 11,
            'stock_status_id' => 7,
            'length_class_id' => LengthUnit($data->length_unit)->length_class_id,
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
            $que = $qry->product_id;
            $app = PendingApproval::where('pro_id', $id)->first();
            // $arr_img= explode('/' , $app->pro_image);
            $arr_img = $app->pro_image;
            $data = [
                'product_id'             => $que,
                'name'                   => $app->pro_name,
                'overview'               => $app->pro_desc,
                'description'            => "",
                'language_id'            => 2,
                'fimage'                 => "",
                'video1'                 => "",
                'html_product_shortdesc' => "",
                'html_product_right'     => "",
                'html_product_tab'       => "",
                'tab_title'              => "",
                'meta_title'             => $app->pro_name,
                'meta_description'       => "",
                'meta_keyword'           => "",
                "tag"                    => LiveCat($app->pro_categories)->name,
            ];
            $cat = [
                'product_id' => $que,
                'category_id' => $app->pro_categories,
            ];
            $liveImg = [
                'product_id' => $que,
                'image' => "",
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
                'product_id' => $que,
                'attribute_id' => 347,
                'language_id' => 2,
                'text'       => $app->pro_spec,
            ];
            $data2 = [
                'id_product'         => $qry->sku,
                'det_quo_harga_live' => $app->pro_price,
            ];
            $historyprice = [
                'id_pro'         => $app->pro_id,
                'id_pro_live'    => $que,
                'pro_request_id' => $app->pro_request_id,
                'id_quo'         => $app->id_quo,
                'harga'          => $app->pro_price,
                'status'         => "Approved New Product",
                'sku'            => $qry->sku,
                'created_by'     => Auth::id(),
            ];
            $historystatus = [
                'id_pro'         => $app->pro_id,
                'id_pro_live'    => $que,
                'pro_request_id' => $app->pro_request_id,
                'id_quo'         => $app->id_quo,
                'sku'            => $sku,
                'status_App'     => "Approved New Product",
                'status'         => $app->pro_active,
                'created_by'     => Auth::id(),
            ];
            $qry1      = LiveProDescModel::create($data);
            $tblImage  = LiveProImage::create($liveImg);
            $tblStore  = LiveProToStore::create($liveStore);
            $tblLayout = LiveProToLayout::create($liveLayout);
            $tblAtr    = LiveProAtribut::create($liveAtr);
            $qry9      = LiveProToCat::create($cat);
            $qry2      = QuotationProduct::where('id_product_request', $app->pro_request_id)->update($data2);
            $qry3      = ProHargaHist::create($historyprice);
            $qry4      = ProStatusHist::create($historystatus);
        }
        if ($qry4) {
            $data = PendingApproval::where('pro_id', $id)->first();
            $app  = [
                'pro_status' => "Approved",
                'pro_sku'    => $qry->sku,
                'update_by' => Auth::id(),
            ];
            $qry5 = PendingApproval::where('pro_id', $data->pro_id)->update($app);
            $qry6 = Listcontent::where('pro_name', $data->pro_name)->update($app);
        }
        if ($qry6) {
            $app  = PendingApproval::where('pro_id', $id)->first();
            $data = [
                'activity_id_quo'       => $data->id_quo,
                'activity_name'         => "Product " . $app->pro_name . " telah disetujui & Sudah Tayang dengan SKU : " . $sku,
                'activity_id_user'      => Auth::id(),
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $qry7 = ActQuoModel::insert($data);
        }
        return redirect()->route('approval.index')
            ->with('success', 'New Data Successfully Approved');
    }


    public function RejectContent($request, $id)
    {
        $app = [
            'pro_status' => "Reject",
        ];
        $qry = PendingApproval::where('pro_id', $id)->update($app);
        if ($qry) {
            $app = PendingApproval::where('pro_id', $id)->first();
            $list = [
                'pro_status' => "Reject",
            ];
            $qry1 = ListContent::where('pro_id', $app->id_prolist)->update($list);
        }
        return redirect()->route('approval.index')
            ->with('success', 'Rejected Succesfully');
    }

    public function RejectPrice($request, $id)
    {
        $app = [
            'pro_status' => "Reject",
        ];
        $qry = PendingApproval::where('pro_id', $id)->update($app);
        if ($qry) {
            $app = PendingApproval::where('pro_id', $id)->first();
            $harga = [
                'sku'        => $app->pro_sku,
                'harga'      => $app->pro_price,
                'status'     => "Reject",
                'created_by' => Auth::id(),
            ];
            $qry1 = ProHargaHist::create($harga);
        }
        return redirect()->route('approval.index')
            ->with('success', 'Rejected Succesfully');
    }

    public function RejectStatus($request, $id)
    {
        $app = [
            'pro_status' => "Reject",
        ];
        $qry = PendingApproval::where('pro_id', $id)->update($app);
        if ($qry) {
            $app = PendingApproval::where('pro_id', $id)->first();
            $status = [
                'sku'        => $app->pro_sku,
                'status'     => $app->pro_active,
                'status_App' => "Reject",
                'created_by' => Auth::id(),
            ];
            $qry1 = ProStatusHist::create($status);
        }
        return redirect()->route('approval.index')
            ->with('success', 'Rejected Succesfully');
    }

    public function RejectProSO($request, $id)
    {
        $app = [
            'pro_status' => "Reject",
        ];
        $qry = PendingApproval::where('pro_id', $id)->update($app);
        if ($qry) {
            $app = PendingApproval::where('pro_id', $id)->first();
            $list = [
                'pro_status' => "Reject",
            ];
            $data = [
                'activity_id_quo'       => $app->id_quo,
                'activity_name'         => "Product " . $app->pro_name . " " . $app->pro_status,
                'activity_id_user'      => Auth::id(),
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $qry1 = ListContent::where('pro_id', $app->id_prolist)->update($list);
            $qry2 = ActQuoModel::insert($data);
            // dd($qry1, $qry2, $data);
        }
        if ($qry2) {
            return redirect()->route('approval.index')
                ->with('success', 'Rejected Succesfully');
        }
    }

    public function saveReject($id)
    {
    }


    public function Button($id)
    {
        $id = PendingApproval::where('pro_id', $id)->first();
        $sku = PendingApproval::where('pro_sku', $id->pro_sku)->first();
        $cek_status = PendingApproval::where(['pro_sku' => $sku])
            ->get()
            ->first();
        if ($cek_status == "Reject") {
            $info = array(
                "BtnApp" => "",
                "BtnRej" => "disabled",
            );
        } elseif ($cek_status == "Approved") {
            $info = array(
                "BtnApp" => "disabled",
                "BtnRej" => "",
            );
        } else {
            $info = array(
                "BtnApp" => "disabled",
                "BtnRej" => "disabled",
            );
        }
        // dd($data_absen);
        return view('Product.PendingApproval.show', compact('info'));
    }

    public function price($angka)
    {
        $hasil = number_format($angka, 2, ",", ".");
        return $hasil;
    }
}
