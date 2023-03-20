<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\ConfigModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('setting.config.index');
        
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('setting.config.create', [
            'method' => "post",
            'action' => 'Setting\ConfigController@store',
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
    public function edit(ConfigModel $config)
    {
        $data= ConfigModel::where('id', $config->id)->first();
        return view('setting.config.edit', [
            'config' => $data,
            'method' => "put",
            'action' => 'Product\ConfigController@update', $config->id,
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
        return $this->save($request, 'update', $id)->with('success', 'Config Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $config = ConfigModel::findOrFail($id);
        $config->delete();
      
        return redirect()->route('config.index')
        ->with('success', 'Config deleted successfully');
    }


    public function save(Request $request, $save,$id=0){
        $data = [
            'config_name'   => $request->input('config_name'),
            'config_value'   => $request->input('config_value'),
             $save . '_by'=> Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? ConfigModel::create($data) : ConfigModel::where('id', $id)->update($data);
        if ($qry) {
            return redirect('setting/config')->with('success', 'Data Created successfully');
        }
    
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'config_name',
            1 => 'config_value',
            2 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = ConfigModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = ConfigModel::select('*')
                ->orderby($order, $dir)->limit($limit, $start)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = ConfigModel::where('config_name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->limit($limit, $start)->get();
            $totalFiltered = ConfigModel::where('config_value', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->limit($limit, $start)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'config_name' => $post->config_name,
                    'config_value' => $post->config_value,
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
