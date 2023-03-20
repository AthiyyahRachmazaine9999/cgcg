<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HR\Req_OvertimeModel;
use App\Models\HR\Req_OvertimeApp;
use App\Models\HR\EmployeeModel;
use App\Models\role_division;
use Carbon\Carbon;
use Auth;
use DB;


class Req_OvertimeController extends Controller
{
    public function index()
    {
        return view ('hrm.Overtime.index');
    }


 public function ajax_data(Request $request)
     {
        $mine = getUserEmp(Auth::id());
        $emp  = EmployeeModel::where('spv_id', $mine->id)->first();
        $div = $request->session()->put('division_id', $mine->division_id);
        if($mine->division_id == 7 || in_array($mine->id,explode(',',getConfig('list_hr')))){
            return $this->ajax_dataManagement($request);
        }else if($emp==null ? false : $emp->spv_id == $mine->id){
            return $this->ajax_spv($request);
        }
        else{
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'date',
             3 => 'purpose',
             4 => 'overtime_from',
             5 => 'overtime_finish',
             6 => 'status',
             7 => 'created_at',
             8 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Req_OvertimeModel::where('employee_id', $mine->id_emp);
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_OvertimeModel::select('*')->where('employee_id', $mine->id_emp)->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_OvertimeModel::where('employee_id', $mine->id_emp)
                    ->where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = Req_OvertimeModel::where('employee_id', $mine->id_emp)->where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'employee_id'     => emp_name($post->employee_id),
                    'division_id'     => div_name($post->division_id),
                    'purpose'         => $post->purpose,
                    'date'            => $post->date,
                    'overtime_from'   => Carbon::parse($post->overtime_from)->format('H:i:s'),
                    'overtime_finish' => Carbon::parse($post->overtime_finish)->format('H:i:s'),
                    'status'          => $post->status,
                    'created_at'      => $post->created_at->format('Y-m-d'),
                    'id'              => $post->id,
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

    public function ajax_dataManagement(Request $request)
        {
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'date',
             3 => 'purpose',
             4 => 'overtime_from',
             5 => 'overtime_finish',
             6 => 'status',
             7 => 'created_at',
             8 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Req_OvertimeModel::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_OvertimeModel::select('*')
             ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_OvertimeModel::where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = Req_OvertimeModel::where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'employee_id'     => emp_name($post->employee_id),
                    'division_id'     => div_name($post->division_id),
                    'purpose'         => $post->purpose,
                    'date'            => $post->date,
                    'overtime_from'   => Carbon::parse($post->overtime_from)->format('H:i:s'),
                    'overtime_finish' => Carbon::parse($post->overtime_finish)->format('H:i:s'),
                    'status'          => $post->status,
                    'created_at'      => $post->created_at->format('Y-m-d'),
                    'id'              => $post->id,
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

    public function ajax_spv(Request $request)
    {
        $mine    = getUserEmp(Auth::id());
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'date',
             3 => 'purpose',
             4 => 'overtime_from',
             5 => 'overtime_finish',
             6 => 'status',
             7 => 'created_at',
             8 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count = EmployeeModel::join('req_overtime', 'req_overtime.employee_id', '=', 'employees.id')
         ->where('spv_id', $mine->id)->orWhere('employee_id', $mine->id_emp)->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = EmployeeModel::select('*')->join('req_overtime', 'req_overtime.employee_id', '=', 'employees.id')
         ->where('spv_id', $mine->id)->orWhere('employee_id', $mine->id_emp)->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = EmployeeModel::join('req_overtime', 'req_overtime.employee_id', '=', 'employees.id')
         ->where('spv_id', $mine->id)->orwhere('employee_id', $mine->id_emp)->where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = EmployeeModel::join('req_overtime', 'req_overtime.employee_id', '=', 'employees.id')
         ->where('spv_id', $mine->id)->orwhere('employee_id', $mine->id_emp)->where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'employee_id'     => emp_name($post->employee_id),
                    'division_id'     => div_name($post->division_id),
                    'purpose'         => $post->purpose,
                    'date'            => $post->date,
                    'overtime_from'   => Carbon::parse($post->overtime_from)->format('H:i:s'),
                    'overtime_finish' => Carbon::parse($post->overtime_finish)->format('H:i:s'),
                    'status'          => $post->status,
                    'created_at'      => $post->created_at->format('Y-m-d'),
                    'id'              => $post->id,
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



    public function show($id)
    {
        $data     = Req_OvertimeModel::find($id);
        $ov       = Req_OvertimeApp::where('id_overtime', $id)->first();
        $overtime = Req_OvertimeApp::where('id_overtime', $id)->get();
        $st       = $data->status;
        $mine     = getUserEmp(Auth::id());
        $emp      = getEmp($data->employee_id);
        if($st=="Approved" || $st=="Submitted")
        {
            $info = array (
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } else if ($mine->id==$emp->spv_id && $st=="Approved By Supervisor") {
            $info = array(
                'BtnApp'  => 'disabled',
                'BtnApp2' => 'disabled',
            );
        } else if($st=="Reject" || $st="Reject By HRD" || $st=="Reject By Supervisor") {
            $info = array(
            'BtnApp'  => "",
            'BtnApp2' => '',
            );
        } else {
            $info = array(
            'BtnApp'  => "",
            'BtnApp2' => '',
            );
        }
        return view('hrm.Overtime.show',[
            'data'     => $data,
            'ov'       => $ov,
            'overtime' => $overtime,
            'emp'      => $emp,
            'mine'     => $mine,
            'info'     => $info,
            'employee' => emp_name($data->employee_id),
            'division' => div_name($data->division_id),
        ]);
    }

    public function approve(Request $request)
    {
        // dd($request->segment(5));
        $id   = $request->segment(5);
        $type = $request->segment(6);
        $mine = getUserEmp(Auth::id());

        if ($type=="SPV"){
        $app=[
            'status'      => "Approved By Supervisor",
            'approve_spv' => "Done",
            'updated_at'  => Carbon::now(),
            'updated_by'  => Auth::id(),

        ];
        $overtime =[
            'id_overtime' => $id,
            'status_by'   => $mine->id,
            'status_app'  => "Approved",
            'approval_by' => "Supervisor",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $qry2 = Req_OvertimeModel::where('id',$id)->update($app);
        $qry3 = Req_OvertimeApp::create($overtime);
        }
        else if($type=="HRD"){
        $app=[
            'status'     => "Submitted",
            'approve_hr' => "Done",
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id(),

        ];
        $overtime =[
            'id_overtime' => $id,
            'status_by'   => $mine->id,
            'status_app'  => "Approved",
            'approval_by' => "HRD",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $qry2 = Req_OvertimeModel::where('id',$id)->update($app);
        $qry3 = Req_OvertimeApp::create($overtime);
        }
        return redirect('hrm/request/overtime')->with('success','Approved Successfully');
    }

    public function reject(Request $request)
    {
        $id   = $request->segment(5);
        $type = $request->segment(6);
        $mine = getUserEmp(Auth::id());

        if ($type=="SPV"){
        $app=[
            'status'      => "Reject By Supervisor",
            'approve_spv' => "Done",
            'updated_at'  => Carbon::now(),
            'updated_by'  => Auth::id(),

        ];
        $overtime =[
            'id_overtime' => $id,
            'status_by'   => $mine->id,
            'status_app'  => "Reject By HRD",
            'approval_by' => "Supervisor",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $qry2 = Req_OvertimeModel::where('id',$id)->update($app);
        $qry3 = Req_OvertimeApp::create($overtime);
        }
        else if($type=="HRD"){
        $app=[
            'status'     => "Reject",
            'approve_hr' => "Done",
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id(),

        ];
        $overtime =[
            'id_overtime' => $id,
            'status_by'   => $mine->id,
            'status_app'  => "Reject",
            'approval_by' => "HRD",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $qry2 = Req_OvertimeModel::where('id',$id)->update($app);
        $qry3 = Req_OvertimeApp::create($overtime);
        }
            return redirect('hrm/request/overtime')->with('success','Reject Successfully');
    }

    

     public function create()
     {
         $emp = EmployeeModel::all();
         $div = Role_division::all();
         return view('hrm.Overtime.create',[
             'employee' => $emp,
             'division' => $div,
             'method'   => 'post',
             'action'   => 'HR/Req_OvertimeController@store'
         ]);
     }


     public function edit($id)
     {
        $data = Req_OvertimeModel::where('id', $id)->first();
        // dd($data);
        return view('hrm.Overtime.edit',[
            'division_id' => $this->AllDivision(),
            'employee_id' => $this->AllEmployee(),
            'getdata'     => $data,
            'method'      => 'put',
            'action'      => ['HR\Req_OvertimeController@update',$id],
        ]);
     }

     public function update(Request $request, $id)
     {
        return $this->save($request, 'updated', $id)->with('Success', 'Data Updated Successfully');
     }

    public function store(Request $request)
     {
        return $this->save($request, 'created');
     }

     public function save($request, $save, $id=0)
     {
        $division = getEmp($request->employee_id);
        $data     = [
                              'employee_id'     => $request->input('employee_id'),
                              'division_id'     => $division->division_id,
                              'date'            => $request->input('date'),
                              'status'          => "Pending",
                              'purpose'         => $request->input('purpose'),
                              'overtime_from'   => $request->input('overtime_from'),
                              'overtime_finish' => $request->input('overtime_finish'),
                        $save . '_by'           => Auth::id()
        ];
        $qry = $save == 'created' ? Req_OvertimeModel::create($data) : Req_OvertimeModel::where('id', $id)->update($data);
        if ($qry) {
            return redirect('hrm/request/overtime/')->with('success', 'Overtime Created successfully');
        }
     }

       public function AllDivision()
    {

        $data = Role_division::all();
        $arr  = array();
        foreach ($data as $reg) {
           
            
            $arr[$reg->id] = $reg->div_name;
        }
        return $arr;
    }

        public function AllEmployee()
    {

        $data = EmployeeModel::all();
        $arr  = array();
        foreach ($data as $reg) {
           
            
            $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
       
    }

     public function destroy($id)
    {
        $ov = Req_OvertimeModel::findOrFail($id);
        $ov->delete();

        return redirect()->route('Overtime.index')
        ->with('success', 'Data deleted successfully');
    }

public function time()
{
    $data = Req_OvertimeModel::all();
    $time = Req_OvertimeModel::where('overtime_from', $data->overtime_from)->first();
    $time = Carbon::parse($data->time_from)->format('H:i:s');

}
}