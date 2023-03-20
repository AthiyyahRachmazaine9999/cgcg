<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\UploadAbsen;
use  App\Models\Android\AndroidAbsensi;
use App\Models\Role\UserModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AbsenImport;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Carbon\Carbon;

class UploadAbsenController extends Controller
{

    public function index(Request $request){
        
        $this->timeZone('Asia/Jakarta');
        $Name = Auth::id();
        $cek_absen = AndroidAbsensi::where(['created_by' => $Name])->orderBy('id', 'desc')->first();
        //dd($cek_absen);
        if (is_null($cek_absen)) {
            $info = array(
                "status" => "Belum mengisi absensi!",
                "btnIn" => "",
                "btnOut" => "disabled");
        } elseif ($cek_absen->status == "check-in") {
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

        $data_absen =  AndroidAbsensi::where(['created_by' => Auth::id()])->orderBy('id', 'desc')->first();

        if($data_absen==null)
        {
            $view = "Check In";
        }
        else if($data_absen->status=="check-in")
        {
            $view = "Check Out";
        }else{
            $view = "Check In";
        }
        return view('hrm.UploadAbsen.index',[
            'data_absen'  => $data_absen == null ? null : $data_absen,
            'info'        => $info,
            'view'       => $view,
        ]);
    }



    public function get_location(Request $request)
    {
        $backoffice = CheckLongLat($request->long, $request->lat);
        $decode     = json_decode($backoffice);
        $encode     = $request->type;
        if($encode=="static" && $decode->name!="normal"){
            $text_alamat = $decode->name;
            $long        = $request->long;
            $lats        = $request->lat;
        }else{
            $addres     = $this->getmyaddress($request->lat, $request->long, '18',$request->type);
            if($request->type=="normal"){
                $text_alamat = $addres['display_name'];
                $lats        = $addres['lat'];
                $long        = $addres['lon'];
            }else{
                $text_alamat = $addres['results'][0]['formatted_address'];
                $lats        = $addres['results'][0]['geometry']['location']['lat'];
                $long        = $addres['results'][0]['geometry']['location']['lng'];
            }
        }
        
        return response()->json([
            'success'   => true,
            'latitude'  => $lats,
            'longitude' => $long,
            'alamat'    => $text_alamat,
            'code'      => http_response_code(),
        ]);


    }

        public function getmyaddress($lat, $long, $zoom,$type)
    {
        $cari        = "&lat=" . $lat . '&lon=' . $long . '&zoom=' . $zoom . '&format=json';
        $locationiq  = 'https://us1.locationiq.com/v1/reverse.php?key='.getConfig('locationIQ') . $cari;
        $googleapi   = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&key=".getConfig('googleAPI');
        $json_string = $type == "normal" ? $locationiq : $googleapi;
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
        return $get;

    }


    public function import(Request $request){
        Excel::import (new AbsenImport, $request->file);
        return redirect()->route('absensi.index')
        ->with('success', 'Absensi import successfully');
    }

  public function timeZone($location){
        return date_default_timezone_set($location);
    }

    public function create()
    {
        $this->timeZone('Asia/Jakarta');
        $Name = Auth::user()->id;
        $cek_absen = UploadAbsen::where(['Name' => $Name])
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

        $data_absen = UploadAbsen::where('id', $Name)
                        ->orderBy('Name', 'desc')
                        ->first();

       // dd($data_absen);
        return view('hrm.UploadAbsen.create', compact('data_absen', 'info'));
    }


  public function store(Request $request){
    //   dd($request);
        return $this->save($request, 'created');
    }

 public function update( Request $request, $id)
    { 
        
        return $this->checkOut($request, 'update',$id);
    }
   
 public function checkOut($request, $save, $id=0)
    {
        $this->timeZone('Asia/Jakarta');
        $name = Auth::user()->id;
        $time = date("H:i:s");
        $keterangan = $request->keterangan;

       $presensi = UploadAbsen::where([
            ['Name','=',auth()->user()->id],
            // ['date','=',$date],
        ])->first();
        
        $dt=[
            'time_out' => $time,
            'keterangan' => $keterangan,
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
    $Name = Auth::id();
    $status =  AndroidAbsensi::where(['created_by' => Auth::id()])->orderBy('id', 'desc')->first();
       if($status==null)
       {
           $st = "check-in";
       }else if($status->status=="check-in")
       {
           $st = "check-out";
       }else{
           $st= "check-in";
       }
       $qry =  AndroidAbsensi::create([
            'longitude'  => $request->long,
            'latitude'   => $request->lat,
            'location'   => $request->alamat,
            'time'       => Carbon::now('GMT+7')->format('H:i:s'),
            'status'     => $st,
            'created_at' => Carbon::now('GMT+7'),
            'created_by' => Auth::id(),

        ]);
        if($qry){
        return redirect('hrm/absensi/')->with('success', 'Created Successfully');
    }
}


    
     public function destroy($id)
    {
        $absensi = UploadAbsen::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.index')
        ->with('success', 'Absensi deleted successfully');
    }

    public function ajax_data(Request $request)
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
 
         $menu_count    = AndroidAbsensi::where('created_by', Auth::id())->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = AndroidAbsensi::select('*')->where('created_by', Auth::id())
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = AndroidAbsensi::where('created_by', Auth::id())->where('Name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = AndroidAbsensi::where('created_by', Auth::id())->where('Name', 'like', '%' . $search . '%')
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
}