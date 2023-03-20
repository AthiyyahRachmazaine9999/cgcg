<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receptionist\MeetingRoom;
use App\Models\Receptionist\BookingRoom;
use Auth;
use Carbon\Carbon;
use DB;

class ReceptionistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $m_room = MeetingRoom::all();
        return view('Receptionist.BookingRoom.index',[
            'for' => $m_room,
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
        // dd($request);
        $data = [
            'date'          => $request->date,
            'reserved_name' => $request->reserved_name,
            'id_room'       => $request->room_name,
            'start_time'    => date("G:i", strtotime($request->start_time)),
            'end_time'      => date("G:i", strtotime($request->end_time)),
            'agenda'        => $request->agenda,
            'note'          => $request->note,
            'created_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'created_by'    => Auth::id(),
        ];

        $save = BookingRoom::create($data);
        return redirect()->back()->with('success', 'Booking Successfully');
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

    public function ajax_data(Request $request)
    {
        // dd($request);
         $columns = array(
             0 => 'room_name',
             1 => 'capacity',
             2 => 'note',
             3 => 'created_at',
             4 => 'id',
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
         
         $menu_count    = MeetingRoom::all();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = MeetingRoom::select('*')->offset($start)->limit($limit)->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = MeetingRoom::where('room_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = MeetingRoom::where('room_name', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         // dd($posts);
 
         $data = [];
         if (!empty($posts)) {
            foreach ($posts as $post) {
            $data[] = [
                    'created_at'  => Carbon::parse($post->created_at)->format('d F Y'),
                    'note'        => $post->note,
                    'capacity'    => $post->capacity,
                    'room_name'   => $post->room_name,
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


    function ListRoom(Request $request)
    {
        // dd($request);
        $room = BookingRoom::all();
        foreach ($room as $qry) {
            if($qry->id_room == 1){
                $color = "#1dc3f4";
            }else if($qry->id_room==2){
                $color = "#3897b3";
            }else if($qry->id_room==3){
                $color = "#0dca90";
            }else if($qry->id_room==4){
                $color = "#f36616";
            }else{
                $color = "#c80756";
            }
            
            $arr[] = array(
                'id'    => $qry->id,
                'title' => room_name($qry->id_room)->room_name." - ".Carbon::parse($qry->start_time)->format('H:i:s').' s/d '.Carbon::parse($qry->end_time)->format('H:i:s').'. Agenda: '.$qry->agenda,
                'start' => Carbon::parse($qry->date)->format('Y-m-d').' '.Carbon::parse($qry->start_time)->format('H:i:s'),
                'color' => $color,
            );
            $result = $arr;
            // dd($result);
        }
        return $result;

    }
    

    function FormBooking(Request $request)
    {
        // dd($request);
        return view('Receptionist.BookingRoom.form_booking',[
            'date' => $request->time,
            'room' => $this->getRoom(),
        ]);
    }

    function EditFormBooking(Request $request)
    {
        // dd($request);
        $book = BookingRoom::where('id', $request->id)->first();
        return view('Receptionist.BookingRoom.edit_form_booking',[
            'main'   => $book,
            'date'   => $request->time,
            'room'   => $this->getRoom(),
            'method' => "POST",
            'action' => 'Receptionist\ReceptionistController@saveUpdate',
        ]);
    }


    function saveUpdate(Request $request)
    {
        // dd($request);
        $date = new \DateTime();
        $data = [
            'date'          => $request->date,
            'reserved_name' => $request->reserved_name,
            'id_room'       => $request->room_name,
            'start_time'    => date("H:i", strtotime($request->start_time)),
            'end_time'      => date("H:i", strtotime($request->end_time)),
            'agenda'        => $request->agenda,
            'note'          => $request->note,
            'updated_at'    => Carbon::now('GMT+7')->toDateTimeString(),
            'updated_by'    => Auth::id(),
        ];
            // dd($data);
        $save = BookingRoom::where('id', $request->id)->update($data);
        return redirect()->back()->with('success', 'Update Booking Successfully');
    }


    function delete_form(Request $request)
    {
        // dd($request);
        $save = BookingRoom::where('id', $request->id)->first();
        $save->delete();
        return redirect()->back()->with('success', 'Deleted Booking Successfully');
        
    }
    

    function getRoom()
    {
        $data = MeetingRoom::all();
        $arr = array();
        foreach ($data as $reg) 
        {
            $arr[$reg->id] = $reg->note.' '.$reg->room_name.' (Capacity '.$reg->capacity.')';
        }
        return $arr;
    }
}