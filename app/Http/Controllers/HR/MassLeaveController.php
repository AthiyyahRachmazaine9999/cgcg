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


class MassLeaveController extends Controller
{
    public function index()
    {
        return view('hrm.mass_leave.index');
    }


    public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'id',
             1 => 'days',
             2 => 'created_at',
             5 => 'note',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
         
         $menu_count    = Req_MassLeave::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = Req_MassLeave::select('*')
             ->orderBy($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = Req_MassLeave::where('note', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = Req_MassLeave::where('note', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'id'          => $post->id,
                    'note'        => $post->note,
                    'days'        => $post->days." Hari",
                    'created_at'  => Carbon::parse($post->created_at)->format('d F Y'),
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
        return view('hrm.mass_leave.create');
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = [
            'note'       => $request->note,
            'days'       => $request->days,
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => Auth::id(),
        ];
        $qry = Req_MassLeave::create($data);
        if($qry)
        {
            $detail = [
                'id_mass_leave'=> $qry->id,
                'date_of_days' => $request->date,
                'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by'   => Auth::id(),
            ];
            $dtl = Req_MassLeaveDetail::create($detail);

            if($request->has('date_add'))
            {
                $add = $request->date_add;
                foreach ($add as $add => $q)
                {
                $details = [
                    'id_mass_leave'=> $qry->id,
                    'date_of_days' => $request->date_add[$add],
                    'created_at'   => Carbon::now('GMT+7')->toDateTimeString(),
                    'created_by'   => Auth::id(),
                ];
                $dtl = Req_MassLeaveDetail::create($details);
                }
            }
        }
        return redirect('hrm/mass_leave')->with('success', 'Created Succesfully');
    }


    public function getMoreRow(Request $request)
    {
        return view('hrm.mass_leave.addRow',[
        'n_equ'  => $request->n_equ,
        ]);
    }

    public function show($id)
    {
        $mass = Req_MassLeave::where('id', $id)->first();
        $dtl  = Req_MassLeaveDetail::where('id_mass_leave', $id)->get();
        return view('hrm.mass_leave.show',[
        'mass' => $mass,
        'dtl'  => $dtl,
        ]);
    }


    public function edit($id)
    {
        // dd($id);
        $mass = Req_MassLeave::where('id', $id)->first();
        $dtl  = Req_MassLeaveDetail::where('id_mass_leave', $id)->get();
        return view('hrm.mass_leave.edit',[
        'mass' => $mass,
        'dtl'  => $dtl,
        ]);
    }


    public function update(Request $request)
    {
        dd($request);
    }




}