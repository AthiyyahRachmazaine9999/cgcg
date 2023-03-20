<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeModel;
use App\Models\HR\SalaryModel;
use App\Models\HR\SalaryDetailModel;
use App\Models\Role\UserModel;
use App\Models\ConfigModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalaryDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hrm.payroll.detail.index');
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'emp_name',
            1 => 'division_name',
            2 => 'position',
            3 => 'bank_acc',
            4 => 'basic_salary',
            5 => 'allowance',
            6 => 'bpjs',
            7 => 'pension',
            8 => 'tax',
            9 => 'employees.id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = SalaryModel::select('*')
            ->where('emp_status', '=', 'Active')
            ->join('employees', 'employees_salary.id_emp', '=', 'employees.id')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = SalaryModel::select('*', 'employees.id as idku')
                ->where('emp_status', '=', 'Active')
                ->join('employees', 'employees_salary.id_emp', '=', 'employees.id')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = SalaryModel::select('*', 'employees.id as idku')
                ->where([
                    ['emp_status', '=', 'Active'],
                    ['emp_name', 'like', '%' . $search . '%']
                ])
                ->join('employees', 'employees_salary.id_emp', '=', 'employees.id')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = SalaryModel::select('*', 'employees.id as idku')
                ->where([
                    ['emp_status', '=', 'Active'],
                    ['emp_name', 'like', '%' . $search . '%']
                ])
                ->join('employees', 'employees_salary.id_emp', '=', 'employees.id')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($request->session()->has('salary_key')) {
                    $check = $request->session()->get('salary_key') == getConfig('salary_key');
                    if ($check) {
                        if ($post->basic_salary !== null) {
                            $decode_salary    = base64_decode($post->basic_salary);
                            $decode_allowance = base64_decode($post->allowance);
                            $decode_bpjs      = base64_decode($post->bpjs);
                            $decode_pension   = base64_decode($post->pension);
                            $decode_tax       = base64_decode($post->tax);

                            list($basic_salary, $keys_basic_salary) = explode('males', $decode_salary);
                            list($allowance, $key_allowance) = explode('males', $decode_allowance);
                            list($bpjs, $key_bpjs) = explode('males', $decode_bpjs);
                            list($pension, $key_pension) = explode('males', $decode_pension);


                            $gross_salary = $basic_salary + $allowance + $bpjs + $pension;
                            $deduction    = 0;
                            $thp          = 0;
                        } else {
                            $gross_salary = 0;
                            $deduction    = 0;
                            $thp          = 0;
                        }


                        $data[] = [
                            'emp_name'      => $post->emp_name,
                            'division_name' => $post->division_name,
                            'position'      => $post->position,
                            'bank_acc'      => $post->bank_acc,
                            'gross_salary'  => $gross_salary,
                            'deduction'     => $deduction,
                            'thp'           => $thp,
                            'created_at'    => Carbon::parse($post->created_at)->format('Y-m-d'),
                            'id'            => $post->idku,
                        ];
                    } else {
                        $data[] = [
                            'emp_name'      => $post->emp_name,
                            'division_name' => $post->division_name,
                            'position'      => $post->position,
                            'bank_acc'      => $post->bank_acc,
                            'gross_salary'  => '********',
                            'deduction'     => '********',
                            'thp'           => '********',
                            'created_at'    => Carbon::parse($post->created_at)->format('Y-m-d'),
                            'id'            => $post->idku,
                        ];
                    }
                } else {
                    $data[] = [
                        'emp_name'      => $post->emp_name,
                        'division_name' => $post->division_name,
                        'position'      => $post->position,
                        'bank_acc'      => $post->bank_acc,
                        'gross_salary'  => '********',
                        'deduction'     => '********',
                        'thp'           => '********',
                        'created_at'    => Carbon::parse($post->created_at)->format('Y-m-d'),
                        'id'            => $post->idku,
                    ];
                }
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function generatemonth(Request $request)
    {
        $mk   = $request->session()->get('salary_key');
        $getemp = EmployeeModel::select('*', 'employees.id as idku')
            ->where('emp_status', '=', 'Active')
            ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
            ->get();

        foreach ($getemp as $key => $value) {
            $data[] = [
                'year'         => date('yyyy'),
                'month'        => date('n'),
                'id_emp'       => $value->idku,
                'basic_salary' => $value->basic_salary,
                'allowance'    => $value->allowance,
                'bpjs'         => $value->bpjs,
                'pension'      => $value->pension,
                'tax'          => $value->tax,
                'ded_position' => base64_encode('0' . 'males' . $mk),
                'ded_bpjs'     => base64_encode('0' . 'males' . $mk),
                'ded_pension'  => base64_encode('0' . 'males' . $mk),
                'ded_tax'      => base64_encode('0' . 'males' . $mk),
                'ded_loan'     => base64_encode('0' . 'males' . $mk),
                'created_by'   => Auth::id(),
                'created_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $main = SalaryModel::where('id_emp', $request->id_emp)->first();
        $mk   = $request->session()->get('salary_key');
        if ($request->has('type')) {

            $data = [
                'id_emp'       => $request->id_emp,
                'when'         => date('y-m', strtotime($request->month)),
                'type'         => $request->type,
                'basic_salary' => base64_encode($request->basic_salary . 'males' . $mk),
                'created_by'   => Auth::id(),
                'created_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];
        } else {
            $data = [
                'id_emp'        => $request->id_emp,
                'basic_salary'  => $main->basic_salary,
                'allowance'     => $main->allowance,
                'bpjs'          => $main->bpjs,
                'pension'       => $main->pension,
                'when'          => date('y-m', strtotime($request->month)),
                'basic_salary'  => base64_encode($request->basic_salary . 'males' . $mk),
                'allowance'     => base64_encode($request->allowance . 'males' . $mk),
                'overtime'      => base64_encode($request->add_overtime . 'males' . $mk),
                'ded_other'     => base64_encode($request->ded_other . 'males' . $mk),
                'ded_bpjs'      => base64_encode($request->ded_bpjs . 'males' . $mk),
                'ded_pension'   => base64_encode($request->ded_pension . 'males' . $mk),
                'ded_tax'       => base64_encode($request->ded_tax . 'males' . $mk),
                'ded_loan'      => base64_encode($request->ded_loan . 'males' . $mk),
                'ded_insurance' => base64_encode($request->ded_insurance . 'males' . $mk),
                'created_by'    => Auth::id(),
                'created_at'    => Carbon::now('GMT+7')->toDateTimeString()
            ];
        }
        SalaryDetailModel::insert($data);
        return redirect('hrm/payroll/' . $request->id_emp . '/edit#detail')->with("success", "Berhasil di simpan");
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

    public function checkmonth(Request $request)
    {
        $main = SalaryModel::where([
            ['id_emp', $request->id_emp],
        ])->first();
        $check = SalaryDetailModel::where([
            ['id_emp', $request->id_emp],
            ['when', date('y-m', strtotime($request->month))],
            ['type', $request->jenis == 'thr' ? 'thr' : null]
        ])->first();
        if (is_null($check)) {
            $view   = $request->jenis == 'thr' ? 'thr' : "create";
            $method = "post";
            $action = 'HR\SalaryDetailController@store';
        } else {
            $view   = $request->jenis == 'thr' ? 'thr' : "edit";
            $method = "put";
            $action = ['HR\SalaryDetailController@update', $request->id_emp];
        }

        return view('hrm.payroll.detail.' . $view, [
            'main'    => $main,
            'getdata' => $check,
            'idku'    => $request->id_emp,
            'month'   => $request->month,
            'type'    => $request->jenis,
            'method'  => $method,
            'action'  => $action
        ]);
    }

    public function deduction(Request $request, $id)
    {
        $check = $request->session()->get('salary_key') == getConfig('salary_key');
        if ($check) {
            $employee = EmployeeModel::select('*', 'employees.id as idku')
                ->where('employees.id', $id)
                ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')->first();
            $method = $employee->id == null ? "post" : "put";
            $view   = $employee->id == null ? "create" : "edit";
            $action = $employee->id == null ? 'HR\SalaryController@store' : ['HR\SalaryController@update', $employee->idku];
            return view('hrm.payroll.detail.' . $view, [
                'getdata' => $employee,
                'method'  => $method,
                'action'  => $action
            ]);
        } else {
            return redirect('hrm/payroll')->with("error", "Maaf anda tidak memiliki akses master token untuk melihat detail salary");
        }
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
        $mk   = $request->session()->get('salary_key');
        if ($request->has('type')) {

            $data = [
                'id_emp'       => $request->id_emp,
                'type'         => $request->type,
                'basic_salary' => base64_encode($request->basic_salary . 'males' . $mk),
                'ded_tax'      => base64_encode($request->ded_tax . 'males' . $mk),
                'created_by'   => Auth::id(),
                'created_at'   => Carbon::now('GMT+7')->toDateTimeString()
            ];
        } else {
            $data = [
                'basic_salary'  => base64_encode($request->basic_salary . 'males' . $mk),
                'allowance'     => base64_encode($request->allowance . 'males' . $mk),
                'overtime'      => base64_encode($request->overtime . 'males' . $mk),
                'ded_other'     => base64_encode($request->ded_other . 'males' . $mk),
                'ded_bpjs'      => base64_encode($request->ded_bpjs . 'males' . $mk),
                'ded_pension'   => base64_encode($request->ded_pension . 'males' . $mk),
                'ded_tax'       => base64_encode($request->ded_tax . 'males' . $mk),
                'ded_loan'      => base64_encode($request->ded_loan . 'males' . $mk),
                'ded_insurance' => base64_encode($request->ded_insurance . 'males' . $mk),
                'update_by'     => Auth::id(),
                'updated_at'    => Carbon::now('GMT+7')->toDateTimeString()
            ];
        }
        SalaryDetailModel::where([
            ['id_emp', $id],
            ['when', date('y-m', strtotime($request->month))],
            ['type', $request->has('type') ? 'thr' : null]
        ])->update($data);
        return redirect('hrm/payroll/' . $id . '/edit#detail')->with('success', 'Salary Update successfully');
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
