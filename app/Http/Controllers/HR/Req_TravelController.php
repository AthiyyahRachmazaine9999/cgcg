<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HR\Req_TravelModel;
use App\Models\HR\Req_TravelApproval;
use App\Models\role_division;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use App\Models\HR\EmployeeModel;
use Auth;
use Carbon\Carbon;

class Req_TravelController extends Controller
{
    public function index()
    {
        return view ('hrm.Req_Travel.index');
    }

public function ajax_data(Request $request)
     {
        $mine = getUserEmp(Auth::id());
        if($mine->division_id==8 || $mine->division_id==3){
            return $this->ajax_all($request);
        }
        else{
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'destination',
             3 => 'purpose',
             4 => 'status',
             5 => 'created_at',
             6 => 'req_travel.id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Req_TravelModel::where('employee_id', $mine->id_emp);
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_TravelModel::select('*')->where('employee_id', $mine->id_emp)
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_TravelModel::where('employee_id', $mine->id_emp)->where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = Req_TravelModel::where('employee_id', $mine->id_emp)->where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'division_id' => div_name($post->division_id),
                    'destination' => city($post->des_kota),
                    'purpose'     => $post->purpose,
                    'status'      => $post->status,
                    'approval_by' => $post->approval_by,
                    'created_at'  => $post->created_at->format('d-m-Y'),
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

public function ajax_all(Request $request)
     {
        $mine = getUserEmp(Auth::id());
         $columns = array(
             0 => 'employee_id',
             1 => 'division_id',
             2 => 'destination',
             3 => 'purpose',
             4 => 'status',
             5 => 'created_at',
             6 => 'req_travel.id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = Req_TravelModel::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_TravelModel::select('*')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_TravelModel::where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = Req_TravelModel::where('emp_id', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'employee_id' => emp_name($post->employee_id),
                    'division_id' => div_name($post->division_id),
                    'destination' => city($post->des_kota),
                    'purpose'     => $post->purpose,
                    'status'      => $post->status,
                    'approval_by' => $post->approval_by,
                    'created_at'  => $post->created_at->format('d-m-Y'),
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

     public function create()
     {   
         $emp = EmployeeModel::all();
         $div = role_division::all();
         return view('hrm.Req_Travel.create',[
            'employee' => $emp,
            'division' => $div,
            'province' => $this->get_province(),
            'city'     => [],
            'method'   => 'post',
            'action'   => 'HR\Req_TravelController@store'
         ]);
     }

public function edit($id)
    {   
         $data = Req_TravelModel::where('id', $id)->first();
        // dd($data->des_provinsi);
         return view('hrm.Req_Travel.edit',[
            'division_id' => $this->AllDivision(),
            'employee_id' => $this->AllEmp(),
            'province'    => $this->get_province(),
            'city'        => $this->get_city(),
            'getdata'     => $data,
            'method'      => 'put',
            'action'      => ['HR\Req_TravelController@update',$id],
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        return $this->save($request, 'update', $id)->with('success', 'Data updated successfully');
    }

 public function store(Request $request)
 {
    return $this->save($request, 'created');
 }

public function save($request, $save, $id=0)
    {
        $division = getEmp($request->employee_id);
        $data     = [
                          'employee_id'         => $request->input('employee_id'),
                          'division_id'         => $division->division_id,
                          'purpose'             => $request->input('purpose'),
                          'des_kota'            => $request->input('des_kota'),
                          'des_provinsi'        => $request->input('des_provinsi'),
                          'departure_transport' => $request->input('departure_transport'),
                          'return_transport'    => $request->input('return_transport'),
                          'akomodasi'           => $request->input('akomodasi'),
                          'est_biaya'           => $request->input('est_biaya'),
                          'time_departure'      => $request->input('time_departure'),
                          'status'              => "Pending",
                          'keterangan'          => $request->input('keterangan'),
                          'date_departure'      => $request->input('date_departure'),
                          'date_return'         => $request->input('date_return'),
                    $save . '_by'               => Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? Req_TravelModel::create($data) : Req_TravelModel::where('id', $id)->update($data);
        if ($qry) {
            return redirect('hrm/request/travel/')->with('success', 'Request Travel Created successfully');
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


public function AllEmp()
    {

        $data = EmployeeModel::all();
        $arr  = array();
        foreach ($data as $reg) {
           
            
            $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
    }

public function Emp_finance()
    {

        $data = EmployeeModel::where('division_id', 3)->get();
        $arr  = array();
        foreach ($data as $reg) {           
            $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
    }

public function Emp_HRD()
    {

        $data = EmployeeModel::where('division_id', 8)->get();
        $arr  = array();
        foreach ($data as $reg) {           
            $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
    }

    
public function Emp_Manage()
    {

        $data = EmployeeModel::where('division_id', 7)->get();
        $arr  = array();
        foreach ($data as $reg) {           
            $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
    }


public function show($id)
    {
        $mine     = getUserEmp(Auth::id());
        $usr      = getEmp(Auth::id());
        $data     = Req_TravelModel::find($id);
        $st       = $data->status;
        $fappr    = Req_TravelApproval::where('id_travel', $id)->first();
        $approval = Req_TravelApproval::where('id_travel', $id)->get();
        if($fappr!=null){
            foreach ($approval as $App){
                if($App->status_by==$mine->id)
                {
                    $info = array (
                        'BtnApp'  => 'disabled',
                        'BtnApp2' => 'disabled',
                    );
                } elseif($st=="Reject" || $st=="Rejected By Finance" || $st=="Rejected By HRD") {
                    $info = array (
                        'BtnApp'  => 'disabled',
                        'BtnApp2' => 'disabled',
                    );
                } else if($st=="Approved By Finance" && $mine->division_id==3){
                        $info = array(
                            'BtnApp'  => "disabled",
                            'BtnApp2' => "disabled",
                        );
                } else if($st=="Approved By Finance" && $mine->division_id!=3){
                        $info = array(
                            'BtnApp'  => "",
                            'BtnApp2' => "",
                        );
                }else if($st=="Submitted" && $mine->division_id==8 || $st="Submitted" && $mine->id==2){
                        $info = array(
                            'BtnApp'  => "disabled",
                            'BtnApp2' => "disabled",
                        );
                }else {
                    $info = array(
                    'BtnApp'  => "",
                    'BtnApp2' => "",
                    );
                }
            }
        }
        else if($st=="Reject" || $st=="Rejected By Finance" || $st=="Rejected By HRD"){
                $info = array(
                    'BtnApp'  => "",
                    'BtnApp2' => "disabled",
                );
        } else if($st=="Approved By Finance"){
                $info = array(
                    'BtnApp'  => "",
                    'BtnApp2' => "",
                );
        } else if($st=="Pending" && $mine->id==2 || $data->appby_finance=="" && $mine->divsision_id==8){
                $info = array(
                    'BtnApp'  => "disabled",
                    'BtnApp2' => "disabled",
                );                
        }else{
                $info = array(
                    'BtnApp'  => "",
                    'BtnApp2' => "",
                );
        }
    
         $div      = role_division::all();
         $approval = Req_TravelApproval::where('id_travel', $id)->get();
         $join     = Req_TravelModel::where('req_travel.id', $id)
        ->join('employees as e', 'e.id', '=', 'req_travel.employee_id')->first();
        return view('hrm.Req_Travel.show',[
            'data'         => $data,
            'info'         => $info,
            'fappr'        => $fappr,
            'approval'     => $approval,
            'emp_finance'  => $this->Emp_finance(),
            'emp_HRD'      => $this->Emp_HRD(),
            'emp_Manage'   => $this->Emp_Manage(),
            'employee'     => emp_name($data->employee_id),
            'division'     => div_name($data->division_id),
            'des_kota'     => city($data->des_kota),
            'des_provinsi' => province($data->des_provinsi),
            'div'          => $div,
            'join'         => $join,
            'mine'         => $mine,
        ]);
        
    }

    public function approve_travel(Request $request)
    {
        // dd($request);
        $id  = $request->segment(5);
        $div = $request->segment(6);
        $emp = $request->segment(7);
        if($div=="Finance"){
        $app=[
            'id_travel'   => $id,
            'approval_by' => "Finance",
            'status_by'   => $emp,
            'status_app'  => "Approved",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $travel = [
            'status'        => "Approved By Finance",
            'appby_finance' => "Done",
            'update_by'     => Auth::id(),
            'updated_at'    => Carbon::now(),
        ];
        $qry2 = Req_TravelApproval::create($app);
        $tr   = Req_TravelModel::where('id', $id)->update($travel);
    } else if($div=="HRD"){
        $app=[
            'id_travel'   => $id,
            'approval_by' => "HRD",
            'status_by'   => $emp,
            'status_app'  => "Approved",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $travel = [
            'status'     => "Submitted",
            'appby_hrd'  => "Done",
            'update_by'  => Auth::id(),
            'updated_at' => Carbon::now(),
        ];
        $qry2 = Req_TravelApproval::create($app);
        $tr   = Req_TravelModel::where('id', $id)->update($travel);
    }
        return redirect('hrm/request/travel')->with('success','Approved Successfully');
    }

    public function destroy($id)
    {
        $travel = Req_TravelModel::findOrFail($id);
        $travel->delete();

        return redirect()->route('travel.index')
        ->with('success', 'Data deleted successfully');
    }

    public function reject(Request $request)
    {
        $id  = $request->segment(5);
        $div = $request->segment(6);
        $emp = $request->segment(7);
        if($div=="Finance"){
        $app=[
            'id_travel'   => $id,
            'approval_by' => "Finance",
            'status_by'   => $emp,
            'status_app'  => "Reject",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $travel = [
            'status'        => "Rejected By Finance",
            'appby_finance' => "Done",
            'update_by'     => Auth::id(),
            'updated_at'    => Carbon::now(),
        ];
        $qry2 = Req_TravelApproval::create($app);
        $tr   = Req_TravelModel::where('id', $id)->update($travel);
    } else if($div=="HRD"){
        $app=[
            'id_travel'   => $id,
            'approval_by' => "HRD",
            'status_by'   => $emp,
            'status_app'  => "Reject",
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];
        $travel = [
            'status'     => "Rejected By HRD",
            'appby_hrd'  => "Done",
            'update_by'  => Auth::id(),
            'updated_at' => Carbon::now(),
        ];
        $qry2 = Req_TravelApproval::create($app);
        $tr   = Req_TravelModel::where('id', $id)->update($travel);
    }
        return redirect('hrm/request/travel')->with('success','Rejected Successfully');
    }
    
    public function get_province()
    {

        $data = Provinsi::all();
        $arr  = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }


    public function get_city()
    {

        $data = Kota::all();
        $arr  = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->kota);
        }
        return $arr;
    }


}