<?php

namespace App\Http\Controllers\Distribution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Distribution\ShippingModel;
use App\Models\Distribution\Shipping_pic;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;

class ShippingController extends Controller
{
    public function index()
    {
        return view('Distribution.shipping.index');
    }

    public function create()
    {

        return view('Distribution.shipping.create', [
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'country'  => $this->get_kecamatan(),
            'method'   => 'post',
            'action'   => 'Distribution\ShippingController@store'
        ]);
    }

    public function edit($id)
    {
        $data = ShippingModel::where('id', $id)->first();
        $pic  = Shipping_pic::where('company_id', $id)->first();
        return view('Distribution.shipping.edit', [
            'data'     => $data,
            'pic'      => $pic,
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'country'  => $this->get_kecamatan(),
            'method'   => 'put',
            'action'   => ['Distribution\ShippingController@update', $id],
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


    public function save($request, $save, $id = 0)
    {
        // dd($request);
        $data = [
            'company'  => $request->input('company'),
            'phone'    => $request->input('phone'),
            'fax'      => $request->input('fax'),
            'email'    => $request->input('email'),
            'province' => $request->input('province'),
            'city'     => $request->input('city'),
            'country'  => $request->input('country'),
            'address'  => $request->input('address'),
            $save . '_by'    => Auth::id()
        ];
        // dd($data);
        $qry = $save == 'created' ? ShippingModel::create($data) : ShippingModel::where('id', $id)->update($data);
        if ($qry) {

            $datapic = [
                'company_id' => $qry->id,
                'name'       => $request->input('name'),
                'position'   => $request->input('jabatan'),
                'pic_phone'  => $request->input('mobile'),
                'pic_email'  => $request->input('email_pic'),
                $save . '_by'      => Auth::id()
            ];
            $qry2 = $save == 'created' ? Shipping_pic::create($datapic) : Shipping_pic::where('company_id', $id)->update($datapic);
            $redto = $request->has('other') ? 'success' : redirect('distibution/shipping')->with('success', ucwords($request->input('company')) . ' Vendor Shipping Data' . $save . ' successfully');
            

            dd($redto);
            return  $redto;
            // return redirect('distribution/shipping');
        }
    }

    public function show(Request $request, $id)
    {
        $data = ShippingModel::where('id', $id)->first();
        $pic = Shipping_pic::where('company_id', $id)->get();
        // dd($data,$pic);
        return view('Distribution.shipping.show', [
            'data'   => $data,
            'pic'    => $pic,
            'method' => "post",
            'action' => 'Distribution\ShippingController@storepic',
        ]);
    }

    public function storepic(Request $request)
    {
        // dd($request);
        $datapic = [
            'company_id' => $request->input('company_id'),
            'name'       => $request->input('name'),
            'position'   => $request->input('position'),
            'pic_phone'  => $request->input('pic_phone'),
            'pic_email'  => $request->input('pic_email'),
            'created_by' => Auth::id()
        ];
        shipping_pic::insert($datapic);
        return redirect('distribution/shipping')->with('success', ucwords($request->input('name')) . ' Vendor Shipping PIC Add successfully');
    }

    public function find_shipping(Request $request)
    {

        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = ShippingModel::select("id", "company")
                ->where('company', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }
    public function modal_call(Request $request)
    {

        return view('Distribution.shipping.form', [
            'province' => $this->get_province(),
            'city'     => [],
            'country'  => [],
            'method'   => "post",
        ]);
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'company',
            1 => 'phone',
            2 => 'address',
            3 => 'name',
            4 => 'pic_phone',
            5 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = ShippingModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;
        if (empty($request->input('search')['value'])) {
            $posts = ShippingModel::select('*')->join('shipping_pic', 'shipping_company.id', '=', 'shipping_pic.company_id')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            //dd($posts);
        } else {
            $search        = $request->input('search')['value'];
            $posts         = ShippingModel::select('*')->where('shipping_company.company', 'like', '%' . $search . '%')
                ->join('shipping_pic', 'shipping_company.id', '=', 'shipping_company.company_id')
                ->orWhere('shipping_pic.name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();

            $totalFiltered = ShippingModel::select('*')->where('shipping_company.company', 'like', '%' . $search . '%')
                ->join('shipping_pic', 'shipping_company.id', '=', 'shipping_company.company_id')
                ->orWhere('shipping_pic.name', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'company'    => $post->company,
                    'phone'      => $post->phone,
                    'address'    => $post->address,
                    'name'       => $post->name,
                    'pic_phone'  => $post->pic_phone,
                    'created_at' => $post->created_at->format('d-m-Y'),
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

    public function get_province()
    {

        $data = Provinsi::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->nama;
        }
        return $arr;
    }

    public function get_city()
    {

        $data = Kota::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->nama;
        }
        return $arr;
    }


    public function get_Kecamatan()
    {

        $data = Kecamatan::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->nama;
        }
        return $arr;
    }
}
