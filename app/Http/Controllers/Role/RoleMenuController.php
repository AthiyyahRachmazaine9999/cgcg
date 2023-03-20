<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\UI\MenuModel;
use App\Models\Role\UserModel;
use App\Models\Role\Role_menu;
use App\Models\HR\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RoleMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd($menu);
        $alluser = UserModel::All();
        return view('role.menu.create', [
            'getuser' => [getAllUser()],
            'gettree' => $this->get_tree(),
            'method'  => "post",
            'action'  => 'Role\RoleMenuController@store',
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
        $menu = explode(',', $request->input('datamenu'));
        $request->request->add(['menu' => $menu]);
        $dataone = [];
        foreach (array_keys($menu) as $index => $key) {
            $dataone[] = [
                'id_menu' => checkParent(getMenuName($request->input('menu')[$key]))
            ];
        }
        $arr        = array_unique(array_filter(array_column($dataone, "id_menu")));
        $impl       = implode(",", $arr);
        $menuparent = explode(',', $impl);
        $request->request->add(['parent' => $menuparent]);
        
        $dataparent = [];
        foreach (array_keys($menuparent) as $index => $key) {
            $dataparent[] = [
                'id_user'    => $request->input('user'),
                'id_menu'    => $request->input('parent')[$key],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            
        }

        $datawo = [];
        foreach (array_keys($menu) as $index => $key) {
            $datawo[] = [
                'id_user'    => $request->input('user'),
                'id_menu'    => getMenuName($request->input('menu')[$key]),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
        }
        $full = array_unique(array_merge($dataparent,$datawo), SORT_REGULAR);
        // dd($datawo);
        Role_menu::insert($full);
        return redirect('role/accessmenu/'.$request->input('user').'/edit')->with('success', 'Create Role successfully');
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
        $idu = Role_menu::where('id_user', $id)->first();
        if($idu == null){
            return redirect('role/accessmenu/create');
        }else{
            return view('role.menu.edit', [
                'idu'     => $idu,
                'getuser' => [getAllUser()],
                'getdata' => $this->getmymenu($id),
                'gettree' => $this->active_tree($id),
                'method'  => "put",
                'action'  => ['Role\RoleMenuController@update',$id],
            ]);
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

        $diu = Role_menu::where('id_user',$request->input('user'));
        $diu->delete();

        $menu = explode(',', $request->input('datamenu'));
        $request->request->add(['menu' => $menu]);
        $dataone = [];
        foreach (array_keys($menu) as $index => $key) {
            $dataone[] = [
                'id_menu' => checkParent(getMenuName($request->input('menu')[$key]))
            ];
        }
        $arr        = array_unique(array_filter(array_column($dataone, "id_menu")));
        $impl       = implode(",", $arr);
        $menuparent = explode(',', $impl);
        $request->request->add(['parent' => $menuparent]);

        $dataparent = [];
        foreach (array_keys($menuparent) as $index => $key) {
            $dataparent[] = [
                'id_user'    => $request->input('user'),
                'id_menu'    => $request->input('parent')[$key],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            
        }

        $datawo = [];
        foreach (array_keys($menu) as $index => $key) {
            $datawo[] = [
                'id_user'    => $request->input('user'),
                'id_menu'    => getMenuName($request->input('menu')[$key]),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
        }
        $full = array_unique(array_merge($dataparent,$datawo), SORT_REGULAR);
        $qry = Role_menu::insert($full);
        if($qry){
            return redirect('role/accessmenu/'.$id.'/edit')->with('success', 'Edit Role successfully');
        }
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

    // other function

    public function getmymenu($id)
    {
        $resultstr = array();
        $mine      = Role_menu::where("id_user", $id)
            ->join('ui_menu as m', 'role_menus.id_menu', '=', 'm.id')
            ->get();
        foreach ($mine as $result) {
            $resultstr[] = $result->title;
        }
        return implode(",", $resultstr);
    }

    public function get_tree()
    {
        $id = Auth::id();
        $gets = MenuModel::where('position', '1')->get();
        $menu = [];
        // dd($gets);
        $menu = '<ul class="mb-0">';
        foreach ($gets as $key => $get) {

            $submenu = MenuModel::where([
                ['position', '2'], ['parent_id', $get->id]
            ])->get();
            if (checkChild($get->id) == 0) {
                $menu .= '<li>' . $get->title . '</li>';
            } else {
                $menu .= '<li class="expanded">' . $get->title;
                $menu .= '<ul>';

                foreach ($submenu as $key => $sub) {
                    if (checkChild($sub->id) > 0) {
                        $child = MenuModel::where([
                            ['position', '3'], ['parent_id', $sub->id]
                        ])->get();
                        $menu .= '<li class="expanded">' . $sub->title;
                        $menu .= '<ul>';
                        foreach ($child as $key => $chd) {
                            $menu .= '<li>' . $chd->title . '</li>';
                        }

                        $menu .= '</ul>';
                        $menu .= '</li>';
                    } else {

                        $menu .= '<li>' . $sub->title . '</li>';
                    }
                }
                $menu .= '</ul>';
                $menu .= '</li>';
            }
            $menu .= '</li>';
        }
        $menu .= '</ul>';

        return $menu;
    }

    public function active_tree($id)
    {
        $gets = MenuModel::where('position', '1')->get();
        $menu = [];
        // dd($gets);
        $menu = '<ul class="mb-0">';
        foreach ($gets as $key => $get) {

            $submenu = MenuModel::where([
                ['position', '2'], ['parent_id', $get->id]
            ])->get();
            if (checkChild($get->id) == 0) {
                if (MenuAllow($id, $get->id) == "yes") {
                    $menu .= '<li class="selected" data-selected="true">' . $get->title . '</li>';
                } else {
                    $menu .= '<li>' . $get->title . '</li>';
                }
            } else {
                $menu .= '<li class="expanded">' . $get->title;
                $menu .= '<ul>';

                foreach ($submenu as $key => $sub) {
                    if (checkChild($sub->id) > 0) {
                        $child = MenuModel::where([
                            ['position', '3'], ['parent_id', $sub->id]
                        ])->get();
                        $menu .= '<li class="expanded">' . $sub->title;
                        $menu .= '<ul>';
                        foreach ($child as $key => $chd) {
                            if (MenuAllow($id, $chd->id) == "yes") {
                                $menu .= '<li class="selected" data-selected="true">' . $chd->title . '</li>';
                            } else {
                                $menu .= '<li>' . $chd->title . '</li>';
                            }
                        }

                        $menu .= '</ul>';
                        $menu .= '</li>';
                    } else {
                        if (MenuAllow($id, $sub->id) == "yes") {
                            $menu .= '<li class="selected" data-selected="true">' . $sub->title . '</li>';
                        }else{
                            $menu .= '<li>' . $sub->title . '</li>';
                        }
                    }
                }
                $menu .= '</ul>';
                $menu .= '</li>';
            }
            $menu .= '</li>';
        }
        $menu .= '</ul>';

        return $menu;
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'division_id',
            1 => 'position',
            2 => 'emp_name',
            3 => 'created_at',
            4 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = EmployeeModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = EmployeeModel::select('*')
                ->join('users as u', 'employees.id', '=', 'u.id_emp')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = EmployeeModel::where('emp_name', 'like', '%' . $search . '%')
                ->join('users as u', 'employees.id', '=', 'u.id_emp')
                ->orWhere('emp_address', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = EmployeeModel::where('emp_name', 'like', '%' . $search . '%')
                ->join('users as u', 'employees.id', '=', 'u.id_emp')
                ->orWhere('emp_address', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'div'        => div_name($post->division_id),
                    'position'   => $post->position,
                    'name'       => $post->emp_name,
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
