<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\UI\MenuModel;
use App\Models\UI\IconModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
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
        return view('menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MenuModel $menumodel)
    {
        $newseq = MenuModel::whereNull('parent_id')->max('sequence_to') + 1;
        // dd($menu);
        return view('menu.create', [
            'menu'   => $this->check_parent(),
            'icon'   => [],
            'icons'  => $this->icons(),
            'seq'    => $newseq,
            'method' => "post",
            'action' => 'Menu\MenuController@store',
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
        // dd($request);
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
    public function edit(MenuModel $menu)
    {
        // dd($id);
        $newseq = MenuModel::whereNull('parent_id')->max('sequence_to') + 1;
        $data = MenuModel::where('id',$menu->id)->first();
        // dd($data);
        return view('menu.edit', [
            'getdata'=> $data,
            'menu'   => $this->check_parent(),
            'icon'   => [$data->icon_id => getIcon($data->icon_id)],
            'icons'  => $this->icons(),
            'seq'    => $newseq,
            'method' => "put",
            'action' => ['Menu\MenuController@update',$menu->id],
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
        // dd($request);
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
        // dd($request);
        $pos = $request->input('parent_id') == '' ? '1' : MenuModel::where('id', $request->input('parent_id'))->first()->position + 1;
        $data = [
            'parent_id'   => $request->input('parent_id'),
            'title'       => $request->input('title'),
            'position'    => $pos,
            'sequence_to' => $request->input('sequence_to'),
            'link'        => $request->input('link'),
            'icon_id'     => $request->input('icon_id'),
            'description' => $request->input('description'),
            $save . '_by'       => Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? MenuModel::create($data) : MenuModel::where('id', $id)->update($data);
        if ($qry) {
            return redirect('setting/menu/')->with('success', ucwords($request->input('title')) . ' Menu ' . $save . ' successfully');
        }
    }

    // other functional

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'title',
            1 => 'icon',
            2 => 'parent_id',
            3 => 'link',
            4 => 'created_at',
            5 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = MenuModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = MenuModel::select('*')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = MenuModel::where('title', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = MenuModel::where('title', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'title'      => $post->title,
                    'icon'       => getIcon($post->icon_id),
                    'parent_id'  => $this->my_parent($post->parent_id,$post->id),
                    'link'       => $post->link,
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

    public function my_parent($parent_id,$id)
    {
        $pid = MenuModel::where('id',$parent_id)->first();
        $did = MenuModel::where('id',$id)->first();
        $gt = is_null($parent_id)  ? '' : $pid->title.'/'.$did->title;
        return $gt;
    }

    public function check_parent()
    {

        $data = MenuModel::all();
        $arr = array();
        foreach ($data as $reg) {
            $cm = MenuModel::where('id',$reg->parent_id)->first();
            $gt = is_null($reg->parent_id)  ? $reg->title : $cm->title.'/'.$reg->title;
            $arr[$reg->id] = $gt;
        }
        return $arr;
    }

    protected function icons()
    {
        $arr    = [];
        $icons    = IconModel::all();
        $n        = 0;
        $type    = '';
        foreach ($icons as $ic) {
            if ($n == 4 || ($type != $ic->icon_type && $type != '')) {
                $arr[$ic->icon_type][] = '</div><div class="row">';
                $n = 1;
            } else {
                $n++;
            }
            $arr[$ic->icon_type][] = '<div class="col-md-3"><div class="d-flex align-items-center" style="cursor:pointer" onclick="setIcon(this)" data-title="' . $ic->title . '" data-id="' . $ic->id . '"><i class="' . $ic->title . ' mr-3 mb-2 fa-2x"></i><div> ' . $ic->title . '	</div></div></div>';
            $type = $ic->icon_type;
        }
        return $arr;
    }
}
