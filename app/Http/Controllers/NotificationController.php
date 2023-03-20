<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationReplacement;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\Customer_pic;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use App\Models\Activity\ActQuoModel;
use App\Models\Activity\ActLoginModel;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_model;
use App\Models\Inventory\InventoryModel;
use App\Models\WarehouseUpdate\WarehouseOut;
use App\Models\WarehouseUpdate\WarehouseOutDetail;
use App\Models\WarehouseUpdate\WarehouseSN;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Storage;
use DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

    public function countpublic(Request $request)
    {

        $id      = $request->id;
        $last    = session()->all();
        // dd($last);
        // $getdata = $last['auth']['password_confirmed_at'];
        // $check   = ActLoginModel::where([
        //     ['token',$getdata],
        //     ['created_by',Auth::id()]
        // ])->first();
        // if( $check==null){
        //     $datas   = [
        //         'token'      => $getdata,
        //         'ip'         => \Request::ip(),
        //         'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
        //         'created_by' => Auth::id(),
        //     ];
        //     ActLoginModel::create($datas);
        // }
        $main = [
            'pendingrfq' => $this->pendingrfq($id),
            'negosiasi'  => $this->negosiasi($id),
            'invoicing'  => $this->invoicing($id),
            'unpaid'     => $this->unpaid($id),
            'waitmodal'  => $this->waitmodal($id),
            'waitout'    => $this->waitout($id),
            'nodoc'      => $this->nodoc($id),
        ];
        return view('layouts.notif', [
            'main'     => $main,
        ]);
    }

    public function pendingrfq($id)
    {
        $count = QuotationModel::where('quo_type', '1')
            ->WhereNull('quo_ekskondisi')->count();
        $all = QuotationModel::where('quo_type', '1')->count();
        $data = [
            'jumlah'  => $count . '/' . $all,
            'comment' => "Unfinish RFQ",
            'icon'    => "far fa-calendar-minus",
            'link'    => url("sales/quotation"),
        ];
        return $data;
    }

    public function negosiasi($id)
    {
        $count = QuotationModel::whereNull('quo_instatus')
            ->whereNull('d.id')
            ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
            ->where([
                ['quo_type', '>', '1'],
                ['quo_eksposisi', 'not like', '%distri%'],
                ['quo_ekskondisi', '<>', 'Batal']
            ])
            ->count();
        $data = [
            'jumlah'  => $count,
            'comment' => "SO Masih Nego",
            'link'    => url("sales/quotation"),
        ];
        return $data;
    }

    public function invoicing($id)
    {
        $count = QuotationModel::WhereNull('ket_lunas')
            ->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')
            ->count();
            $data = [
            'jumlah'  => $count,
            'comment' => "Invoicing",
            'link'    => url("sales/quotation"),
        ];
        return $data;
    }

    public function waitmodal($id)
    {
        $count = QuotationProduct::WhereNull('det_quo_harga_modal')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_product.id_quo')
            ->groupBy('quotation_product.id_quo')
            ->get();
        $data = [
            'jumlah'  => count($count),
            'comment' => "HARGA MODAL KOSONG",
            'link'    => url("finance/invoice"),
        ];
        return $data;
    }

    public function unpaid($id)
    {
        $count = DB::select(DB::raw("SELECT * from (SELECT tgl_invoice, quo_ekskondisi, ket_lunas, quo_approve_status from quotation_models JOIN quotation_invoice on quotation_invoice.id_quo = quotation_models.id where quo_ekskondisi = 'Masih Negosiasi' or ISNULL(quo_ekskondisi)) as subquery WHERE ISNULL(ket_lunas) and quo_approve_status != 'reject'"));
        // dd(count($count), array_sum($count));
        $all  = QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->WhereNull('type')->count();
        
        $data = [
            'jumlah'  => count($count).'/'.$all,
            'comment' => "SO Unpaid",
            'link'    => url("finance/invoice"),
        ];
        return $data;
    }

    public function waitout($id)
    {
        $count = WarehouseOut::leftJoin('warehouse_outbound_detail as d', 'd.id_outbound', '=', 'warehouse_outbound.id')
            ->whereNull('d.id')
            ->get();
        $all    = WarehouseOut::count();
        $kurang = $all - count($count);
        $data   = [
            'jumlah'  => count($count) . '/' . $all,
            'comment' => "MENUNGGU PENGIRIMAN",
            'link'    => url("sales/quotation"),
        ];
        return $data;
    }

    public function nodoc($id)
    {
        $count = QuotationDocument::where('q.quo_type', '>', '1')
            ->where('q.quo_ekskondisi', '<>', 'Batal')
            ->whereNull('quotation_document.doc_po')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_document.id_quo')
            ->count();
        $all = QuotationDocument::where('q.quo_type', '>', '1')
            ->where('q.quo_ekskondisi', '<>', 'Batal')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_document.id_quo')
            ->count();
        $data = [
            'jumlah'  => $count . '/' . $all,
            'comment' => "File PO blm diupload",
            'link'    => url("sales/quotation"),
        ];
        return $data;
    }




    /////////////////////////DETAIL///////////////


    public function DetailInfo(Request $request)
    {
        // dd($request);
        $id_user = $request->id_user;
        $div     = $request->div;
        $type    = $request->type;

        if ($type == "pendingrfq") {
            return $this->detail_pendingrfq($type);
        } else if ($type == "nego") {
            return $this->detail_negosiasi($type);
        } else if ($type == "unpaid") {
            return $this->detail_unpaid($type);
        } else if ($type == "nodoc") {
            return $this->detail_dokumenSO($type);
        } else if ($type == "waitmodal") {
            return $this->detail_waitmodal($type);
        } else if ($type == "waitout") {
            return $this->detail_waitout($type);
        }
    }



    public function ajax_detailinfo(Request $request)
    {
        // dd($request);
        if ($request->what == "pendingrfq") {
            return $this->ajax_detail_pendingrfq($request);
        } else if ($request->what == "nego") {
            return $this->ajax_detailNegosiasi($request);
        } else if ($request->what == "unpaid") {
            return $this->ajax_detailUnpaid($request);
        } else if ($request->what == "nodoc") {
            return $this->ajax_detailDokumenSO($request);
        } else if ($request->what == "waitmodal") {
            return $this->ajax_detailwaitmodal($request);
        } else if ($request->what == "waitout") {
            return $this->ajax_detailwaitout($request);
        }
    }



    public function detail_pendingrfq($type)
    {
        $datas = QuotationModel::where('quo_type', '1')
            ->WhereNull('quo_ekskondisi')->get();
        // dd($datas);
        return view('layouts.modal_notif', [
            'datas' => $datas,
            'type'  => $type,
        ]);
    }


    public function ajax_detail_pendingrfq($request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'qty',
            4 => 'id_customer',
            5 => 'id_sales',
            6 => 'created_by',
            7 => 'quo_order_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::where('quo_type', '1')->WhereNull('quo_ekskondisi')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::where('quo_type', '1')->WhereNull('quo_ekskondisi')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::where('quo_type', '1')->WhereNull('quo_ekskondisi')->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationModel::where('quo_type', '1')->WhereNull('quo_ekskondisi')->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'           => $post->id,
                    'id_quo'       => 'SO' . sprintf("%06d", $post->id),
                    'quo_no'       => $post->quo_no,
                    'quo_type'     => getQuoType($post->quo_type)->type_name,
                    'quo_name'     => $post->quo_name,
                    'quo_color'    => getQuoType($post->quo_type)->color,
                    'id_customer'  => getCustomer($post->id_customer)->company,
                    'id_sales'     => getEmp($post->id_sales)->emp_name,
                    'created_by'   => getEmp($post->created_by)->emp_name,
                    'quo_order_at' => $post->quo_order_at,
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


    public function detail_negosiasi($type)
    {
        return view('layouts.modal_notif', [
            'type'  => $type,
        ]);
    }


    public function ajax_detailNegosiasi($request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'qty',
            4 => 'id_customer',
            5 => 'id_sales',
            6 => 'created_by',
            7 => 'quo_order_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::whereNull('quo_instatus')
            ->whereNull('d.id')
            ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
            ->where([
                ['quo_type', '>', '1'],
                ['quo_eksposisi', 'not like', '%distri%'],
                ['quo_ekskondisi', '<>', 'Batal']
            ])->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::select('quotation_models.*')
                ->whereNull('quo_instatus')
                ->whereNull('d.id')
                ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
                ->where([
                    ['quo_type', '>', '1'],
                    ['quo_eksposisi', 'not like', '%distri%'],
                    ['quo_ekskondisi', '<>', 'Batal']
                ])
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::select('quotation_models.*')
                ->whereNull('quo_instatus')
                ->whereNull('d.id')
                ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
                ->where([
                    ['quo_type', '>', '1'],
                    ['quo_eksposisi', 'not like', '%distri%'],
                    ['quo_ekskondisi', '<>', 'Batal']
                ])->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationModel::select('quotation_models.*')
                ->whereNull('quo_instatus')
                ->whereNull('d.id')
                ->leftJoin('warehouse_outbound as d', 'd.id_quo', '=', 'quotation_models.id')
                ->where([
                    ['quo_type', '>', '1'],
                    ['quo_eksposisi', 'not like', '%distri%'],
                    ['quo_ekskondisi', '<>', 'Batal']
                ])->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'           => $post->id,
                    'id_quo'       => 'SO' . sprintf("%06d", $post->id),
                    'quo_no'       => $post->quo_no,
                    'quo_type'     => getQuoType($post->quo_type)->type_name,
                    'quo_name'     => $post->quo_name,
                    'quo_color'    => getQuoType($post->quo_type)->color,
                    'id_customer'  => getCustomer($post->id_customer)->company,
                    'id_sales'     => getEmp($post->id_sales)->emp_name,
                    'created_by'   => getEmp($post->created_by)->emp_name,
                    'quo_order_at' => $post->quo_order_at,
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


    public function detail_unpaid($type)
    {
        $count = QuotationModel::select('*','quotation_invoice.tgl_invoice', 'quotation_invoice.no_invoice', 'quotation_invoice.id_quo','quotation_models.created_by', 'quotation_models.id')->whereNull('ket_lunas')->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->where([['quo_approve_status', '<>', 'reject'], ['quo_ekskondisi', '<>', 'Batal']])->get();
        // dd($datas);
        return view('layouts.modal_notif', [
            'datas' => $count,
            'type'  => $type,
        ]);
    }


    public function ajax_detailUnpaid($request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'id_quo',
            2 => 'tgl_invoice',
            3 => 'created_by',
            4 => 'note',
            5 => 'no_invoice',
            6 => 'quo_no',
            7 => 'ket_lunas',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationModel::whereNull('ket_lunas')->WhereNull('type')->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = QuotationModel::whereNull('ket_lunas')->WhereNull('type')->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')
                ->orderby('quotation_invoice.id_quo', $dir)->offset($start)->limit($limit)->get();
            // dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationModel::whereNull('ket_lunas')->WhereNull('type')->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('quotation_invoice.id_quo', $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationModel::whereNull('ket_lunas')->WhereNull('type')->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('quotation_invoice.id_quo', $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $check = $post->quo_ekskondisi!='Batal' ? 'allow' : 'no';
                $check2= $post->quo_approve_status!='reject' ? 'allow' : 'no';
                if($check=='allow' && $check2 == 'allow')
                {
                    $getemp   = getUserEmp($request->id_user)->division_id;
                    $newid    = $getemp == '3' ? $post->id : $post->id_quo;
                    $link     = $getemp == '3' ? 'finance/invoice/edit_invoice/' . $post->id : 'sales/quotation/' . $post->id_quo;
                    
                    $data[] = [
                        'no_invoice'  => $post->no_invoice,
                        'id_quo'      => "SO" . sprintf("%06d", $post->id_quo),
                        'tgl_invoice' => $post->tgl_invoice,
                        'note'        => $post->note,
                        'created_by'  => user_name($post->created_by),
                        'id'          => $newid,
                        'link'        => $link,
                        'quo_no'      => $post->quo_no,
                    ];
                }
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => count($data),
            "recordsFiltered" => count($data),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    public function detail_dokumenSO($type)
    {
        $count = QuotationDocument::where('q.quo_type', '>', '1')
            ->where('q.quo_ekskondisi', '<>', 'Batal')
            ->whereNull('quotation_document.doc_bast')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_document.id_quo')
            ->get();
        // dd($datas);
        return view('layouts.modal_notif', [
            'datas' => $count,
            'type'  => $type,
        ]);
    }


    public function ajax_detailDokumenSO($request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'qty',
            4 => 'id_customer',
            5 => 'id_sales',
            6 => 'created_by',
            7 => 'quo_order_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationDocument::where('q.quo_type', '>', '1')
            ->where('q.quo_ekskondisi', '<>', 'Batal')
            ->whereNull('quotation_document.doc_po')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_document.id_quo')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = QuotationDocument::where('q.quo_type', '>', '1')
                ->where('q.quo_ekskondisi', '<>', 'Batal')
                ->whereNull('quotation_document.doc_po')
                ->join('quotation_models as q', 'q.id', '=', 'quotation_document.id_quo')
                ->orderby('q.id', $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationDocument::WhereNull('ket_lunas')
                ->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('q.id', $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationDocument::WhereNull('ket_lunas')
                ->join('quotation_invoice', 'quotation_invoice.id_quo', '=', 'quotation_models.id')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('q.id', $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'           => $post->id,
                    'id_quo'       => 'SO' . sprintf("%06d", $post->id),
                    'quo_no'       => $post->quo_no,
                    'quo_type'     => getQuoType($post->quo_type)->type_name,
                    'quo_name'     => $post->quo_name,
                    'quo_color'    => getQuoType($post->quo_type)->color,
                    'id_customer'  => getCustomer($post->id_customer)->company,
                    'id_sales'     => getEmp($post->id_sales)->emp_name,
                    'created_by'   => getEmp($post->created_by)->emp_name,
                    'quo_order_at' => $post->quo_order_at,
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

    public function detail_waitmodal($type)
    {
        $count = QuotationProduct::WhereNull('det_quo_harga_modal')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_product.id_quo')
            ->groupBy('id_quo')->get();
        // dd($datas);
        return view('layouts.modal_notif', [
            'datas' => $count,
            'type'  => $type,
        ]);
    }

    public function ajax_detailwaitmodal($request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'qty',
            4 => 'id_customer',
            5 => 'id_sales',
            6 => 'created_by',
            7 => 'quo_order_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = QuotationProduct::WhereNull('det_quo_harga_modal')
            ->join('quotation_models as q', 'q.id', '=', 'quotation_product.id_quo')
            ->groupBy('quotation_product.id_quo')->get();
        $totalData     = count($menu_count);
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = QuotationProduct::WhereNull('det_quo_harga_modal')
                ->join('quotation_models as q', 'q.id', '=', 'quotation_product.id_quo')
                ->groupBy('quotation_product.id_quo')
                ->orderby('q.id', $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = QuotationProduct::WhereNull('det_quo_harga_modal')
                ->join('quotation_models as q', 'q.id', '=', 'quotation_product.id_quo')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->groupBy('id_quo')
                ->orderby('q.id', $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = QuotationProduct::WhereNull('det_quo_harga_modal')
                ->join('quotation_models as q', 'q.id', '=', 'quotation_product.id_quo')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->groupBy('id_quo')
                ->orderby('q.id', $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'id'           => $post->id,
                    'id_quo'       => 'SO' . sprintf("%06d", $post->id),
                    'quo_no'       => $post->quo_no,
                    'quo_type'     => getQuoType($post->quo_type)->type_name,
                    'quo_name'     => $post->quo_name,
                    'quo_color'    => getQuoType($post->quo_type)->color,
                    'id_customer'  => getCustomer($post->id_customer)->company,
                    'id_sales'     => getEmp($post->id_sales)->emp_name,
                    'created_by'   => getEmp($post->created_by)->emp_name,
                    'quo_order_at' => $post->quo_order_at,
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

    public function detail_waitout($type)
    {
        // dd($datas);
        return view('layouts.modal_notif', [
            'type'  => $type,
        ]);
    }

    public function ajax_detailwaitout($request)
    {
        // dd($request);
        $columns = array(
            0 => 'id',
            1 => 'quo_no',
            2 => 'quo_name',
            3 => 'qty',
            4 => 'id_customer',
            5 => 'id_sales',
            6 => 'created_by',
            7 => 'quo_order_at',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = WarehouseOut::leftJoin('warehouse_outbound_detail as d', 'd.id_outbound', '=', 'warehouse_outbound.id')
            ->whereNull('d.id')
            ->get();
        $totalData     = count($menu_count);
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = WarehouseOut::select('*', 'warehouse_outbound.id as newid')
                ->leftJoin('warehouse_outbound_detail as d', 'd.id_outbound', '=', 'warehouse_outbound.id')
                ->whereNull('d.id')
                ->orderby('warehouse_outbound.id', $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = WarehouseOut::select('*', 'warehouse_outbound.id as newid')
                ->leftJoin('warehouse_outbound_detail as d', 'd.id_outbound', '=', 'warehouse_outbound.id')
                ->whereNull('d.id')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('warehouse_outbound.id', $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = WarehouseOut::select('*', 'warehouse_outbound.id as newid')
                ->leftJoin('warehouse_outbound_detail as d', 'd.id_outbound', '=', 'warehouse_outbound.id')
                ->whereNull('d.id')
                ->where('quo_no', 'like', '%' . $search . '%')
                ->orWhere('quo_name', 'like', '%' . $search . '%')
                ->orderby('warehouse_outbound.id', $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $getemp   = getUserEmp($request->id_user)->division_id;
                $newid    = $getemp == '5' ? $post->newid : $post->id_quo;
                $link     = $getemp == '5' ? 'warehouse/warehouse_outbound/' . $post->newid : 'sales/quotation/' . $post->id_quo;
                $get_quo  = getQuo($post->id_quo);
                $quo_type = getQuoType($get_quo->quo_type);
                $data[]         = [
                    'id'           => $newid,
                    'link'         => $link,
                    'id_quo'       => 'SO' . sprintf("%06d", $post->id_quo),
                    'quo_no'       => $post->quo_no,
                    'quo_type'     => $quo_type->type_name,
                    'quo_name'     => $get_quo->quo_name,
                    'quo_color'    => $quo_type->color,
                    'id_customer'  => getCustomer($get_quo->id_customer)->company,
                    'id_sales'     => getEmp($get_quo->id_sales)->emp_name,
                    'created_by'   => getEmp($get_quo->created_by)->emp_name,
                    'quo_order_at' => $post->created_at,
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
