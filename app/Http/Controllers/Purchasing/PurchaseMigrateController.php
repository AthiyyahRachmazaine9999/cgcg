<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\Customer_pic;
use App\Models\Role\Role_cabang;
use App\Models\HR\EmployeeModel;
use App\Models\Activity\ActQuoModel;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Purchasing\PurchaseMigrateDetail;
use App\Models\Purchasing\PurchaseMigrate;
use App\Models\Purchasing\Purchase_address;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PurchaseMigrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchasing.datamigrate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        $main       = PurchaseMigrate::where('po_number', $id)->first();
        $vend       = VendorModel::where('id', $main->id_vendor)->first();
        $altaddress = Purchase_address::where('id_po', $main->id)->first();
        $product    = PurchaseMigrateDetail::where('id_po', $main->id)->get();
        return view('purchasing.datamigrate.show', [
            'main'     => $main,
            'product'  => $product,
            'vend'     => $vend,
            'alamat'   => $altaddress,
            'vend_pic' => Vendor_pic::where('vendor_id', $vend->id)->get(),
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
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'po_number',
            2 => 'id_quo',
            3 => 'id_vendor',
            4 => 'status',
            5 => 'price',
            6 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = PurchaseMigrate::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = PurchaseMigrate::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            if (substr($search, 0, 2) == 'SO' || substr($search, 0, 2) == 'so') {
                $search   = ltrim(substr($search, 2), '0');
            } else {
                $search  = $search;
            }
            $posts         = PurchaseMigrate::where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = PurchaseMigrate::where('po_number', 'like', '%' . $search . '%')
                ->orWhere('id_quo', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $data[] = [
                    'id'         => $post->id,
                    'po_number'  => $post->po_number,
                    'id_quo'     => $post->id_quo,
                    'id_vendor'  => getVendor($post->id_vendor)->vendor_name,
                    'price'      => getSumPObackup($post->id),
                    'created_at' => $post->created_at->format('Y-m-d'),
                    'status'     => $post->status,
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
