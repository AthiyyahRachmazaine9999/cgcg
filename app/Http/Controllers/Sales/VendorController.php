<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\VendorModel;
use App\Models\Sales\Vendor_pic;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\PurchaseFinanceModel;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Purchasing\PurchaseHistory;
use App\Models\Purchasing\PurchaseFinanceBank;
use App\Models\Finance\Pay_VoucherModel;
use App\Models\Finance\Pay_VoucherDetail;
use App\Models\Finance\Pay_VoucherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use Storage;
use DB;



class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales.vendor.create', [
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'country'  => $this->get_kecamatan(),
            'method'   => "post",
            'action'   => 'Sales\VendorController@store',
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

    public function save($request, $save, $id = 0)
    {
        // dd($request);
        $data = [
            'vendor_name' => $request->input('vendor_name'),
            'phone'       => $request->input('phone'),
            'fax'         => $request->input('fax'),
            'email'       => $request->input('email'),
            'province'    => $request->input('province'),
            'city'        => $request->input('city'),
            'country'     => $request->input('country'),
            'address'     => $request->input('address'),
            $save . '_by'       => Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? VendorModel::create($data) : VendorModel::where('id', $id)->update($data);
        // dd($qry);
        if ($qry) {
            $datapic = [
                'vendor_id' => $qry->id,
                'name'      => $request->input('name'),
                'position'  => $request->input('position'),
                'pic_phone' => $request->input('pic_phone'),
                'pic_email' => $request->input('pic_email'),
                $save . '_by'     => Auth::id()
            ];
            // dd($datapic);
            $qry1 = $save == 'created' ? Vendor_pic::create($datapic) : Vendor_pic::where('vendor_id', $id)->update($datapic);
            // dd($qry1);
            $redto = $request->has('other') ? 'success' : redirect('sales/vendor')->with('success', ucwords($request->input('vendor_name')) . ' Vendor Data' . $save . ' successfully');
            return $redto;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = VendorModel::where('id', $id)->first();
        // dd($data);
        $pic = Vendor_pic::where('vendor_id', $id)->get();
        // dd($pic);
        return view('sales.vendor.show', [
            'data' => $data,
            'pic' => $pic,
            'method' => 'post',
            'action' => 'Sales\VendorController@storepic'
        ]);
    }

    public function storepic(Request $request)
    {
        // dd($request);
        $datapic = [
            'vendor_id'  => $request->input('vendor_id'),
            'name'       => $request->input('name'),
            'position'   => $request->input('position'),
            'pic_phone'  => $request->input('pic_phone'),
            'pic_email'  => $request->input('pic_email'),
            'created_by' => Auth::id()
        ];
        vendor_pic::insert($datapic);
        return redirect('sales/vendor/' . $request->input('vendor_id'))->with('success', ucwords($request->input('name')) . ' Vendor PIC Add successfully');
    }


    public function listPo(Request $request)
    {
        // dd($request);
        $jumlah_po = Purchase_order::where('id_vendor', $request->id_vendor)->get();
        return view('sales.vendor.list_po', [
            'data'   => $jumlah_po,
            'id_vendor' => $request->id_vendor,
        ]);
    }

    public function ajax_listPo(Request $request)
    {
        // dd($request, $request->id_vendor);
        $columns = array(
            0 => 'po_number',
            1 => 'type_payment',
            2 => 'price',
            3 => 'id',
        );


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = Purchase_detail::select('*', DB::raw('SUM(purchase_detail.qty*purchase_detail.price) as total'))->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_detail.id_po')->where([['purchase_orders.id_vendor', $request->id_vendor],['status', '!=', 'reject']])->groupBy('id_po')->get();
        // dd($menu_count);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = Purchase_detail::select('*', DB::raw('SUM(purchase_detail.qty*purchase_detail.price) as total'))->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_detail.id_po')->where([['purchase_orders.id_vendor', $request->id_vendor],['status', '!=', 'reject']])
                         ->groupBy('id_po')->offset($start)->limit($limit)->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = Purchase_detail::select('*', DB::raw('SUM(purchase_detail.qty*purchase_detail.price) as total'))->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_detail.id_po')->where([['purchase_orders.id_vendor', $request->id_vendor],['status', '!=', 'reject']])
                            ->where('po_number', 'like', '%' . $search . '%')
                            ->groupBy('id_po')->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = Purchase_detail::select('*', DB::raw('SUM(purchase_detail.qty*purchase_detail.price) as total'))->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_detail.id_po')->where([['purchase_orders.id_vendor', $request->id_vendor],['status', '!=', 'reject']])
                            ->where('po_number', 'like', '%' . $search . '%')
                            ->groupBy('id_po')->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $pay_detail = Pay_VoucherDetail::where('no_po', $post->po_number)->first();
                $vat        = $post->total*(GetPPN($post->created_at,$post->created_at)/100);
                $data[] = [
                    'nomer_po'     => $post->po_number,
                    'tipe_payment' => $pay_detail == null ? '-' : strtoupper($pay_detail->type_payment),
                    'total'        => number_format($post->total + $vat),
                    'created_at'   => Carbon::parse($post->kirim_time)->format('d F Y'),
                    'id'           => $post->id,
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



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = VendorModel::where('id', $id)->first();
        $pic  = Vendor_pic::where('vendor_id', $id)->get();
        // dd($pic);
        return view('sales.vendor.edit', [
            'data'     => $data,
            'pic'      => $pic,
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'ccity'    => [$data->city => city($data->city)],
            'country'  => $this->get_kecamatan(),
            'ccountry' => [$data->country => country($data->country)],
            'method'   => 'put',
            'action'   => ['Sales\VendorController@update', $id],
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
        $data = [
            'vendor_name' => $request->input('vendor_name'),
            'phone'       => $request->input('phone'),
            'fax'         => $request->input('fax'),
            'email'       => $request->input('email'),
            'province'    => $request->input('province'),
            'city'        => $request->input('city'),
            'country'     => $request->input('country'),
            'address'     => $request->input('address'),
            'updated_by'  => Auth::id()
        ];
        // dd($data);
        VendorModel::where('id', $id)->update($data);

        if ($request->has('id_pic')) {
            $ids = $request->input('name');
            foreach ($ids as $item => $v) {
                $datapic = [
                    'vendor_id'  => $id,
                    'name'       => $request->input('name')[$item],
                    'position'   => $request->input('position')[$item],
                    'pic_phone'  => $request->input('pic_phone')[$item],
                    'pic_email'  => $request->input('pic_email')[$item],
                    'updated_by' => Auth::id()
                ];
                // dd($datapic);
                Vendor_pic::where('vendor_id', $id)->update($datapic);
            }
            
        }
        // dd($qry1);
        $redto = redirect('sales/vendor')->with('success', ucwords($request->input('vendor_name')) . ' Vendor Data Updated successfully');
        return $redto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = 'DELETE vendor_company, vendor_pic
        FROM vendor_company JOIN vendor_pic ON vendor_pic.vendor_id = vendor_company.id
        WHERE vendor_company.id = ?';
        DB::delete($query, array($id));

        return redirect()->route('vendor.index')
            ->with('success', 'Data deleted successfully');
    }

    public function deletepic(Request $request)
    {
        // dd($request);
        $id        = $request->id_pic;
        $redto     = 'sales/vendor/' . $request->id_vend;
        $deletepic = Vendor_pic::findOrFail($id);
        $deletepic->delete();

        return $redto;
    }

    public function get_province()
    {

        $data = Provinsi::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }

    public function get_city()
    {

        $data = Kota::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->kota);
        }
        return $arr;
    }


    public function get_Kecamatan()
    {

        $data = Kecamatan::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }


    public function find_vendor(Request $request)
    {

        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = VendorModel::select("id", "vendor_name")
                ->where('vendor_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function modal_call(Request $request)
    {

        return view('sales.vendor.form', [
            'province' => $this->get_province(),
            'city'     => [],
            'country'  => [],
            'method'   => "post",
        ]);
    }


    public function modal_detail(Request $request)
    {
        $id   = $request->id_vendor;
        $data = VendorModel::where('id', $id)->first();
        $pic  = Vendor_pic::where('vendor_id', $id)->first();
        return view('sales.vendor.show_modal', [
            'data'   => $data,
            'pic'    => $pic,
            'ids'    => $id,
        ]);
    }


public function export_normal(Request $request)
{
    // dd($request);
    $vendor     = $request->segment(4);
    $start      = $request->segment(5);
    $end        = $request->segment(6);

    $query = VendorModel::filterexport($vendor,$start,$end)->groupBy('id_po')->get();
    // dd($query);
    
    $j=1;
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:K1')->getFont()->setBold(TRUE);
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

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Vendor');
        $sheet->setCellValue('C1', 'Phone');
        $sheet->setCellValue('D1', 'Address');
        $sheet->setCellValue('E1', 'No. SO');
        $sheet->setCellValue('F1', 'No. PO');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Total Harga');
        $sheet->setCellValue('I1', 'Tanggal PO');
        $sheet->setCellValue('J1', 'Created by');
        $sheet->setCellValue('K1', 'Created at');
        $rows = 2;
        
        
    foreach($query as $qp)
    {
        $vat = $qp['total']*(GetPPN($qp['created_at'],$qp['created_at'])/100);
        
        $sheet->setCellValue('A' . $rows, $j++); 
        $sheet->setCellValue('B' . $rows, getVendor($qp['id_vendor'])->vendor_name);  
        $sheet->setCellValue('C' . $rows, $qp['phone']=='N' ? '' : $qp['phone']);  
        $sheet->setCellValue('D' . $rows, $qp['address']);  
        $sheet->setCellValue('E' . $rows, 'SO' . sprintf("%06d", $qp['id_quo']));  
        $sheet->setCellValue('F' . $rows, $qp['po_number']); 
        $sheet->setCellValue('G' . $rows, $qp['status']); 
        $sheet->setCellValue('H' . $rows, $qp['total'] + $vat); 
        $sheet->setCellValue('I' . $rows, Carbon::parse($qp['kirim_time'])->format('Y-m-d'));
        $sheet->setCellValue('J' . $rows, user_name($qp['created_by']));
        $sheet->setCellValue('K' . $rows, Carbon::parse($qp['created_at'])->format('Y-m-d'));
        $rows++;  
    }
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('Vendor.xlsx');
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Vendor.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
}


    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'vendor_name',
            1 => 'email',
            2 => 'address',
            3 => 'created_at',
            4 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = VendorModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = VendorModel::select('*')->offset($start)->limit($limit)
                ->orderby('id', $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = VendorModel::where('vendor_name', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orderby('id', $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = VendorModel::where('vendor_name', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orderby('id', $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $jumlah_po = Purchase_order::where([['id_vendor', $post->id], ['status','!=', 'reject']])->get();
                $data[] = [
                    'vendor_name' => $post->vendor_name,
                    'email'       => $post->email,
                    'jumlah_po'   => count($jumlah_po). " PO",
                    'address'     => $post->address,
                    'created_at'  => $post->created_at->format('Y-m-d'),
                    'id'          => $post->id,
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
