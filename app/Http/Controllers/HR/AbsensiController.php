<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\AbsensiModel;
use App\Models\HR\EmployeeModel;
use App\Models\Role\UserModel;
use Illuminate\Http\Request;
use Auth;
class AbsensiController extends Controller
{
        public function index(Request $request)
    {
       
        return view('Absensi.index');
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
  public function timeZone($location){
        return date_default_timezone_set($location);
    }

    public function create()
    {
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d");
        $cek_absen = AbsensiModel::where(['user_id' => $user_id, 'date' => $date])
                            ->get()
                            ->first();
        //dd($cek_absen);
        if (is_null($cek_absen)) {
            $info = array(
                "status" => "Belum mengisi absensi!",
                "btnIn" => "",
                "btnOut" => "disabled");
        } elseif ($cek_absen->time_out == NULL) {
            $info = array(
                "status" => "Jangan lupa Check Out",
                "btnIn" => "disabled",
                "btnOut" => "");
        } else {
            $info = array(
                "status" => "Absensi hari ini telah selesai!",
                "btnIn" => "disabled",
                "btnOut" => "disabled");
        }

        $data_absen = AbsensiModel::where('id', $user_id,)
                        ->orderBy('date', 'desc')
                        ->first();

       // dd($data_absen);
        return view('Absensi.create', compact('data_absen', 'info'));
    }


  public function store(Request $request){

        return $this->save($request, 'created');
    }

 public function update( Request $request, $id)
    { 
        
        return $this->checkOut($request, 'update',$id);
    }
   
 public function checkOut($request, $save, $id=0)
    {
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d"); // 2017-02-01
        $time = date("H:i:s");
        // 12:31:20
        $note = $request->note;

       $presensi = AbsensiModel::where([
            ['user_id','=',auth()->user()->id],
            ['date','=',$date],
        ])->first();
        
        $dt=[
            'time_out' => $time,
            'note' => $note,
        ];

        if ($presensi->time_out == ""){
            $presensi->update($dt);
            return redirect('hrm/absensi/')->with('success', 'Check Out successfully');
        }else{
            return redirect('hrm/absensi/create')->with('Done!');
        }
    
    }

    
    public function save($request, $save, $id=0)
    {
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d"); // 2017-02-01
        $time = date("H:i:s");
        // 12:31:20
        $note = $request->note;
       
        $presensi = AbsensiModel::where([
            ['user_id','=',auth()->user()->id],
            ['date','=',$date],
        ])->first();
        if ($presensi){
            return redirect('hrm/absensi/create')->with('Done!');
        }else{
            AbsensiModel::create([
                'user_id' => auth()->user()->id,
                'date' => $date,
                'time_in' => $time,
                'note' => $note,
            ]);
        }
            return redirect('hrm/absensi/')->with('success', 'Check In successfully');
    }

  public function destroy($id)
    {
        $absensi = AbsensiModel::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.index')
        ->with('success', 'Absensi deleted successfully');
    }


    public function ajax_data(Request $request)
     {
         $columns = array(
             0 => 'user_id',
             1 => 'date',
             2 => 'time_in',
             3 => 'time_out',
             4 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = AbsensiModel::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = AbsensiModel::select('*')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = AbsensiModel::where('cat_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = AbsensiModel::where('cat_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                $data[] = [
                    'user_id'  => user_name($post->user_id),
                    'date'     => $post->date,
                    'time_in'  => $post->time_in,
                    'time_out' => $post->time_out,
                    'note'     => $post->note,
                    'id'       => $post->id,
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
