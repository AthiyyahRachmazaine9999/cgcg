<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role\UserModel;
use App\Models\Role\Role_cabang;
use App\Models\Android\AndroidAbsensi;
use App\Models\Android\Android_izin;
use App\Models\HR\Req_LeaveModel;
use App\Models\HR\Req_LeaveApp;
use App\Models\Android\history_login;
use App\Models\Android\AndroidLogin;
use App\Models\Role\Role_address;
use App\Models\Role\Role_division;
use App\Models\HR\EmployeeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;
use Redirect;
use DateTime;

class AndroidAbsensiController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getvers(Request $request)
    {
        return response()->json([
            'success'  => true,
            'version'  => 2,
        ]);
    }
    public function downloadnewvers(Request $request)
    {
        $to = "https://home.maleser.co.id/public/myinternal/homeMaleser.apk";
        return Redirect::to($to);

        // $file    = base_path() . "public/myinternal/homeMaleser.apk";
        // $headers = array(
        //     'Content-Type: application/vnd.android.package-archive',
        // );
        // return response()->download($file, 'homeMaleser.apk', $headers);
    }


    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = UserModel::where('email', $request->email)->first();
            $data = [
                'mac'        => $request->mac,
                'ip'         => \Request::ip(),
                'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
                'created_by' => Auth::id(),
            ];
            AndroidLogin::create($data);
            return response()->json([
                'success' => true,
                'message' => "Successful",
                'rincian' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Unsuccessful",
                'rincian' => null,
            ]);
        }
    }


    public function loginurl(Request $request)
    {
        $token = $request->session()->token();
        dd($token);
        $token = csrf_token();
        $url  = $request->url;
        $myid = UserModel::find($request->myid);
        $test = Auth::login($myid);
        dd($myid);
        // return redirect()->route('hrm/request/travel/create');
    }
    public function getmyaddress(Request $request)
    {
        $store = [
            'longitude'  => $request->longitude,
            'latitude'   => $request->latitude,
        ];
        $check      = AndroidAbsensi::where('created_by', $request->myid)->orderBy('id', 'desc')->first();
        // dd($check);
        if (is_null($check)) {
            $inout        = "check-out";
            $times_in = "no_time";
            $times_out = "no_time";
        } else {
            if ($check->status == "check-in") {
                $inout    = $check->status;
                $times_in = $check->created_at;
                $times_out = "no_time";
            } else {
                $in_time   = AndroidAbsensi::where([['created_by', $request->myid], ['status', 'check-in']])->orderBy('id', 'desc')->first();
                $inout    = $check->status;
                $times_in = $in_time->created_at;
                $times_out = $check->created_at;
            }
        }
        $backoffice = CheckLongLat($request->longitude, $request->latitude);
        $decode     = json_decode($backoffice);
        $encode     = $decode->condition;
        if ($encode == "static") {
            $text_alamat = $decode->name;
        } else {
            $addres     = $this->getLocation($request->latitude, $request->longitude, '18', $request->type);
            if ($request->type == "normal") {
                $text_alamat = $addres['display_name'];
            } else {
                $text_alamat = $addres['results'][0]['formatted_address'];
            }
        }

        return response()->json([
            'success'   => true,
            'condition' => $inout,
            'time_in'   => $times_in != "no_time" ? Carbon::parse($times_in)->toDateTimeString() : "no_time",
            'time_out'  => $times_out != "no_time" ? Carbon::parse($times_out)->toDateTimeString() : "no_time",
            'addres'    => $text_alamat,
            'code'      => http_response_code(),
        ]);
    }


    public function CheckIn(Request $request)
    {
        $backoffice = CheckLongLat($request->longitude, $request->latitude);
        $decode     = json_decode($backoffice);
        $encode     = $decode->condition;
        if ($encode == "static") {
            $text_alamat = $decode->name;
        } else {
            $addres     = $this->getLocation($request->latitude, $request->longitude, '18', $request->type);
            if ($request->type == "normal") {
                $text_alamat = $addres['display_name'];
            } else {
                $text_alamat = $addres['results'][0]['formatted_address'];
            }
        }
        $store = [
            'time'       => Carbon::now('GMT+7')->toDateTimeString(),
            'longitude'  => $request->longitude,
            'latitude'   => $request->latitude,
            'location'   => $text_alamat,
            'note'       => $request->note,
            'status'     => "check-in",
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => $request->myid,
        ];
        $qry = AndroidAbsensi::create($store);
        return response()->json([
            'success' => true,
            'message' => "Successful",
            'addres'  => $text_alamat,
            'time'    => $store['created_at'],
            'code'    => http_response_code(),
        ]);
    }


    public function CheckOut(Request $request)
    {
        $backoffice = CheckLongLat($request->longitude, $request->latitude);
        $decode     = json_decode($backoffice);
        $encode     = $decode->condition;
        if ($encode == "static") {
            $text_alamat = $decode->name;
        } else {
            $addres     = $this->getLocation($request->latitude, $request->longitude, '18', $request->type);
            if ($request->type == "normal") {
                $text_alamat = $addres['display_name'];
            } else {
                $text_alamat = $addres['results'][0]['formatted_address'];
            }
        }
        $datas = [
            'time'       => Carbon::now('GMT+7')->toDateTimeString(),
            'longitude'  => $request->longitude,
            'latitude'   => $request->latitude,
            'location'   => $text_alamat,
            'note'       => $request->note,
            'status'     => "check-out",
            'created_at' => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by' => $request->myid,
        ];
        $qry = AndroidAbsensi::create($datas);
        return response()->json([
            'success' => true,
            'message' => "Successful",
            'addres'  => $text_alamat,
            'time'    => $datas['created_at'],
            'code'    => http_response_code(),
        ]);
    }

    public function getLocation($lat, $long, $zoom, $type)
    {

        $cari        = "&lat=" . $lat . '&lon=' . $long . '&zoom=' . $zoom . '&format=json';
        $locationiq  = 'https://us1.locationiq.com/v1/reverse.php?key=' . getConfig('locationIQ') . $cari;
        $googleapi   = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $long . "&key=" . getConfig('googleAPI');
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


    public function profiles(Request $request)
    {
        $idku    = $request->id;
        $emailku = $request->email;
        $usr     = getUserEmp($request->id);
        if ($usr->email == $emailku) {
            return response()->json([
                'success'  => true,
                'emp'      => $usr,
            ]);
        } else {
            return response()->json([
                'success'  => false,
            ]);
        }
    }

    public function gethelp(Request $request)
    {
        $div     = $request->div;
        return response()->json([
            'success' => true,
            'nomer'   => "+6281211086448",
        ]);
    }


    public function getPermission(Request $request)
    {
        //request -> emp / auth , ket_izin, note lainnya
        $data = [
            'ket_izin'    => $request->ket_izin,
            'ket_lainnya' => $request->ket_lain,
            'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'  => $request->myid,
        ];
        $save = Android_izin::create($data);
        if ($save) {
            return response()->json([
                'success'  => true,
                'message'  => 'Successful'
            ]);
        }
    }




    public function getDailyAbsensi(Request $request)
    {
        $posts_dates = AndroidAbsensi::select(DB::raw('DATE(created_at) as dates'), DB::raw('count(created_at) as counts'))->where('created_by', $request->myid)->groupBy('dates')->orderBy('id', 'desc')->get()->toArray();
        // $dt = json_decode($posts_dates);
        return response()->json([
            'result'    => $posts_dates,
        ]);
    }


    public function getDetailDailyAbsensi(Request $request)
    {
        //request id, dan createdate
        $posts_dates = AndroidAbsensi::select(DB::raw('DATE(created_at) as dates'), 'longitude', 'latitude', 'time', 'status', 'note', 'location')->where('created_by', $request->myid)->whereDate('created_at', $request->date)->orderBy('id', 'asc')->get();
        $dt = json_decode($posts_dates);
        return response()->json([
            'result'    => $dt,
        ]);
    }


    public function dtl_percard(Request $request)
    {
        $posts_dates = AndroidAbsensi::select(DB::raw('DATE(created_at) as dates'), 'longitude', 'latitude', 'time', 'status', 'note', 'location')->where('created_by', $request->myid)->whereDate('created_at', $request->date)->orderBy('id', 'asc')->get();
        $dt = json_decode($posts_dates);
        return response()->json([
            'result'    => $dt,
        ]);
    }


    public function teamLocation(Request $request)
    {
        $idtemp = EmployeeModel::where([
            ['spv_id', getUserEmp($request->myid)->id],
            ['emp_status', 'Active']
        ])->get();
        $result = [];
        if (count($idtemp) > 0) {
            foreach ($idtemp as $emp => $get) {
                $idusr = getIdUser($get->id)->id;
                $check = AndroidAbsensi::where('created_by', $idusr)->get();
                $absen = AndroidAbsensi::select(DB::raw('count(id) as jml'), DB::raw('created_by'), DB::raw('created_at'))->where('created_by', $idusr)->get();
                $counts = AndroidAbsensi::where('created_by', $idusr)->get()->count('id');
                foreach ($absen as $qry) {
                    if ($qry->jml > 0) {
                        $arr[] = array(
                            'jml'      => $qry->jml,
                            'tanggal'   => Carbon::parse($qry->created_at)->format('d/m/Y'),
                            'nama_emp'  => getUserEmp($qry->created_by)->emp_name,
                            'user_id'   => $qry->created_by,
                        );
                        $result = $arr;
                    }
                }
            }
        } else {
            $result = null;
        }

        if (!empty($result)) {
            $datas = $result;
        } else if ($result == null) {
            $datas = null;
        } else {
            $datas = null;
        }

        return response()->json([
            'result'   => $datas,
        ]);
    }



    function getEmp($request)
    {
        //2 user
        $emps = EmployeeModel::where([['spv_id', getUserEmp($request->myid)->id], ['emp_status', 'Active']])->pluck('id');
        if (!empty($emps)) {
            foreach ($emps as $emp => $get) {
                $absen = $get;
            }
        }
        return $absen;
    }



    public function teamhistory(Request $request)
    {
        $idtemp = EmployeeModel::where([
            ['spv_id', getUserEmp($request->myid)->id],
            ['emp_status', 'Active']
        ])->get();
        if (!empty($idtemp)) {
            foreach ($idtemp as $emp => $get) {
                $idusr = getIdUser($get->id)->id;
                $absen = AndroidAbsensi::where('created_by', $idusr)->get();
                if (count($absen) > 0) {
                    $arr[] = array(
                        'test'      => $absen,
                        'jml'       => count($absen),
                    );
                    $result = $arr;
                }
            }
        } else {
            $result = array('results' => null,);
        }
        return response()->json([
            'success' => true,
            'datas'   => $result,
        ]);
    }



    function getCount($qrys)
    {
        $counts =  AndroidAbsensi::where('created_by', $qrys->id)->get()->toArray();
        return $counts;
    }



    function savePermission(Request $request)
    {
        if ($request->type == "izin_telat") {
            $data = [
                'employee_id' => getUserEmp($request->myid)->id,
                'purpose'     => $request->purpose,
                'note'        => $request->notes,
                'type_leave'  => "Late Permission",
                'status'      => "Pending",
                'time_finish' => Carbon::parse($request->times)->format("H:i:s"),
                'created_by'  => $request->myid,
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qry = Req_LeaveModel::create($data);
            if ($qry) {
                return response()->json([
                    'success' => true,
                ]);
            }
        } else if ($request->type == "tidak_masuk") {
            $arr_dm = explode(',', $request->times);

            $data = [
                'employee_id' => getUserEmp($request->myid)->id,
                'purpose'     => $request->purpose,
                'note'        => $request->notes,
                'type_leave'  => "Permission",
                'status'      => "Pending",
                'date_finish' => Carbon::parse($arr_dm[1])->format('Y/m/d'),
                'created_by'  => $request->myid,
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString(),
            ];
            $qry = Req_LeaveModel::create($data);
            if ($qry) {
                return response()->json([
                    'success' => true,
                    'datas'   => $data,

                ]);
            }
        }
    }


    public function saveLeave(Request $request)
    {
        $from          = $request->from;
        $end           = $request->end;
        $dari          = Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d');
        $sampai        = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');
        $break_1_start = DateTime::createFromFormat('Y-m-d', $dari);
        $break_1_end   = DateTime::createFromFormat('Y-m-d', $sampai);
        $s             = $break_1_start->diff($break_1_end);
        // dd($r, $break_1_end,$s);
        // dd( $from,$end,$dari,$sampai, $break_1_end,$s);

        $cr_from  =  Carbon::createFromFormat('d/m/Y', $request->from)->format('Y/m/d');
        $cr_to    =  Carbon::createFromFormat('d/m/Y', $request->end)->format('Y/m/d');
        $fr       =  date('Y-m-d', strtotime($from));
        $n        =  date('Y-m-d', strtotime($end));
        $data     = [
            'employee_id' => getUserEmp($request->myid)->id,
            'type_leave'  => $request->type_leave,
            'purpose'     => $request->type_leave == "Special Leave" ? $request->sel_tujuan : $request->mynote,
            'note'        => $request->real_note,
            'status'      => "Pending",
            'lama_cuti'   => $s->format('%m') + 1 . ' Hari',
            'date_from'   => $cr_from,
            'date_finish' => $cr_to,
            'created_at'  => Carbon::now(),
            'created_by'  => $request->myid,
        ];
        // dd($data);
        $qry = Req_LeaveModel::create($data);
        return response()->json([
            'success'  => true,
        ]);
    }

    public function showleaves(Request $request)
    {
        $year = Carbon::now()->format('Y');
        $day  = Carbon::now()->format('d');
        $mon  = Carbon::now()->format('m');

        $hadir = AndroidAbsensi::where([['created_by', $request->myid], ['status', 'check-in']])
            ->whereMonth('created_at', $mon)->whereYear('created_at', $year)->get()->count();

        $izin = Req_LeaveModel::where('type_leave', '!=', 'Late Permission')->where('created_by', $request->myid)->whereMonth('created_at', $mon)->whereYear('created_at', $year)->get()->count();

        $telat = Req_LeaveModel::where('type_leave', 'Late Permission')->where('created_by', $request->myid)->whereMonth('created_at', $mon)->whereYear('created_at', $year)->get()->count();

        return response()->json([
            'success'  => true,
            'hadir'    => $hadir,
            'telat'    => $telat,
            'izin'    =>  $izin,
        ]);
    }


    function listLeave(Request $request)
    {
        //myid
        $qry = Req_LeaveModel::select('type_leave', 'purpose', 'date_from', 'date_finish', 'time_finish', 'note', 'status', DB::raw('DATE_FORMAT(created_at, "%d-%b-%Y") as tanggalan'))
            ->where('created_by', $request->myid)->orderBy('id', 'desc')->get();
        return response()->json([
            'success'  => true,
            'data'     => $qry,
        ]);
    }



    function listApproval(Request $request)
    {
        //myid
        $emp = EmployeeModel::where([
            ['spv_id', getUserEmp($request->myid)->id],
            ['emp_status', 'Active']
        ])->get();
        if (count($emp) > 0) {
            foreach ($emp as $emp => $get) {
                $idusr = getIdUser($get->id)->id;
                $qry = Req_LeaveModel::select(
                    'req_leave.*',
                    'employees.emp_name',
                    DB::raw('DATE_FORMAT(req_leave.created_at, "%d-%b-%Y") as tanggals')
                )->join('employees', 'employees.id', 'req_leave.employee_id')->where([['req_leave.created_by', $idusr], ['req_leave.status', 'pending']])->orderBy('req_leave.id', 'desc')->get();
                if (count($qry) > 0) {
                    $result = $qry;
                }
            }
        } else {
            $result = "null";
        }
        return response()->json([
            'success' => true,
            'datas'   => isset($result) == false ? "null" : $result,
        ]);
    }


    function DetailApproval(Request $request)
    {
        //id leave, type_leave, myid
        $leave = Req_LeaveModel::where('id', $request->id)->first();
        return response()->json([
            'success' => true,
            'date_from' => Carbon::parse($leave->date_from)->format('d F Y'),
            'date_finish' => Carbon::parse($leave->date_finish)->format('d F Y'),
            'time_finish' => Carbon::parse($leave->time_finish)->format('H:i:s'),
            'lama_cuti'  => $leave->lama_cuti,
            'type_leave' => $leave->type_leave,
        ]);
    }


    function leaveApproval(Request $request)
    {
        //request myid, // request id leave
        $data = Req_LeaveModel::where('id', $request->id)->first();
        $app = [
            'status'     => "Approved",
            'app_spv'    => $request->myid,
        ];
        $leave = [
            'id_leave'    => $request->id,
            'status_by'   => getUserEmp($request->myid)->id,
            'created_by'  => $request->myid,
            'status_app'  => "Approved",
            'approval_by' => "Supervisor",
        ];
        $qry2 = Req_LeaveModel::where('id', $request->id)->update($app);
        $qry3 = Req_LeaveApp::create($leave);
        return response()->json([
            'success' => true,
        ]);
    }


    function leaveReject(Request $request)
    {
        //request myid, // request id leave
        $data = Req_LeaveModel::where('id', $request->id)->first();
        $app = [
            'status'     => "Rejected",
            'app_spv'    => $request->myid,
        ];

        $leave = [
            'id_leave'    => $request->id,
            'status_by'   => getUserEmp($request->myid)->id,
            'created_by'  => $request->myid,
            'status_app'  => "Rejected",
            'approval_by' => "Supervisor",
        ];
        $qry2 = Req_LeaveModel::where('id', $request->id)->update($app);
        $qry3 = Req_LeaveApp::create($leave);
        return response()->json([
            'success' => true,
        ]);
    }


    public function upload_file(Request $request)
    {
        return response()->json([
            'success' => "test",
            'data'    => $request,
        ]);
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
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
