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
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use PDF;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mine = getUserEmp(Auth::id())->division_id;
        if (in_array($mine, array('7', '8'))) {
            $view = 'index';
        } else {
            $view = 'indexother';
        }
        return view('hrm.payroll.' . $view);
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'employees.id',
            1 => 'emp_name',
            2 => 'division_name',
            3 => 'position',
            4 => 'bank_acc',
            5 => 'basic_salary',
            6 => 'allowance',
            7 => 'bpjs',
            8 => 'pension',
            9 => 'tax',
            10 => 'employees.id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = EmployeeModel::select('*')
            ->where('emp_status', '=', 'Active')
            ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = EmployeeModel::select('*', 'employees.id as idku')
                ->where('emp_status', '=', 'Active')
                ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = EmployeeModel::select('*', 'employees.id as idku')
                ->where([
                    ['emp_status', '=', 'Active'],
                    ['emp_name', 'like', '%' . $search . '%']
                ])
                ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = EmployeeModel::select('*', 'employees.id as idku')
                ->where([
                    ['emp_status', '=', 'Active'],
                    ['emp_name', 'like', '%' . $search . '%']
                ])
                ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($request->session()->has('salary_key')) {
                    $check = $request->session()->get('salary_key') == getConfig('salary_key');
                    if ($check) {
                        $decode_salary    = base64_decode($post->basic_salary);
                        $decode_allowance = base64_decode($post->allowance);
                        $data[] = [
                            'emp_name'      => $post->emp_name,
                            'division_name' => $post->division_name,
                            'position'      => $post->position,
                            'bank_acc'      => $post->bank_acc,
                            'basic_salary'  => $decode_salary,
                            'allowance'     => $decode_allowance,
                            'created_at'    => Carbon::parse($post->created_at)->format('Y-m-d'),
                            'id'            => $post->idku,
                        ];
                    } else {
                        $data[] = [
                            'emp_name'      => $post->emp_name,
                            'division_name' => $post->division_name,
                            'position'      => $post->position,
                            'bank_acc'      => $post->bank_acc,
                            'basic_salary'  => '********',
                            'allowance'     => '********',
                            'bpjs'          => '********',
                            'pension'       => '********',
                            'tax'           => '********',
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
                        'basic_salary'  => '********',
                        'allowance'     => '********',
                        'bpjs'          => '********',
                        'pension'       => '********',
                        'tax'           => '********',
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

    public function my_data(Request $request)
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

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order')[0]['column']];
        $dir    = $request->input('order')[0]['dir'];
        $id_emp = getUserEmp(Auth::id())->id_emp;

        $menu_count    = EmployeeModel::select('*')
            ->where('emp_status', '=', 'Active')
            ->where('employees.id', '=', $id_emp)
            ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')->get();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = EmployeeModel::select('*', 'employees.id as idku')
                ->where('emp_status', '=', 'Active')
                ->where('employees.id', '=', $id_emp)
                ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($request->session()->has('salary_key')) {
                    $check = $request->session()->get('salary_key') == $post->mytoken;
                    if ($check) {
                        $decode_salary    = base64_decode($post->basic_salary);
                        $decode_allowance = base64_decode($post->allowance);
                        $decode_bpjs      = base64_decode($post->bpjs);
                        $decode_pension   = base64_decode($post->pension);
                        $decode_tax       = base64_decode($post->tax);

                        $data[] = [
                            'emp_name'      => $post->emp_name,
                            'division_name' => $post->division_name,
                            'position'      => $post->position,
                            'bank_acc'      => $post->bank_acc,
                            'basic_salary'  => $decode_salary,
                            'allowance'     => $decode_allowance,
                            'bpjs'          => $decode_bpjs,
                            'pension'       => $decode_pension,
                            'tax'           => $decode_tax,
                            'created_at'    => Carbon::parse($post->created_at)->format('Y-m-d'),
                            'id'            => $post->idku,
                        ];
                    } else {
                        $data[] = [
                            'emp_name'      => $post->emp_name,
                            'division_name' => $post->division_name,
                            'position'      => $post->position,
                            'bank_acc'      => $post->bank_acc,
                            'basic_salary'  => '********',
                            'allowance'     => '********',
                            'bpjs'          => '********',
                            'pension'       => '********',
                            'tax'           => '********',
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
                        'basic_salary'  => '********',
                        'allowance'     => '********',
                        'bpjs'          => '********',
                        'pension'       => '********',
                        'tax'           => '********',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mk   = $request->session()->get('salary_key');
        $data = [
            'id_emp'       => $request->id_emp,
            'basic_salary' => base64_encode($request->basic_salary . 'males' . $mk),
            'allowance'    => base64_encode($request->allowance . 'males' . $mk),
            'bpjs'         => base64_encode($request->bpjs . 'males' . $mk),
            'pension'      => base64_encode($request->pension . 'males' . $mk),
            'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'   => Auth::id(),
        ];
        SalaryModel::insert($data);
        return redirect('hrm/payroll')->with('success', 'Salary Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = EmployeeModel::select('*', 'employees.id as idku')
            ->where('employees.id', $id)
            ->join('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')->first();
        $check  = $request->session()->get('salary_key') == $data->mytoken;
        if ($check) {
            return view('hrm.payroll.show', [
                'data'     => $data,
                'method'  => "post",
                'action'  => 'HR\SalaryController@download'
            ]);
        } else {
            return redirect('hrm/payroll')->with("error", "Silahkan masukan token anda untuk dapat mendownload");
        }
    }

    public function checkdetail(Request $request)
    {
        $time  = date('y-m', strtotime($request->month));
        $check = SalaryDetailModel::where([
            ['id_emp', $request->id_emp],
            ['when', $time],
        ])->first();
        return $check == null ? "empty" : "exist";
    }



    public function download(Request $request)
    {
        $finaldate = $request->date_hr == null ? $request->date : $request->date_hr;
        $time      = date('y-m', strtotime($finaldate));
        $check     = SalaryDetailModel::where([
            ['id_emp', $request->id_emp],
            ['when', $time],
        ])->first();
        $data = EmployeeModel::select('*', 'employees.id as idku')
            ->where('employees.id', $request->id_emp)
            ->join('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')->first();
        $views = $request->type == 'thr' ? 'slipgaji_thr' : 'slipgaji';
        $pdf   = PDF::loadview('pdf.' . $views, [
            'data'  => $data,
            'check' => $check,
            'time'  => Carbon::now('GMT+7')->format('d F Y')
        ]);
        return $pdf->stream('slipgaji.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $check = $request->session()->get('salary_key') == getConfig('salary_key');
        if ($check) {
            $employee = EmployeeModel::select('*', 'employees.id as idku')
                ->where('employees.id', $id)
                ->leftjoin('employees_salary', 'employees.id', '=', 'employees_salary.id_emp')->first();
            $method = $employee->id == null ? "post" : "put";
            $view   = $employee->id == null ? "create" : "edit";
            $action = $employee->id == null ? 'HR\SalaryController@store' : ['HR\SalaryController@update', $employee->idku];
            return view('hrm.payroll.' . $view, [
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
        $data = [
            'id_emp'       => $request->id_emp,
            'basic_salary' => base64_encode($request->basic_salary . 'males' . $mk),
            'allowance'    => base64_encode($request->allowance . 'males' . $mk),
            'bpjs'         => base64_encode($request->bpjs . 'males' . $mk),
            'pension'      => base64_encode($request->pension . 'males' . $mk),
            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString(),
            'update_by'    => Auth::id(),
        ];
        SalaryModel::where('id_emp', $id)->update($data);
        return redirect('hrm/payroll/' . $request->id_emp . '/edit')->with('success', 'Salary Update successfully');
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

    public function check_token(Request $request)
    {
        $check  = getConfig('salary_key');
        $awal   = $check == null ? "baru" : "normal";
        $method = "post";
        $action = 'HR\SalaryController@storekey';
        return view('hrm.payroll.masterkey', [
            'awal'   => $awal,
            'method' => $method,
            'action' => $action
        ]);
    }

    public function storekey(Request $request)
    {
        if ($request->has('password_new')) {
            $data  = [
                'config_value' => sha1($request->password_new),
                'updated_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                'update_by'    => Auth::id(),
            ];
            ConfigModel::where('config_name', 'salary_key')->update($data);
            $message   = "success";
            $fullnotif = "Master Key berhasil di buat";
        } else {
            if (is_null($request->password_confirm)) {
                if (is_null($request->password_reset)) {
                    $check = getConfig('salary_key') == sha1($request->password_lama);
                    if ($check) {
                        $data  = [
                            'config_value' => sha1($request->password_baru),
                            'updated_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                            'update_by'    => Auth::id(),
                        ];
                        ConfigModel::where('config_name', 'salary_key')->update($data);
                        $message   = "success";
                        $fullnotif = "Reset Berhasil silahkan masukan master key untuk melihat gaji";
                    } else {
                        $message   = "error";
                        $fullnotif = "Reset Gagal silahkan masukan master key sekarang dengan benar";
                    }
                } else {
                    $usm    = UserModel::where('id', Auth::id())->first();
                    $empl   = EmployeeModel::where('id_emp', $usm->id_emp)->first();
                    $detail = [$empl];
                    $menu   = "Confirmation";
                    $token  = base64_encode("master" . rand());
                    $url    = url('/hrm/salary/masterkeyconfirm?token=' . $token);
                    $event  = "Master Key Reset Confirmation";
                    SendEmailResetMaster($detail, $url, $event, $menu);
                    $message   = "success";
                    $fullnotif = "Silahkan Check email hrd@maleser.com, untuk mereset master key";
                }
            } else {
                $check = getConfig('salary_key') == sha1($request->password_confirm);
                if ($check) {
                    $request->session()->put('salary_confirm', "benar");
                    $request->session()->put('salary_key', sha1($request->password_confirm));
                    $message   = "success";
                    $fullnotif = "Master Key Sesuai";
                } else {
                    $message   = "error";
                    $fullnotif = "Masterkey Salah";
                }
            }
        }

        return redirect('hrm/payroll')->with($message, $fullnotif);
    }



    public function personaloken(Request $request)
    {
        $ids    = getUserEmp($request->id_emp)->id_emp;
        $check  = SalaryModel::where('id_emp', $ids)->first();
        if (is_null($check)) {
            $exist  = "new";
            $awal   = "";
        } else {
            $exist  = "exist";
            $awal   = $check->mytoken == null ? "baru" : "normal";
        }

        $method = "post";
        $action = 'HR\SalaryController@storepersonaloken';
        return view('hrm.payroll.personaltoken', [
            'awal'   => $awal,
            'exist'  => $exist,
            'method' => $method,
            'action' => $action
        ]);
    }

    public function storepersonaloken(Request $request)
    {
        $ids    = getUserEmp(Auth::id())->id_emp;
        if ($request->has('password_new')) {
            $data  = [
                'mytoken'    => sha1($request->password_new),
            ];
            SalaryModel::where('id_emp', $ids)->update($data);

            $message   = "success";
            $fullnotif = "Master Key berhasil di buat";
        } else {
            $getdata = SalaryModel::where('id_emp', $ids)->first()->mytoken;
            if (is_null($request->password_confirm)) {
                if (is_null($request->password_reset)) {
                    $check = $getdata == sha1($request->password_lama);
                    if ($check) {
                        $data  = [
                            'mytoken'    => sha1($request->password_baru),
                        ];
                        SalaryModel::where('id_emp', $ids)->update($data);
                        $message   = "success";
                        $fullnotif = "Reset Berhasil silahkan masukan master key untuk melihat gaji";
                    } else {
                        $message   = "error";
                        $fullnotif = "Reset Gagal silahkan masukan master key sekarang dengan benar";
                    }
                } else {
                    $usm    = UserModel::where('id', Auth::id())->first();
                    $empl   = EmployeeModel::where('id_emp', $usm->id_emp)->first();
                    $detail = [$empl];
                    $menu   = "Confirmation";
                    $token  = base64_encode("master" . rand());
                    $url    = url('/hrm/salary/masterkeyconfirm?token=' . $token);
                    $event  = "Master Key Reset Confirmation";
                    SendEmailResetMaster($detail, $url, $event, $menu);
                    $message   = "success";
                    $fullnotif = "Silahkan Check email , untuk mereset master key";
                }
            } else {
                $check = $getdata == sha1($request->password_confirm);
                if ($check) {
                    $request->session()->put('salary_confirm', "benar");
                    $request->session()->put('salary_key', sha1($request->password_confirm));
                    $message   = "success";
                    $fullnotif = "Master Key Sesuai";
                } else {
                    $message   = "error";
                    $fullnotif = "Masterkey Salah";
                }
            }
        }

        return redirect('hrm/payroll')->with($message, $fullnotif);
    }

    public function masterkeyconfirm(Request $request)
    {
        return view('hrm.payroll.confirmkey', [
            'method'  => "post",
            'action'  => 'HR\SalaryController@saveconfirm'
        ]);
    }
}
