<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role\UserModel;
use App\Models\HR\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.user.create', [
            'getuser' => $this->get_employee(),
            'method'  => "post",
            'action'  => 'Role\UserController@store',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->save($request, 'created');
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
        $data = UserModel::where('id', $id)->first();
        $mine = getDataEmp('id',$data->id_emp);
        return view('role.user.edit', [
            'data'    => $data,
            'getuser' => [$mine->id=>$mine->emp_name],
            'method'  => "put",
            'action'  => ['Role\UserController@update',$id],
        ]);
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
        return $this->save($request, 'update',$id);
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

    public function save($request, $save,$id=0)
    {
        $data = [
            'id_emp'     => $request->input('id_emp'),
            'email'      => getEmp($request->input('id_emp'))->emp_email,
            'name'       => getEmp($request->input('id_emp'))->emp_name,
            'password'   => Hash::make($request->input('password')),
            'created_by' => Auth::id()
            ];
            $qry = $save == 'created' ? UserModel::create($data) : UserModel::where('id', $id)->update($data);
            if ($qry) {
            $empl   = EmployeeModel::where('id', $request->input('id_emp'))->first();
            $usm    = UserModel::where('id_emp', $request->input('id_emp'))->first();
            $detail = [$empl, $request->input('password')];
            $menu   = "Credential";
            $url    = url('/');
            $event  = "Your Credential Password " . $save;
            SendEmailNotif($save, $request->input('id_emp'), $detail, $url, $event, $menu);
            return redirect('setting/users')->with('success', ucwords($request->input('title')) . ' User ' . getEmp($request->input('id_emp'))->emp_name . ' ' . $save . ' successfully');
        }
    }

    public function get_employee()
    {
        $pos = UserModel::All();
        $final = [];
        foreach($pos as $p){
            $data[] = $p->id_emp;
        }
        $sisa = EmployeeModel::select('*')->whereNotIn('id', $data)->get();
        foreach($sisa as $s){
            $final[$s->id] = $s->emp_name;
        }
        return $final;
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'email',
            1 => 'name',
            2 => 'created_at',
            5 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = UserModel::select('users.*', 'e.emp_name as name')
            ->leftJoin('employees as e', 'users.id_emp', '=', 'e.id');
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = UserModel::select('users.*', 'e.emp_name as name')
                ->leftJoin('employees as e', 'users.id_emp', '=', 'e.id')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = UserModel::select('users.*', 'e.emp_name as name')
                ->where('emp_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = UserModel::select('users.*', 'e.emp_name as name')
                ->where('emp_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'email'      => $post->email,
                    'name'       => $post->name,
                    'created_at' => $post->created_at->format('Y-m-d'),
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
