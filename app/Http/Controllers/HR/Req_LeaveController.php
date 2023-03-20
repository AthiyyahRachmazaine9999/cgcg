<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HR\Req_LeaveModel;
use App\Models\HR\ReqLeaveSpecial;
use App\Models\HR\Req_LeaveApp;
use App\Models\HR\Req_MassLeave;
use App\Models\HR\Req_MassLeaveDetail;
use App\Models\role_division;
use App\Models\HR\EmployeeModel;
use Auth;
use Carbon\Carbon;
use DB;

class Req_LeaveController extends Controller
{
     public function index()
    {
        return view('hrm.Leave.index',[
            'id' => Auth::id(),
        ]);
    }

 public function ajax_data(Request $request)
     {
        $mine = getUserEmp(Auth::id());
        $emp  = EmployeeModel::where('spv_id', $mine->id)->get()->count();
        $div = $request->session()->put('division_id', $mine->division_id);
        if($mine->division_id == 7 || in_array($mine->id,explode(',',getConfig('list_hr')))){
            return $this->ajax_dataManagement($request);
        }else if($emp!=0){
            return $this->ajax_spv($request);
        }
        else{
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'type_leave',
             3 => 'purpose',
             4 => 'status',
             5 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
         
         $menu_count    = Req_LeaveModel::where('employee_id', getUserEmp(Auth::id())->id)->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_LeaveModel::select('*')->where('employee_id', getUserEmp(Auth::id())->id)->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_LeaveModel::join('employees', 'employees.id','=', 'req_leave.employee_id')
                 ->where('req_leave.employee_id', getUserEmp(Auth::id())->id)
                 ->where('emp_name', 'like', '%' . $search . '%')
                 ->orderby('req_leave.'.$order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = Req_LeaveModel::join('employees', 'employees.id','=', 'req_leave.employee_id')
                 ->where('req_leave.employee_id', getUserEmp(Auth::id())->id)
                 ->where('emp_name', 'like', '%' . $search . '%')
                 ->orderby('req_leave.'.$order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                if($post->type_leave=="Annual Leave")
                {
                    $start_date = Carbon::parse($post->date_from)->format('d F Y');
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Special Leave")
                {
                    $start_date = Carbon::parse($post->date_from)->format('d F Y');
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Permission")
                {
                    $start_date = null;
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Late Permission")
                {
                    $start_date = Carbon::parse($post->created_at)->format('d F Y');
                    $end_date   = Carbon::parse($post->time_finish)->format('H:i:s');
                }
                
            $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'type_leave'  => $post->type_leave,
                    'purpose'     => $post->purpose,
                    'user'        => "other",
                    'status'      => $post->status,
                    'no_user'     => Auth::id(),
                    'note'        => $post->note,
                    'start'       => $start_date,
                    'end'         => $end_date,
                    'created_by'  => $post->created_by,
                    'created_at'  => Carbon::parse($post->created_at)->format('Y-m-d'),
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

    public function ajax_dataManagement(Request $request)
        {
         $mine = getUserEmp(Auth::id());
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'type_leave',
             3 => 'purpose',
             4 => 'status',
             5 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Req_LeaveModel::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_LeaveModel::select('*')
             ->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "F" end) as status_sort'))
             ->addSelect(DB::raw('(case when date_finish IS NOT NULL then date_finish else created_at end) as dates'))
             ->orderBy('status_sort', 'asc')->orderBy('dates', 'desc')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = EmployeeModel::join('req_leave', 'req_leave.employee_id','=', 'employees.id')->where('emp_name', 'like', '%' . $search . '%')
                 ->orderby('req_leave.'.$order, $dir)->orderBy('date_finish', 'desc')->orderBy('employees.created_at', 'desc')->offset($start)->limit($limit)->get();
             $totalFiltered = EmployeeModel::join('req_leave', 'req_leave.employee_id','=', 'employees.id')->where('emp_name', 'like', '%' . $search . '%')
                 ->orderby('req_leave.'.$order, $dir)->orderBy('date_finish', 'desc')->orderBy('employees.created_at', 'desc')->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {

                if($post->type_leave=="Annual Leave")
                {
                    $start_date = Carbon::parse($post->date_from)->format('d F Y');
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Special Leave")
                {
                    $start_date = Carbon::parse($post->date_from)->format('d F Y');
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Permission")
                {
                    $start_date = null;
                    $end_date   = $post->date_finish == null ? Carbon::parse($post->created_at)->format('d F Y') : Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Late Permission")
                {
                    $start_date = Carbon::parse($post->created_at)->format('d F Y');
                    $end_date   = Carbon::parse($post->time_finish)->format('H:i:s');
                }else{
                    $start_date = null;
                    $end_date   = null;
                }

                $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'type_leave'  => $post->type_leave,
                    'purpose'     => $post->purpose,
                    'lama_cuti'   => $post->lama_cuti,
                    'status'      => $post->status,
                    'no_user'     => Auth::id(),
                    'user'        => in_array($mine->id,explode(',',getConfig('list_hr'))) ? "hrs" : "manage",
                    'note'        => $post->note,
                    'start'       => $start_date,
                    'end'         => $end_date,
                    'created_by'  => $post->created_by,
                    'created_at'  => Carbon::parse($post->created_at)->format('Y-m-d'),
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

    public function ajax_spv(Request $request)
    {
        $mine    = getUserEmp(Auth::id());
        $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'type_leave',
             3 => 'purpose',
             4 => 'status',
             5 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Req_LeaveModel::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_LeaveModel::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))
             ->orderBy('status_sort', 'asc')->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_LeaveModel::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))
                 ->join('employees', 'employees.id','=', 'req_leave.employee_id')->where('emp_name', 'like', '%' . $search . '%')
                 ->orderby('req_leave.'.$order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = Req_LeaveModel::select('*')->addSelect(DB::raw('(case when (status = "Pending") then "A" when (status = "Approved") then "C" when (status = "Completed") then "D" else "Z" end) as status_sort'))
                 ->join('employees', 'employees.id','=', 'req_leave.employee_id')->where('emp_name', 'like', '%' . $search . '%')
                 ->orderby('req_leave.'.$order, $dir)->offset($start)->limit($limit)->count();
         }
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                if($post->type_leave=="Annual Leave")
                {
                    $start_date = Carbon::parse($post->date_from)->format('d F Y');
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Special Leave")
                {
                    $start_date = Carbon::parse($post->date_from)->format('d F Y');
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Permission")
                {
                    $start_date = null;
                    $end_date   = Carbon::parse($post->date_finish)->format('d F Y');

                }else if($post->type_leave=="Late Permission")
                {
                    $start_date = Carbon::parse($post->created_at)->format('d F Y');
                    $end_date   = Carbon::parse($post->time_finish)->format('H:i:s');
                }
                                $created = getUserEmp($post->created_by)->spv_id;
                if($created == getUserEmp(Auth::id())->id || $post->created_by == Auth::id()){
                    $data[] = [
                        'employee_id' => emp_name($post->employee_id),
                        'division_id' => div_name($post->division_id),
                        'type_leave'  => $post->type_leave,
                        'purpose'     => $post->purpose,
                        'lama_cuti'   => $post->lama_cuti,
                        'user'        => "spv",
                        'no_user'     => Auth::id(),
                        'note'        => $post->note,
                        'start'       => $start_date,
                        'end'         => $end_date,
                        'status'      => $post->status,
                        'created_by'  => $post->created_by,
                        'created_at'  => Carbon::parse($post->created_at)->format('Y-m-d'),
                        'id'          => $post->id,
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

 public function create()
    {
         $emp     = EmployeeModel::all();
         $div     = role_division::all();   
        return view('hrm.Leave.create',[
            'employee' => $emp,
            'division' => $div,
            'id_emp'   => getUserEmp(Auth::id())->id,
            'arrsleave'=> $this->CategorySpecialLeave(),
            'usr'      => Auth::id(),
            'purpose'  => $this->getSpecialLeave(),
            'method'   => "post",
            'action'   => "HR\Req_LeaveController@store"
        ]);    
    }


    function CategorySpecialLeave()
    {
        $data = ReqLeaveSpecial::all();
        $arr  = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->note;
        }
        return $arr;
    }



    public function show($id)
    {
        $data   = Req_LeaveModel::find($id);
        $le_app = Req_LeaveApp::where('id_leave',$id)->first();
        $app    = Req_LeaveApp::where('id_leave',$id)->get();
        
        $mine = getUserEmp(Auth::id());
        $emp  = getEmp($data->employee_id);
        $st   = $data->status;
        
        return view('hrm.Leave.show',[
            'le_app'   => $le_app,
            'app'      => $app,
            'mine'     => $mine,
            'emp'      => $emp,
            'data'     => $data,
            'employee' => emp_name($data->employee_id),
            'division' => div_name($data->division_id),
        ]);
        
    }


    public function SpecialForm()
    {
        return view('hrm.Leave.attribute.specialform',[
        'arrsleave' => $this->CategorySpecialLeave(),
        'id_emp'    => getUserEmp(Auth::id())->id,
        ]);
        
    }

    public function AnnualForm()
    {
        $emp_id        = getUserEmp(Auth::id());
        $check_month   = Carbon::now()->format('n');
        $check_leave   = Req_LeaveModel::where([['created_by', Auth::id()],['type_leave', 'Annual Leave']])->get()->count();
        $emp           = EmployeeModel::where('id', $emp_id->id)->first();
        $tgl_bergabung = $emp->tgl_bergabung;
        $newDateTime   = Carbon::parse($tgl_bergabung)->subMonth();
        // dd($emp, $tgl_bergabung, $newDateTime);
        return view('hrm.Leave.attribute.annualform',[
            'id_emp'      => $emp_id->id,
        ]);
    }


    public function PermissionForm()
    {
        return view('hrm.Leave.attribute.permissionform',[
            'id_emp'      => getUserEmp(Auth::id())->id,
        ]);
    }

    public function LateForm()
    {
        return view('hrm.Leave.attribute.lateform',[
            'id_emp'      => getUserEmp(Auth::id())->id,
        ]);
    }


    public function approve(Request $request)
    {
        // dd($request);
        $id   = $request->segment(5);
        $type = $request->segment(6);
        $mine = getUserEmp(Auth::id());
        $data = Req_LeaveModel::where('id',$id)->first();
        if($type=="SPV")
        {
        $app=[
            'status'     => "Approved",
            'app_spv'    => Auth::id(),
        ];
        $leave = [
            'id_leave'    => $id,
            'status_by'   => $mine->id,
            'created_by'  => Auth::id(),
            'status_app'  => "Approved",
            'approval_by' => "Supervisor",
        ];
        $qry2 = Req_LeaveModel::where('id',$id)->update($app);
        $qry3 = Req_LeaveApp::create($leave);
        } 
        else if($type=="HRD")
        {
        $app=[
            'status'     => "Completed",
            'app_hr'     => Auth::id(),
        ];
        $leave = [
            'id_leave'    => $id,
            'status_by'   => $mine->id,
            'created_by'  => Auth::id(),
            'status_app'  => "Approved",
            'approval_by' => "HRD",
        ];
        $qry2 = Req_LeaveModel::where('id',$id)->update($app);
        $qry3 = Req_LeaveApp::create($leave);
        }
        return redirect('hrm/request/leave')->with('success', 'Approval Succesfully');
    }


    
    public function reject(Request $request)
    {
        $id   = $request->segment(5);
        $type = $request->segment(6);
        $mine = getUserEmp(Auth::id());
        $data = Req_LeaveModel::where('id',$id)->first();
        if($type=="SPV")
        {
        $app=[
            'status'     => "Rejected",
            'app_spv'    => Auth::id(),
        ];
        $leave = [
            'id_leave'    => $id,
            'status_by'   => $mine->id,
            'created_by'  => Auth::id(),
            'status_app'  => "Rejected",
            'approval_by' => "Supervisor",
        ];
        $qry2 = Req_LeaveModel::where('id',$id)->update($app);
        $qry3 = Req_LeaveApp::create($leave);
        } 
        else if($type=="HRD")
        {
        $app=[
            'status'     => "Rejected",
            'app_hr'     => Auth::id(),
        ];
        $leave = [
            'id_leave'    => $id,
            'status_by'   => $mine->id,
            'created_by'  => Auth::id(),
            'status_app'  => "Rejected",
            'approval_by' => "HRD",
        ];
        $qry2 = Req_LeaveModel::where('id',$id)->update($app);
        $qry3 = Req_LeaveApp::create($leave);
        }
    return redirect('hrm/request/leave')->with('success', 'Rejected Succesfully');
    }

  public function store(Request $request)
    {
        return $this->save($request,'created');
    }

    public function edit($id)
    {   
        $data = Req_LeaveModel::where('id', $id)->first();
        return view('hrm.Leave.edit',[
            'division_id' => $this->AllDivision(),
            'employee_id' => $this->AllEmployee(),
            'getdata'     => $data,
            'mine'        => getUserEmp(Auth::id()),
            'method'      => 'put',
            'action'      => ['HR\Req_LeaveController@update',$id],
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        return $this->saveUpdate($request)->with('success', 'Data updated successfully');
    }

    public function save($request, $save, $id=0)
    {
        $mine  = getUserEmp(Auth::id());
        // dd($request, strtotime($request->input('time_finish')));
        if($request->type_leave == "Permission")
        {
        if($request->has('file_sakit')){
            $new = $request->file('file_sakit');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->file_sakit->storeAs('public/hr_permission',$newName);
        }else{
            $newName= null;
        }

        $data       = [
                    'employee_id' => getUserEmp($request->employee_id)->id,
                    'type_leave'  => "Permission",
                    'purpose'     => $request->input('purpose_permit'),
                    'note'        => $request->input('note'),
                    'status'      => "Pending",
                    'date_finish' => Carbon::createFromFormat('d/m/Y', $request->read_finish)->format('Y-m-d'),
                    'created_by'  => Auth::id(),
                    'file_permit' => $newName,
                    'created_at'  => Carbon::now(),
        ];
        $qry = Req_LeaveModel::create($data);
        }else if($request->type_leave == "Late Permission") {
        $data       = [
                    'employee_id' => getUserEmp($request->employee_id)->id,
                    'type_leave'  => "Late Permission",
                    'purpose'     => $request->input('purpose'),
                    'note'        => $request->input('note'),
                    'status'      => "Pending",
                    'time_finish' => date("G:i", strtotime($request->input('time_finish'))),
                    'created_by'  => Auth::id(),
                    'created_at'  => Carbon::now(),
        ];
        // dd($data);
        $qry = Req_LeaveModel::create($data);
        } else{
            // dd($request);
        $type_leave = Req_LeaveModel::where('id', $id)->first();
        $type       = Req_LeaveModel::select('type_leave')->where('id', $id);
        $data       = [
                    'employee_id' => getUserEmp($request->employee_id)->id,
                    'type_leave'  => $request->input('type_leave'),
                    'purpose'     => $request->type_leave=="Annual Leave" ? $request->input('purpose') : $request->input('purpose_leave_array'),
                    'note'        => $request->input('note'),
                    'status'      => "Pending",
                    'lama_cuti'   => $request->input('lama_cuti'),
                    'date_from'   => $request->input('date_from'),
                    'date_finish' => $request->input('date_finish'),
                    'created_by'  => Auth::id(),
                    'created_at'  => Carbon::now(),
        ];
        $qry = Req_LeaveModel::create($data);
        }
        if ($qry) {
            return redirect('hrm/request/leave')->with('success', 'Leave Created successfully');
        }
    }


    public function value_chance(Request $request)
    { 
        // dd($request);
        $data = ReqLeaveSpecial::where('id', $request->id)->first();        
        return response()->json([
            'data' =>[
                'days' => $data->chance,
            ],
        ]);
    }



    public function saveUpdate($request)
    { 
        // dd($request);
        $leave = Req_LeaveModel::where('id', $request->id)->first();
        $mine  = getUserEmp(Auth::id());
        if($request->type_leave == "Permission")
        {

        if($request->has('file_sakit')){
            $new = $request->file('file_sakit');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->file_sakit->storeAs('public/hr_permission',$newName);
        }else{
            $newName = $leave->file_permit;
        }
        $data       = [
                    'employee_id' => $request->employee_id,
                    'type_leave'  => "Permission",
                    'purpose'     => $request->input('purpose'),
                    'note'        => $request->input('note'),
                    'status'      => in_array($mine->id,explode(',',getConfig('list_hr'))) ? $leave->status : "Pending",
                    'file_permit' => $newName,
                    'date_finish' => Carbon::parse($request->read_finish)->format('Y-m-d'),
                    'updated_by'  => Auth::id(),
                    'updated_at'  => Carbon::now(),
        ];
        $qry = Req_LeaveModel::where('id', $request->id)->update($data);
        }else if($request->type_leave == "Late Permission") {
            $data       = [
                    'employee_id' => $request->employee_id,
                    'type_leave'  => "Late Permission",
                    'purpose'     => $request->input('purpose'),
                    'note'        => $request->input('note'),
                    'status'      => in_array($mine->id,explode(',',getConfig('list_hr'))) ? $leave->status : "Pending",
                    'time_finish' => date("G:i", strtotime($request->input('time_finish'))),
                    'updated_by'  => Auth::id(),
                    'updated_at'  => Carbon::now(),
        ];
        $qry = Req_LeaveModel::where('id', $request->id)->update($data);
        } else{
        $data       = [
                    'employee_id' => $request->employee_id,
                    'type_leave'  => $request->input('type_leave'),
                    'purpose'     => $request->input('purpose'),
                    'note'        => $request->input('note'),
                    'status'      => in_array($mine->id,explode(',',getConfig('list_hr'))) ? $leave->status : "Pending",
                    'lama_cuti'   => $request->input('lama_cuti'),
                    'date_from'   => $request->input('date_from'),
                    'date_finish' => $request->input('date_finish'),
                    'updated_by'  => Auth::id(),
                    'updated_at'  => Carbon::now(),
        ];
        $qry = Req_LeaveModel::where('id', $request->id)->update($data);
        }
        if ($qry) {
            return redirect('hrm/request/leave')->with('success', 'Leave Updated successfully');
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


        public function getSpecialLeave()
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
        // dd($id);
        $le = Req_LeaveModel::findorfail($id);
        $le->delete();

        return route('leave.index');
    }
}