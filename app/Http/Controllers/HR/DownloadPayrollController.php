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

class DownloadPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hrm.payroll.report', [
            'method'  => "post",
            'action'  => 'HR\DownloadPayrollController@generatereport'
        ]);
    }

    public function countpayroll(Request $request)
    {
        $month = $request->time;
        $listn = EmployeeModel::select('q.id as idku', 'employees.*', 'employees_salary_detail.*')
            ->where([
                ['emp_status', 'Active'],
                ['when', date('y-m', strtotime($month))]
            ])
            ->join('users as q', 'q.id_emp', '=', 'employees.id')
            ->join('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
            ->join('employees_salary_detail', 'employees.id', '=', 'employees_salary_detail.id_emp')
            ->orderBy('employees.id', 'ASC')
            ->get();

        $current_row = 10;
        $gross = $deduct = $net = 0;
        foreach ($listn as $key => $li) {

            $basic_salary  = explode('males', base64_decode($li->basic_salary))[0];
            $allowance     = explode('males', base64_decode($li->allowance))[0];
            $ded_other     = explode('males', base64_decode($li->ded_other))[0];
            $ded_tax       = explode('males', base64_decode($li->ded_tax))[0];
            $ded_insurance = explode('males', base64_decode($li->ded_insurance))[0];
            $overtime      = explode('males', base64_decode($li->overtime))[0];
            $ded_bpjs      = explode('males', base64_decode($li->ded_bpjs))[0];
            $ded_pension   = explode('males', base64_decode($li->ded_pension))[0];

            $gross  += $basic_salary+$allowance+$ded_bpjs+$ded_pension+$ded_tax+$overtime+$ded_insurance;
            $deduct += $ded_bpjs+$ded_pension+$ded_tax+$ded_other+$ded_insurance;
            $net    += $gross-$deduct;
        }
        $data = [
            'gross'     => $gross,
            'deduction' => $deduct,
            'net'       => $gross-$deduct,
            'month'     => date('F Y',strtotime($month)),
        ];
        return $data;
    }

    
    public function generatereport(Request $request)
    {
        $check = $request->session()->get('salary_key') == getConfig('salary_key');
        $month = $request->time;
        if($check){
            $this->excelgenerate($month);
        }else{
            return redirect('hrm/reportpayroll')->with("error", "Maaf anda tidak memiliki akses master token untuk melihat detail salary");
        }
        
    }

    public function excelgenerate($month)
    {
        $title       = 'Rekap Gaji - ' . date('F-Y', strtotime($month)) . '.xlsx';
        $objPHPExcel = new Spreadsheet();
        $sheet       = $objPHPExcel->getActiveSheet();

        // setting file //
        $objPHPExcel->getProperties()->setCreator('HomeMaleser')
            ->setLastModifiedBy('HomeMaleser')
            ->setTitle('Rekap-' . $title)
            ->setSubject('Rekap-' . $title)
            ->setDescription('Rekap-' . $title)
            ->setKeywords('Rekap-' . $title);

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

        $style_col2 = array(
            'font'      => array('bold' => true, 'size' => 18),   // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
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
        $style_total_akhir = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
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
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Rekap Gaji Bulanan' . ' (' . strtoupper($request->jenis) . ')');
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
        $objPHPExcel->getActiveSheet()->SetCellValue('D8', 'Gross Salary'); // HEADER MERGE
        $objPHPExcel->getActiveSheet()->SetCellValue('D9', 'Basic Salary');
        $objPHPExcel->getActiveSheet()->SetCellValue('E9', 'Allowance');
        $objPHPExcel->getActiveSheet()->SetCellValue('F9', 'BPJS');
        $objPHPExcel->getActiveSheet()->SetCellValue('G9', 'Pensions');
        $objPHPExcel->getActiveSheet()->SetCellValue('H9', 'Tax');
        $objPHPExcel->getActiveSheet()->SetCellValue('I9', 'Overtime');
        $objPHPExcel->getActiveSheet()->SetCellValue('J9', 'Insurance');
        $objPHPExcel->getActiveSheet()->SetCellValue('K8', 'Deduction'); // HEADER MERGE
        $objPHPExcel->getActiveSheet()->SetCellValue('K9', 'BPJS');
        $objPHPExcel->getActiveSheet()->SetCellValue('L9', 'Pensions');
        $objPHPExcel->getActiveSheet()->SetCellValue('M9', 'Tax');
        $objPHPExcel->getActiveSheet()->SetCellValue('N9', 'Other');
        $objPHPExcel->getActiveSheet()->SetCellValue('O9', 'Insurance');
        $objPHPExcel->getActiveSheet()->SetCellValue('P8', 'Total Gross');
        $objPHPExcel->getActiveSheet()->SetCellValue('Q8', 'Total Deduction');
        $objPHPExcel->getActiveSheet()->SetCellValue('R8', 'Net Salary');
        $objPHPExcel->getActiveSheet()->mergeCells('A8:A9');
        $objPHPExcel->getActiveSheet()->mergeCells('B8:B9');
        $objPHPExcel->getActiveSheet()->mergeCells('C8:C9');
        $objPHPExcel->getActiveSheet()->mergeCells('P8:P9');
        $objPHPExcel->getActiveSheet()->mergeCells('Q8:Q9');
        $objPHPExcel->getActiveSheet()->mergeCells('R8:R9');
        $objPHPExcel->getActiveSheet()->mergeCells('D8:J8');
        $objPHPExcel->getActiveSheet()->mergeCells('K8:O8');

        foreach (range('a', 'r') as $v) {
            $objPHPExcel->getActiveSheet()->getStyle($v . '8')->applyFromArray($style_total);
            $objPHPExcel->getActiveSheet()->getStyle($v . '9')->applyFromArray($style_total);
        }

        foreach (range('d', 'r') as $v) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($v)->setWidth(20);
        }



        // $report = $this->absen->filter($ta,$tk,$area);
        $listn = EmployeeModel::select('q.id as idku', 'employees.*', 'employees_salary_detail.*')
            ->where([
                ['emp_status', 'Active'],
                ['when', date('y-m', strtotime($month))]
            ])
            ->join('users as q', 'q.id_emp', '=', 'employees.id')
            ->join('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
            ->join('employees_salary_detail', 'employees.id', '=', 'employees_salary_detail.id_emp')
            ->orderBy('employees.id', 'ASC')
            ->get();
        // echo '<pre>' . var_export($listd, true) . '</pre>';

        $current_row = 10;
        foreach ($listn as $key => $li) {

            $basic_salary  = explode('males', base64_decode($li->basic_salary))[0];
            $allowance     = explode('males', base64_decode($li->allowance))[0];
            $ded_other     = explode('males', base64_decode($li->ded_other))[0];
            $ded_tax       = explode('males', base64_decode($li->ded_tax))[0];
            $ded_insurance = explode('males', base64_decode($li->ded_insurance))[0];
            $overtime      = explode('males', base64_decode($li->overtime))[0];
            $ded_bpjs      = explode('males', base64_decode($li->ded_bpjs))[0];
            $ded_pension   = explode('males', base64_decode($li->ded_pension))[0];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $current_row, $li->emp_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $current_row, $li->position);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $current_row, $li->division_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $current_row, $basic_salary);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $current_row, $allowance);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $current_row, $ded_bpjs);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $current_row, $ded_pension);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $current_row, $ded_tax);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $current_row, $overtime);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $current_row, $ded_insurance);

            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $current_row, $ded_bpjs);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $current_row, $ded_pension);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $current_row, $ded_tax);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $current_row, $ded_other);
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . $current_row, $ded_insurance);

            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $current_row, '=SUM(D' . $current_row . ':J' . $current_row.')');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $current_row, '=SUM(K' . $current_row . ':O' . $current_row.')');
            $objPHPExcel->getActiveSheet()->SetCellValue('R' . $current_row, '=P' . $current_row . '-Q' . $current_row.')');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $current_row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            foreach (range('a', 'r') as $v) {
                $objPHPExcel->getActiveSheet()->getStyle($v . $current_row)->applyFromArray($style_row);
            }
            foreach (range('d', 'r') as $v) {
                $objPHPExcel->getActiveSheet()->getStyle($v . $current_row)->getNumberFormat()->setFormatCode('#,##0');
            }
            $current_row++;
        }
        $row_total = $current_row+1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $current_row, "TOTAL GAJI");
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $current_row, '=SUM(R10:R' . $row_total.')');
        
        $objPHPExcel->getActiveSheet()->getStyle('A' . $current_row)->applyFromArray($style_total_akhir);
        $objPHPExcel->getActiveSheet()->getStyle('R' . $current_row)->applyFromArray($style_total_akhir);
        $objPHPExcel->getActiveSheet()->getStyle('R' . $current_row)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $current_row.':Q'.$row_total);
        $objPHPExcel->getActiveSheet()->mergeCells('R' . $current_row.':R'.$row_total);
        
        // Set orientasi kertas jadi LANDSCAPE
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $objPHPExcel->setActiveSheetIndex(0);


        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title . '"');
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
}
