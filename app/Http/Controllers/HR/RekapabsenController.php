<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeModel;
use App\Models\HR\EmployeeAssetModel;
use App\Models\Role\Role_division;
use App\Models\Role\Role_cabang;
use App\Models\Android\AndroidAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\HR\Req_LeaveModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use DB;

class RekapabsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hrm.absen.index');
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
        $mine = getIdUser($id);
        return view('hrm.absen.detail', [
            'mine'     => $mine,
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

    public function downloadrekap(Request $request)
    {
        return view('hrm.absen.download', [
            'method'  => "post",
            'action'  => 'HR\RekapabsenController@generate_rekap',
        ]);
    }

    public function ajax_data(Request $request)
    {
        $mine = getUserEmp(Auth::id());
        $div = $request->session()->put('division_id', $mine->division_id);

        $columns = array(
            0 => 'emp_name',
            1 => 'emp_email',
            2 => 'emp_phone',
            3 => 'emp_nip',
            4 => 'position',
            5 => 'emp_status',
            6 => 'id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $employee_count = EmployeeModel::where('emp_status', 'Active')->get();
        $totalData     = $employee_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = EmployeeModel::select('*')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = EmployeeModel::where('emp_name', 'like', '%' . $search . '%')
                ->orWhere('emp_address', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = EmployeeModel::where('emp_name', 'like', '%' . $search . '%')
                ->orWhere('emp_address', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'emp_status'     => $post->emp_status,
                    'emp_name'       => $post->emp_name,
                    'emp_email'      => $post->emp_email,
                    'emp_phone'      => $post->emp_phone,
                    'emp_nip'        => $post->emp_nip,
                    'position'       => $post->position,
                    'user'           => "employee",
                    'created_at'     => Carbon::parse($post->created_at)->format('Y-m-d'),
                    'id'             => $post->id,
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

    public function ajax_absen(Request $request)
    {
        $columns   = array(
            0 => 'location',
            1 => 'time',
            2 => 'status',
            3 => 'created_at',
            4 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = AndroidAbsensi::where('created_by', $request->myid)->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = AndroidAbsensi::select('*')->where('created_by', $request->myid)
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = AndroidAbsensi::where('created_by', $request->myid)->where('Name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = AndroidAbsensi::where('created_by', $request->myid)->where('Name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'location'   => $post->location,
                    'time'       => Carbon::parse($post->time)->format('H:i:s'),
                    'created_at' => Carbon::parse($post->created_at)->format('d-m-Y'),
                    'status'     => $post->status,
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
    public function time_absen(Request $request)
    {
        $absen = AndroidAbsensi::where('created_by', $request->ids)->get();
        foreach ($absen as $qry) {
            $arr[] = array(
                'title' => Carbon::parse($qry->time)->format('H:i:s') . ' (' . ucfirst($qry->status) . ')',
                'start' => Carbon::parse($qry->created_at)->format('Y-m-d')
            );
            $result = $arr;
        }
        return $result;
    }

    public function generate_rekap(Request $request)
    {
        if ($request->jenis == 'address') {
            $this->generate_address($request);
        } else {
            $this->generate_normal($request);
        }
    }

    public function generate_normal(Request $request)
    {
        $typedownload = $request->jenis == 'in' ? "CHECK-IN" : "CHECK-OUT";
        $start        = $request->start;
        $end          = $request->end;
        $title        = 'RekapAbsen-' . $typedownload . '_' . $start . '-' . $end . '.xlsx';
        $objPHPExcel  = new Spreadsheet();
        $sheet        = $objPHPExcel->getActiveSheet();

        // setting file //
        $objPHPExcel->getProperties()->setCreator('HomeMaleser')
            ->setLastModifiedBy('HomeMaleser')
            ->setTitle('Rekap-' . $title)
            ->setSubject('Rekap-' . $title)
            ->setDescription('Rekap-' . $title)
            ->setKeywords('Rekap-' . $title);

        $style_col = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );
        $style_row = array(

            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)

            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );
        $style_time = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
        );

        $style_col2 = array(
            'font'      => array('bold' => true, 'size' => 18),   // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            )
        );
        $coloring = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'ff0a0a')
            )
        );
        $green = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '40c263')
            )
        );

        $blue = array(
            'alignment' => array(
                'vertical'   => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color'    => array('argb' => '40c0c2')
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );

        $style_col3 = array(
            'font' => array('bold' => true, 'size' => 12)
        );

        $style_total = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER

            ),

            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );

        $style_total_blue = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type'  => Fill::FILL_SOLID,
                'color' => array('rgb' => '40c0c2')
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );

        $style_total_green = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type'  => Fill::FILL_SOLID,
                'color' => array('rgb' => '40c263')
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );



        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Absensi Karyawan' . ' (' . strtoupper($request->jenis) . ')');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'PT MITRA ERA GLOBAL');
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Perkantoran Mangga Dua Square Blok C.22-25');
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Jl. Mangga Dua Square No.22, RW.6, Ancol');
        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Pademangan, Jakarta, 10730');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col2);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col3);

        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Tanggal Cetak :' . date('d F Y'));

        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Di Cetak Oleh :' . getUserEmp(Auth::id())->emp_name);

        $objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Nama');
        $objPHPExcel->getActiveSheet()->SetCellValue('B8', 'Jabatan');
        $objPHPExcel->getActiveSheet()->SetCellValue('C8', 'Divisi');
        $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($style_total);

        $current_col = 4;
        $awal_col    = 2;
        $current_row = 9;
        $next_row    = 9;

        // $report = $this->absen->filter($ta,$tk,$area);
        $listn = EmployeeModel::select('q.id as idku', 'employees.*')->where('emp_status', 'Active')
            ->join('users as q', 'q.id_emp', '=', 'employees.id')
            ->orderBy('employees.id', 'ASC')
            ->get();
        $listd = AndroidAbsensi::select('created_at')
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)->groupBy(DB::raw('Date(created_at)'))->get();
        // echo '<pre>' . var_export($listd, true) . '</pre>';

        $ars = 0;
        foreach ($listd as $val) {
            // echo '<pre>' . var_export($val->created_at, true) . '</pre>';

            $sdate    = strtotime($val->created_at);
            $ftanggal = date('d-M', $sdate);
            $curr     = date('D', $sdate);
            if ($curr == 'Sat' || $curr == 'Sun') {
            } else {
                $ars++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, '8', $ftanggal);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col, '8')->applyFromArray($style_total);
                $current_col++;
            }
        }


        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, '8', "HK");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 1, '8', "Cuti");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 1, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 2, '8', "Sakit");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 2, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 3, '8', "Alfa");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 3, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 4, '8', "Izin");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 4, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 5, '8', "Dinas");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 5, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 6, '8', "Izin Telat");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 6, '8')->applyFromArray($style_total)->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 7, '8', "Hadir");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 7, '8')->applyFromArray($style_total_blue);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 8, '8', "Telat");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 8, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 9, '8', "Potongan");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 9, '8')->applyFromArray($style_total_green);;

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 10, '8', "Total Potongan");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 10, '8')->applyFromArray($style_total)->getAlignment()->setWrapText(true);



        $next_col = 4;

        foreach ($listn as $key => $li) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $current_row, $li->emp_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $current_row, $li->position);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $current_row, $li->division_name);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $looking  = $key * $ars;
            $telatku  = date('H:i', strtotime('08:45:00'));
            $cepatku  = date('H:i', strtotime('17:30:00'));




            foreach ($listd as $val => $oh) {

                $tanggalnya = date('Y-m-d', strtotime($oh->created_at));
                $currs      = date('D', strtotime($oh->created_at));

                if ($currs == 'Sat' || $currs == 'Sun') {
                } else {
                    if ($request->jenis == 'in') {
                        $looks = AndroidAbsensi::where([
                            ['created_by', $li->idku],
                            ['status', 'check-in'],
                            ['created_at', 'like', '%' . $tanggalnya . '%']
                        ])->first();
                    } else if ($request->jenis == 'out') {
                        $looks = AndroidAbsensi::where([
                            ['created_by', $li->idku],
                            ['status', 'check-out'],
                            ['created_at', 'like', '%' . $tanggalnya . '%']
                        ])->orderBy('id', 'desc')->first();
                    }

                    $cariizin    = Req_LeaveModel::where([
                        ['created_by', $li->idku],
                        ['type_leave', 'Permission'],
                        ['date_finish', $tanggalnya],
                    ])->first();
                    $caritelat   = Req_LeaveModel::where([
                        ['created_by', $li->idku],
                        ['type_leave', 'like', '%Late%'],
                        ['created_at', 'like', '%' . $tanggalnya . '%']
                    ])->first();
                    $cutispecial = Req_LeaveModel::where([
                        ['created_by', $li->idku],
                        ['type_leave', 'Special Leave'],
                        ['date_from', $tanggalnya]
                    ])->first();
                    $cutibiasa   = Req_LeaveModel::where([
                        ['created_by', $li->idku],
                        ['type_leave', 'Annual Leave'],
                        ['date_from', $tanggalnya]
                    ])->first();


                    if (is_null($looks)) {
                        if (is_null($cariizin)) {
                            if (is_null($cutispecial)) {
                                if (is_null($cutibiasa)) {
                                    $fjam = "-";
                                } else {
                                    $items  = array();
                                    $akhir = new DateTime($cutibiasa->date_finish);
                                    $akhir->modify('+1 day');
                                    $period = new DatePeriod(
                                        new DateTime($cutibiasa->date_from),
                                        new DateInterval('P1D'),
                                        $akhir
                                    );
                                    foreach ($period as $key => $value) {
                                        $items[] = $value->format('Y-m-d');
                                    }
                                    // echo '<pre>' . var_export($items, true) . '</pre>';
                                    if (in_array($tanggalnya, $items)) {
                                        $fjam = "Cuti";
                                    } else {
                                        $fjam = "-";
                                    }
                                }
                            } else {
                                $fjam = "Cuti";
                            }
                        } else {
                            $fjam = "Izin";
                        }
                    } else {
                        if (is_null($caritelat)) {
                            $fjam    = date('H:i',  strtotime($looks->created_at));
                        } else {
                            if ($request->jenis == 'out') {
                                $fjam    = date('H:i',  strtotime($looks->created_at));
                            } else {
                                $fjam = "Izin Telat";
                            }
                        }
                    }



                    // ============ pengecekan perizinan ============ //
                    if (in_array($fjam, array('-', 'Izin', "Cuti", "Izin Telat"))) {
                        $cetak_waktu = $fjam;
                        $gantistyle  = $style_time;
                    } else {
                        if ($request->jenis == 'in') {
                            if (strtotime($fjam) > strtotime($telatku)) {
                                $gantistyle  = $coloring;
                                $cetak_waktu = $fjam;

                                // } elseif ($cetak_waktu == 'Alfa') {
                                //     $gantistyle = $green;
                                // } elseif ($cetak_waktu == 'Siang') {
                                //     $gantistyle = $blue;
                            } else {
                                $cetak_waktu = $fjam;
                                $gantistyle  = $style_time;
                            }
                        } else if ($request->jenis == 'out') {
                            if (strtotime($fjam) < strtotime($cepatku)) {
                                $gantistyle  = $coloring;
                                $cetak_waktu = $fjam;

                                // } elseif ($cetak_waktu == 'Alfa') {
                                //     $gantistyle = $green;
                                // } elseif ($cetak_waktu == 'Siang') {
                                //     $gantistyle = $blue;
                            } else {
                                $cetak_waktu = $fjam;
                                $gantistyle  = $style_time;
                            }
                        }
                    }

                    if ($next_col > $looking) {
                        $nextt = $next_col - $looking;
                    } else {
                        $nextt = $next_col;
                    }
                    // echo '<pre>' . var_export($gantistyle, true) . '</pre>';

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt, $current_row, $cetak_waktu);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt, $current_row)->applyFromArray($gantistyle)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME2);
                    $next_col++;
                }
            }


            $alphabet     = $this->createColumnsArray('BZ');
            $getcolumn    = $alphabet[$nextt];
            $getbatas     = $alphabet[$nextt - 1];
            $colhk        = $alphabet[$nextt + 0];
            $colcuti      = $alphabet[$nextt + 1];
            $colsakit     = $alphabet[$nextt + 2];
            $colalfa      = $alphabet[$nextt + 3];
            $colizin      = $alphabet[$nextt + 4];
            $coldinas     = $alphabet[$nextt + 5];
            $colsiang     = $alphabet[$nextt + 6];
            $colhadir     = $alphabet[$nextt + 7];
            $coltelat     = $alphabet[$nextt + 8];
            $colpotong    = $alphabet[$nextt + 9];
            // $coltotpotong = $alphabet[$nextt + 11];
            $ccuti        = '=COUNTIF(D' . $current_row . ':' . $getbatas . $current_row . ',' . $colcuti . '$8)';
            $csakit       = '=COUNTIF(D' . $current_row . ':' . $getbatas . $current_row . ',' . $colsakit . '$8)';
            $calfa        = '=COUNTIF(D' . $current_row . ':' . $getbatas . $current_row . ',' . '"-")';
            $cizin        = '=COUNTIF(D' . $current_row . ':' . $getbatas . $current_row . ',' . $colizin . '$8)';
            $cdinas       = '=COUNTIF(D' . $current_row . ':' . $getbatas . $current_row . ',' . $coldinas . '$8)';
            $csiang       = '=COUNTIF(D' . $current_row . ':' . $getbatas . $current_row . ',' . $colsiang . '$8)';
            $chadir       = '=' . $colhk . $current_row . "-SUM(" . $colcuti . $current_row . ":" . $colsiang . $current_row . ")";
            $rumus1       = '=COUNTIFS(D' . $current_row . ':' . $getbatas . $current_row . ',">=8:45")';
            $potongan     = '=' . $coltelat . $current_row . '*' . $colpotong . $current_row;

            // HK coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 1, $current_row, $ars);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 1, $current_row)->applyFromArray($style_time);

            // Cuti coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 2, $current_row, $ccuti);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 2, $current_row)->applyFromArray($style_time);

            // Sakit coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 3, $current_row, $csakit);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 3, $current_row)->applyFromArray($style_time);

            // Alfa coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 4, $current_row, $calfa);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 4, $current_row)->applyFromArray($style_time);

            // Izin coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 5, $current_row, $cizin);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 5, $current_row)->applyFromArray($style_time);

            // Dinas coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 6, $current_row, $cdinas);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 6, $current_row)->applyFromArray($style_time);

            // Siang coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 7, $current_row, $csiang);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 7, $current_row)->applyFromArray($style_time);

            // Hadir coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 8, $current_row, $chadir);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 8, $current_row)->applyFromArray($blue);

            // telat coloumn //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 9, $current_row, $rumus1);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 9, $current_row)->applyFromArray($style_time);

            // Potongan //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 10, $current_row, "20000");
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 10, $current_row)->applyFromArray($green)->getNumberFormat()->setFormatCode('_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-');
            $objPHPExcel->getActiveSheet()->getColumnDimension($colpotong)->setWidth(15);

            // Total potongan //

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($nextt + 11, $current_row, $potongan);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($nextt + 11, $current_row)->applyFromArray($style_time)->getNumberFormat()->setFormatCode('_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-');
            // $objPHPExcel->getActiveSheet()->getColumnDimension($nextt + 11, $current_row)->setWidth(15);

            $current_row++;
            $next_row++;
        }
        // Set orientasi kertas jadi LANDSCAPE
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $objPHPExcel->getActiveSheet(0)->setTitle("Rekap Absen IN");
        $objPHPExcel->setActiveSheetIndex(0);


        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        $writer->save('php://output');
    }

    public function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length  = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

    public function generate_address(Request $request)
    {
        $start       = $request->start;
        $end         = $request->end;
        $title       = 'RekapAbsen-LOCATION_' . $start . '-' . $end . '.xlsx';
        $objPHPExcel = new Spreadsheet();
        $sheet       = $objPHPExcel->getActiveSheet();

        // setting file //
        $objPHPExcel->getProperties()->setCreator('HomeMaleser')
            ->setLastModifiedBy('HomeMaleser')
            ->setTitle('Rekap-' . $title)
            ->setSubject('Rekap-' . $title)
            ->setDescription('Rekap-' . $title)
            ->setKeywords('Rekap-' . $title);

        $style_col = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );
        $style_row = array(

            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)

            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );
        $style_time = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
        );

        $style_col2 = array(
            'font'      => array('bold' => true, 'size' => 18),   // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            )
        );
        $coloring = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'ff0a0a')
            )
        );
        $green = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
                'vertical'   => Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '40c263')
            )
        );

        $blue = array(
            'alignment' => array(
                'vertical'   => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color'    => array('argb' => '40c0c2')
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );

        $style_col3 = array(
            'font' => array('bold' => true, 'size' => 12)
        );

        $style_total = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER

            ),

            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),   // Set border top dengan garis tipis
                'right'  => array('borderStyle'  => Border::BORDER_THIN),   // Set border right dengan garis tipis
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),   // Set border bottom dengan garis tipis
                'left'   => array('borderStyle'  => Border::BORDER_THIN)    // Set border left dengan garis tipis
            )
        );

        $style_total_blue = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type'  => Fill::FILL_SOLID,
                'color' => array('rgb' => '40c0c2')
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );

        $style_total_green = array(
            'font'      => array('bold' => true),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type'  => Fill::FILL_SOLID,
                'color' => array('rgb' => '40c263')
            ),
            'borders' => array(
                'top'    => array('borderStyle'  => Border::BORDER_THIN),
                'right'  => array('borderStyle'  => Border::BORDER_THIN),
                'bottom' => array('borderStyle'  => Border::BORDER_THIN),
                'left'   => array('borderStyle'  => Border::BORDER_THIN)
            )
        );



        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Absensi Location Karyawan');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'PT MITRA ERA GLOBAL');
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Perkantoran Mangga Dua Square Blok C.22-25');
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Jl. Mangga Dua Square No.22, RW.6, Ancol');
        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Pademangan, Jakarta, 10730');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col2);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col3);

        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Tanggal Cetak :' . date('d F Y'));

        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Di Cetak Oleh :' . getUserEmp(Auth::id())->emp_name);

        $objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Nama');
        $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($style_total);

        $current_col = 2;
        $awal_col    = 2;
        $current_row = 9;
        $next_row    = 9;

        // $report = $this->absen->filter($ta,$tk,$area);
        $listn = AndroidAbsensi::select('q.name as idku', 'e.division_id as mydiv', 'android_absensi.*')->where('emp_status', 'Active')
            ->whereDate('android_absensi.created_at', '>=', $start)
            ->whereDate('android_absensi.created_at', '<=', $end)
            ->join('users as q', 'q.id', '=', 'android_absensi.created_by')
            ->join('employees as e', 'e.id', '=', 'q.id_emp')
            ->orderBy('e.id', 'ASC')
            ->orderBy('android_absensi.created_at', 'ASC')
            ->get();

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, '8', "Location");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 1, '8', "Status");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 1, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 2, '8', "Day");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 2, '8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 3, '8', "Date");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 3, '8')->applyFromArray($style_total);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 4, '8', "Time");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 4, '8')->applyFromArray($style_total);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col + 5, '8', "Division");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($current_col + 5, '8')->applyFromArray($style_total);
        $telatku  = date('H:i', strtotime('08:45:00'));
        $cepatku  = date('H:i', strtotime('17:30:00'));
        foreach ($listn as $key => $li) {
            $fjam    = date('H:i',  strtotime($li->created_at));
            if ($li->status == 'check-in') {
                if (strtotime($fjam) > strtotime($telatku)) {
                    $styleku = $coloring;
                } else {
                    $styleku = $style_row;
                }
            } elseif ($li->status == 'check-out') {
                if (strtotime($fjam) < strtotime($cepatku)) {
                    $styleku = $coloring;
                } else {
                    $styleku = $style_row;
                }
            } else {
                $styleku = $style_row;
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $current_row, $li->idku);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $current_row, $li->location);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $current_row, strtoupper($li->status));
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $current_row, date('l', strtotime($li->created_at)));
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $current_row, date('Y-m-d', strtotime($li->created_at)));
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $current_row, date('H:i:s', strtotime($li->created_at)));
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $current_row, div_name($li->mydiv));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $current_row)->applyFromArray($styleku);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $objPHPExcel->getActiveSheet()->getHighestRow())
                ->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

            $current_row++;
            $next_row++;
        }
        // Set orientasi kertas jadi LANDSCAPE
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $objPHPExcel->getActiveSheet(0)->setTitle("Rekap Absen Address");
        $objPHPExcel->setActiveSheetIndex(0);


        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        $writer->save('php://output');
    }
}
