<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Inventory\InventoryModel;
use App\Models\Inventory\InventoryPinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use PDF;
use DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventory.index');
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
        $data      = getProductDetail($id);
        $order     = InventoryModel::where('sku', $data->product_id)->get();
        $price     = InventoryModel::where('sku', $data->product_id)->orderBy('id', 'DESC')->first();
        $qty_po    = InventoryModel::where([['sku', $data->product_id], ['status', 'order']])->count();
        $sum_qty   = InventoryModel::where([['sku', $data->product_id], ['status', 'order']])->sum('qty');
        $pinjam    = InventoryModel::where([['sku', $data->product_id], ['status', 'pinjam']])->get();
        $pinjamdet = InventoryPinjam::where('sku', $id)->get();
        return view('inventory.show', [
            'data'      => $data,
            'qty_po'    => $qty_po,
            'sum_qty'   => $sum_qty,
            'sku'       => $id,
            'price'     => $price->price,
            'main'      => $order,
            'pinjam'    => $pinjam,
            'pinjamdet' => $pinjamdet,
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
            1 => 'id_purchase',
            2 => 'id_vendor',
            5 => 'jenis',
            3 => 'sku',
            4 => 'qty',
            6 => 'created_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = InventoryModel::where('status', 'order')->count();
        $totalData     = $menu_count;
        $totalFiltered = $menu_count;

        if (empty($request->input('search')['value'])) {
            $posts = InventoryModel::where('status', 'order')->groupBy('sku')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = InventoryModel::join('maleserc_ocar517.ocbz_product_description as a', 'inventory.sku', '=', 'a.product_id')
            ->where([
                ['status', 'order'],
            ])
            ->Where('product', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->groupBy('sku')->orderBy($order, $dir)->offset($start)->limit($limit)->get();
          
            $totalFiltered = InventoryModel::join('maleserc_ocar517.ocbz_product_description as a', 'inventory.sku', '=', 'a.product_id')
            ->where([
                ['status', 'order'],
            ])
            ->Where('product', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->groupBy('sku')->orderby($order, $dir)->offset($start)->limit($limit)->count();

        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $getpro = getProductPo($post->sku);
                $data[] = [
                    'id'         => $post->id,
                    'sku'        => $getpro->sku,
                    'barang'     => $getpro->name,
                    'qty'        => $this->ordertotal($post->sku),
                    'sisa'       => $this->checksisa($post->sku),
                    'jenis'      => $post->jenis,
                    'created_at' => $post->created_at->format('Y-m-d'),
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

    public function checksisa($prd_id)
    {
        $masuk  = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'order'],
        ])->sum('qty');
        $keluar = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'use'],
        ])->sum('qty');
        $pinjam = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'pinjam'],
        ])->sum('qty');

        return $masuk - $keluar - $pinjam;
    }

    public function ordertotal($prd_id)
    {
        $masuk  = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'order'],
        ])->sum('qty');
        return $masuk;
    }

    public function pinjam_stock(Request $request)
    {
        $data     = getProductDetail($request->sku);
        $qty      = InventoryModel::where('sku', $data->product_id)->where('status', 'order')->sum('qty');
        $qty_use  = InventoryModel::where('sku', $data->product_id)->where('status', 'use')->sum('qty');
        $qty_brw  = InventoryModel::where('sku', $data->product_id)->where('status', 'pinjam')->sum('qty');
        $order    = InventoryModel::where('sku', $data->product_id)->where('status', 'order')->orderBy('id', 'DESC')->first();
        $Sqty     = ($qty_brw + $qty_use);
        return view('inventory.attribute.inventory_pinjam', [
            'data'     => $request->sku,
            'id_sku'   => $data->product_id,
            'qty_sisa' => $qty - $Sqty,
            'price'    => $order->price,
            'method'   => "POST",
            'action'   => 'Warehouse\WarehouseController@store_pinjam',
        ]);
    }

    public function editpinjam_stock(Request $request)
    {
        $pinjamdet = InventoryPinjam::where('id', $request->id)->first();
        $data      = getProductDetail($pinjamdet->sku);
        $qty       = InventoryModel::where('sku', $data->product_id)->where('status', 'order')->sum('qty');
        $qty_use   = InventoryModel::where('sku', $data->product_id)->where('status', 'use')->sum('qty');
        $qty_brw   = InventoryModel::where('sku', $data->product_id)->where('status', 'pinjam')->sum('qty');
        $order     = InventoryModel::where('sku', $data->product_id)->where('status', 'order')->orderBy('id', 'DESC')->first();
        $Sqty      = ($qty_brw + $qty_use);
        return view('inventory.attribute.inventory_pinjam_edit', [
            'data'      => $pinjamdet->sku,
            'pinjamdet' => $pinjamdet,
            'id_sku'    => $data->product_id,
            'qty_sisa'  => $qty - $Sqty,
            'price'     => $order->price,
            'method'    => "POST",
            'action'    => 'Inventory\InventoryController@update_pinjam',
        ]);
    }

    public function store_pinjam(Request $request)
    {
        $data = [
            'sku'        => $request->no_sku,
            'qty'        => $request->qty,
            'price'      => $request->price,
            'note'       => $request->note,
            'jenis'      => "warehouse",
            'status'     => "pinjam",
            'created_by' => Auth::id(),
            'created_at' => Carbon::now(),
        ];
        $qry = InventoryModel::create($data);

        $pinjam = [
            'id_inventory'   => $qry->id,
            'id_customer'    => $request->id_customer,
            'alamat'         => $request->alamat_lain,
            'sku'            => $request->sku,
            'nama_peminjam'  => $request->nama_penerima,
            'qty_asli'       => $request->qty_tersedia,
            'qty_pinjam'     => $request->qty,
            'tanggal_pinjam' => $request->date,
            'note'           => $request->note,
            'created_by'     => Auth::id(),
            'created_at'     => Carbon::now(),
        ];
        $qry2 = InventoryPinjam::create($pinjam);

        return redirect('warehouse/inventory/' . $request->sku)->with('success', 'Pinjam Berhasil');
    }

    public function update_pinjam(Request $request)
    {

        $cust = CustomerModel::where('company', $request->id_customer)->first();
        $pinjam = [
            'id_customer'    => $cust->id,
            'alamat'         => $request->alamat_lain,
            'nama_peminjam'  => $request->nama_penerima,
            'qty_pinjam'     => $request->qty,
            'tanggal_pinjam' => $request->date,
            'note'           => $request->note,
            'updated_by'     => Auth::id(),
            'updated_at'     => Carbon::now(),
        ];
        $qry2 = InventoryPinjam::where('id',$request->id)->update($pinjam);
        return redirect('warehouse/inventory/' . $request->sku)->with('success', 'Update pinjam berhasil');
    }

    public function cetak_pinjam(Request $request)
    {
        
        $main  = InventoryPinjam::where('id', $request->id)->first();
        $no_do = "WH/OUT/PJM/".date('y',strtotime($main->tanggal_pinjam)).'/'.$main->id;
                            
        $pdf = PDF::loadview('pdf.warehouse_pinjam_delivery', [
            'main'      => $main,
            'no_do'     => $no_do,
        ]);

        return $pdf->stream('MEG - DO.pdf');
    }
}
