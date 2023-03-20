<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationReplacement;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationInvoiceDetail;
use App\Models\Sales\QuotationInvoicePayment;
use App\Models\Sales\QuotationInvoiceOthers;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\VisitModel;
use App\Models\Sales\VisitHistory;
use App\Models\Sales\CustomerModel;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use DB;
use PDF;
use Storage;



class VisitPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return view('sales.visit_plan.index',[]);
    }


    public function find_lokasi(Request $request)
    {
        // dd($request);
        $data = [];
        if ($request->has('q')) 
        {
            $cari        = "&q=". $request->q . '&format=json';
            $locationiq  = 'https://us1.locationiq.com/v1/search?key=' . getConfig('locationIQ') . $cari;
            $json_string = $locationiq;
            $curl        = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $json_string,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_RETURNTRANSFER => true,
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            $get      = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return response()->json($get);
        }
    }


    public function ListVisit(Request $request)
    {
        $usr  = Auth::id();
        $mine = getUserEmp(Auth::id());
        $team = getTeam($mine->id_emp);

        if(in_array($mine->id,explode(',',getConfig('list_manage'))))
        {
            $list = VisitModel::all();
            foreach ($list as $qry) {
                if($qry->status == "Open Plan"){
                    $color = "#303488";
                }else{
                    $color = "#2B9B6B";
                }
                
                $text = user_name($qry->created_by);
                $arr[] = array(
                    'id'    => $qry->id,
                    'title' => explode(' ',$text)[0].' '.$qry->aktivitas." - ".Carbon::parse($qry->time_start)->format('H:i:s').' s/d '.Carbon::parse($qry->time_end)->format('H:i:s').'. Instansi: '.$qry->id_customer,
                    'start' => Carbon::parse($qry->date)->format('Y-m-d').' '.Carbon::parse($qry->time_start)->format('H:i:s'),
                    'color' => $color,
                );
                $result = $arr;
            }
        }elseif(count($team)>0){
            $coma = implode (", ", $team);
            $list = VisitModel::whereRaw("created_by IN ($usr,$coma)")->get();
            foreach ($list as $qry) {
                if($qry->status == "Open Plan"){
                    $color = "#303488";
                }else{
                    $color = "#2B9B6B";
                }
                
                $text = user_name($qry->created_by);
                $arr[] = array(
                    'id'    => $qry->id,
                    'title' => explode(' ',$text)[0].' '.$qry->aktivitas." - ".Carbon::parse($qry->time_start)->format('H:i:s').' s/d '.Carbon::parse($qry->time_end)->format('H:i:s').'. Instansi: '.$qry->id_customer,
                    'start' => Carbon::parse($qry->date)->format('Y-m-d').' '.Carbon::parse($qry->time_start)->format('H:i:s'),
                    'color' => $color,
                );
                $result = $arr;
            }
        }else{
            $list = VisitModel::where('created_by', $usr)->get();
            foreach ($list as $qry) {
                if($qry->status == "Open Plan"){
                    $color = "#303488";
                }else{
                    $color = "#2B9B6B";
                }
                
                $arr[] = array(
                    'id'    => $qry->id,
                    'title' => $qry->aktivitas." - ".Carbon::parse($qry->time_start)->format('H:i:s').' s/d '.Carbon::parse($qry->time_end)->format('H:i:s').'. Instansi: '.$qry->id_customer,
                    'start' => Carbon::parse($qry->date)->format('Y-m-d').' '.Carbon::parse($qry->time_start)->format('H:i:s'),
                    'color' => $color,
                );
                $result = $arr;
            }
        }
        return $result;

    }


    public function list_visit(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $usr  = Auth::id();
    if(in_array($mine->id,explode(',',getConfig('list_manage'))))
    {
        return $this->list_visitManagement($request);
    }else{
        $columns = array(
            0 => 'id',
            1 => 'aktivitas',
            2 => 'meeting_point',
            3 => 'date',
            4 => 'status',
            5 => 'created_by',
            6 => 'created_at',
            7 => 'id_customer',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = VisitModel::where('created_by', $usr)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = VisitModel::select('*')->where('created_by', $usr)->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = VisitModel::where('created_by', $usr)->where('id', 'like', '%' . $search . '%')
                ->orWhere('aktivitas', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = VisitModel::where('created_by', $usr)->where('id', 'like', '%' . $search . '%')
                ->orWhere('aktivitas', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'tujuan'        => $post->aktivitas,
                    'status'        => $post->status,
                    'customer'      => getCustomer($post->id_customer)->company,
                    'created_at'    => Carbon::parse($post->created_at)->format('d F Y'),
                    'created_by'    => getUserEmp(Auth::id())->emp_name,
                    'date'          => Carbon::parse($post->date)->format('d F Y'),
                    'meeting_point' => $post->meeting_point,
                    'jam'           => Carbon::parse($post->time_start)->format('H:i:s') .' '.Carbon::parse($post->time_end)->format('H:i:s'),
                    'id'            => $post->id,
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


    public function list_visitManagement($request)
    {
        $columns = array(
            0 => 'id',
            1 => 'aktivitas',
            2 => 'meeting_point',
            3 => 'date',
            4 => 'status',
            5 => 'created_by',
            6 => 'created_at',
            7 => 'id_customer',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = VisitModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = VisitModel::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = VisitModel::where('id', 'like', '%' . $search . '%')
                ->orWhere('aktivitas', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = VisitModel::where('id', 'like', '%' . $search . '%')
                ->orWhere('aktivitas', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'tujuan'        => $post->aktivitas,
                    'status'        => $post->status,
                    'customer'      => getCustomer($post->id_customer)->company,
                    'created_at'    => Carbon::parse($post->created_at)->format('d F Y'),
                    'created_by'    => getUserEmp(Auth::id())->emp_name,
                    'date'          => Carbon::parse($post->date)->format('d F Y'),
                    'meeting_point' => $post->meeting_point,
                    'jam'           => Carbon::parse($post->time_start)->format('H:i:s') .' '.Carbon::parse($post->time_end)->format('H:i:s'),
                    'id'            => $post->id,
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



    
    public function filter_visit(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'aktivitas',
            2 => 'meeting_point',
            3 => 'date',
            4 => 'status',
            5 => 'created_by',
            6 => 'created_at',
            7 => 'id_customer',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];


        $st       = $request->segment(3);
        $s_date   = $request->segment(4);
        $end_date = $request->segment(5);
        $sales    = $request->segment(6);
        $customer = $request->segment(7);

        $menu_count    = VisitModel::filtersearch($st, $customer, $sales, $s_date, $end_date);
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $search        = $request->input('search')['value'];
            $posts = VisitModel::filtersearchlimit($st, $customer, $sales, $s_date, $end_date, $start, $limit, $order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = VisitModel::filtersearchfind($st, $customer, $sales, $s_date, $end_date, $start, $limit, $order, $dir, $search)->get();
            $totalFiltered = count(VisitModel::filtersearchfind($st, $customer, $sales, $s_date, $end_date, $start, $limit, $order, $dir, $search)->get());
        }

         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'tujuan'        => $post->aktivitas,
                    'status'        => $post->status,
                    'customer'      => getCustomer($post->id_customer)->company,
                    'created_at'    => Carbon::parse($post->created_at)->format('d F Y'),
                    'created_by'    => getUserEmp(Auth::id())->emp_name,
                    'date'          => Carbon::parse($post->date)->format('d F Y'),
                    'meeting_point' => $post->meeting_point,
                    'jam'           => Carbon::parse($post->time_start)->format('H:i:s') .' '.Carbon::parse($post->time_end)->format('H:i:s'),
                    'id'            => $post->id,
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


    public function ex_visit(Request $request)
    {
        $st       = $request->segment(3);
        $s_date   = $request->segment(4);
        $end_date = $request->segment(5);
        $sales    = $request->segment(6);
        $customer = $request->segment(7);

        if ($st == "kosong" && $s_date=="kosong" && $end_date=="kosong" && $sales=="kosong" && $customer=="kosong") {
            $query = VisitModel::select('*')->get();
        } else {
            $query = VisitModel::filterexport($st, $customer, $sales, $s_date, $end_date)->get();
        }
        $j = 1;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:P1')->getFont()->setBold(TRUE);
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
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(25);
        

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Aktivitas');
        $sheet->setCellValue('C1', 'Customer');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Nomer');
        $sheet->setCellValue('F1', 'Email');
        $sheet->setCellValue('G1', 'Jabatan');
        $sheet->setCellValue('H1', 'Tanggal Meeting');
        $sheet->setCellValue('I1', 'Meeting Point');
        $sheet->setCellValue('J1', 'Detail Meeting Point');
        $sheet->setCellValue('K1', 'Forecast Value');
        $sheet->setCellValue('L1', 'Dari Jam');
        $sheet->setCellValue('M1', 'Sampai Jam');
        $sheet->setCellValue('N1', 'Status');
        $sheet->setCellValue('O1', 'Tanggal Buat');
        $sheet->setCellValue('P1', 'Dibuat Oleh');
        $rows = 2;
        foreach ($query as $qp) {
            $sheet->setCellValue('A' . $rows, $j++);
            $sheet->setCellValue('B' . $rows, $qp['aktivitas']);
            $sheet->setCellValue('C' . $rows, getCustomer($qp['id_customer'])->company);
            $sheet->setCellValue('D' . $rows, $qp['nama_cp']);
            $sheet->setCellValue('E' . $rows, $qp['nomer_hp']);
            $sheet->setCellValue('F' . $rows, $qp['email']);
            $sheet->setCellValue('G' . $rows, $qp['jabatan']);
            $sheet->setCellValue('H' . $rows, Carbon::parse($qp['date'])->format('d F Y'));
            $sheet->setCellValue('I' . $rows, $qp['meeting_point']);
            $sheet->setCellValue('J' . $rows, $qp['detail_meeting_point']);
            $sheet->setCellValue('K' . $rows, $qp['forecast_value']);
            $sheet->setCellValue('L' . $rows, Carbon::parse($qp['time_start'])->format('H:i:s'));
            $sheet->setCellValue('M' . $rows, Carbon::parse($qp['time_end'])->format('H:i:s'));
            $sheet->setCellValue('N' . $rows, $qp['status']);
            $sheet->setCellValue('O' . $rows, Carbon::parse($qp['created_at'])->format('d F Y'));
            $sheet->setCellValue('P' . $rows, user_name($qp['created_by']));
            $rows++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('Visit Plan.xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="VisitPlan.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
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


    public function vp_create(Request $request)
    {
        return view('sales.visit_plan.form',[
            'date'   => $request->time,
        ]);
    }


    public function vp_edit(Request $request)
    {
        $user = getUserEmp(Auth::id());
        if(in_array($user->id,explode(',',getConfig('list_manage'))))
        {
            $visit = VisitModel::where('id', $request->id)->first();
            return view('sales.visit_plan.advice_form',[
                'visit'  => $visit,
                'method'   => "post",
                'action'   => "Sales\VisitPlanController@save_advice"
            ]);
        }else{
            $visit = VisitModel::where('id', $request->id)->first();
            return view('sales.visit_plan.edit_form',[
                'visit'  => $visit,
                'cust'   => $this->getCustomer(),
            ]);
        }
    }


    public function vp_show(Request $request)
    {
        // dd($request);
        $visit = VisitModel::where('id', $request->id)->first();
        return view('sales.visit_plan.show_form',[
            'visit'  => $visit,
            'user'   => getUserEmp(Auth::id()),
        ]);
    }



    public function delete($id)
    {
        $visit = VisitModel::find($id);
        $visit->delete();
        return redirect('sales/visitplan')->with('success', 'Delete Schedule Successfully');
    }


    public function getCustomer()
    {
        $data= CustomerModel::all();
        $arr = array();
        foreach ($data as $reg) 
        {
            $arr[$reg->id] = $reg->company;
        }
        return $arr;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function save_advice(Request $request)
    {
        $visit = VisitModel::where('id', $request->id)->first();
        $data = [
            'advice'    => $request->advice,
            'advice_by' => Auth::id(),
            'advice_at' => Carbon::now('GMT+7')->toDateTimeString(),
        ];
        $qry = VisitModel::where('id', $request->id)->update($data);

        $history = [
            'id_visit'   => $visit->id,
            'activity'   => "Menambahkan advice/suggestion",
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
        ];
        $qry_hist = VisitHistory::create($history);
        return redirect('sales/visitplan')->with('success', 'Created Successfully');
    }
     
    public function store(Request $request)
    {
        $data = [
            'aktivitas'            => $request->aktivitas,
            'id_customer'          => $request->id_customer,
            'meeting_point'        => $request->meeting_point,
            'detail_meeting_point' => $request->detail,
            'forecast_value'       => $request->for_value,
            'time_start'           => date("G:i", strtotime($request->start_time)),
            'time_end'             => date("G:i", strtotime($request->end_time)),
            'status'               => $request->status,
            'nama_cp'              => $request->nama_cp,
            'nomer_hp'             => $request->nomer_hp,
            'jabatan'              => $request->jabatan,
            'email'                => $request->email,
            'created_by'           => Auth::id(),
            'created_at'           => Carbon::now('GMT+7')->toDateTimeString(),
            'date'                 => Carbon::parse($request->date)->format('Y-m-d'),
        ];
        $qry = VisitModel::create($data);

        $history = [
            'id_visit'   => $qry->id,
            'activity'   => "Menambahkan jadwal visit plan dengan status ". $request->status,
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
        ];
        $qry_hist = VisitHistory::create($history);
        return redirect('sales/visitplan')->with('success', 'Created Successfully');
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


    public function page_download()
    {
        return view('sales.visit_plan.page_download',[
            'user'     => getUserEmp(Auth::id()),
            'status'   => $this->get_quoStatus(),
            'sales'    => getEmpSelect('division_id', '9'),
        ]);
    }

    


    public function get_quoStatus()
    {
        $data = QuotationStatus::where('status_type', 'status')->get();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = ucfirst($reg->status_name);
        }
        return $arr;
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
    public function saveUpdate(Request $request)
    {
        $visit = VisitModel::where('id', $request->id)->first();
        // dd($request, $visit);
        $update     = $request->date == $visit->date ? " ": ", Merubah Tanggal dari ".$visit->date." Menjadi ".$request->date;
        $status     = $request->status == $visit->status ? " ": ", Merubah Status dari ".$visit->status." Menjadi ".$request->status;
        $location   = $request->meeting_point == $visit->meeting_point ? " ": ", Merubah Meeting Point dari".$visit->meeting_point." Menjadi ".$request->meeting_point;
        $history = [
            'id_visit'   => $request->id,
            'activity'   => "Mengubah Data Jadwal Visit".$update.$status.$location,
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
        ];
        $qry_hist = VisitHistory::create($history);
        
        $data = [
            'aktivitas'            => $request->aktivitas,
            'id_customer'          => $request->id_customer,
            'meeting_point'        => $request->meeting_point,
            'detail_meeting_point' => $request->detail,
            'forecast_value'       => $request->for_value,
            'time_start'           => date("G:i", strtotime($request->start_time)),
            'time_end'             => date("G:i", strtotime($request->end_time)),
            'status'               => $request->status,
            'nama_cp'              => $request->nama_cp,
            'nomer_hp'             => $request->nomer_hp,
            'jabatan'              => $request->jabatan,
            'email'                => $request->email,
            'created_by'           => Auth::id(),
            'created_at'           => Carbon::now('GMT+7')->toDateTimeString(),
            'date'                 => Carbon::parse($request->date)->format('Y-m-d'),
        ];
        $qry = VisitModel::where('id', $request->id)->update($data);
        return redirect('sales/visitplan')->with('success', 'Updated Successfully');
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
}