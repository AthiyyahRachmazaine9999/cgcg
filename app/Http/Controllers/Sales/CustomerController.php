<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\Customer_pic;
use App\Models\Location\Kecamatan;
use App\Models\Role\Role_cabang;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales.customer.create', [
            'province' => $this->get_province(),
            'city'     => [],
            'country'  => [],
            'method'   => "post",
            'action'   => 'Sales\CustomerController@store',
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

    public function storepic(Request $request)
    {
        // dd($request);
        $datapic = [
            'id_customer' => $request->input('id_customer'),
            'name'        => $request->input('name'),
            'jabatan'     => $request->input('jabatan'),
            'mobile'      => $request->input('mobile'),
            'email'       => $request->input('email_pic'),
            'created_by'  => Auth::id()
        ];
        Customer_pic::insert($datapic);
        return redirect('sales/customer/' . $request->input('id_customer'))->with('success', ucwords($request->input('name')) . ' Customer PIC Add successfully');
    }

    public function deletepic(Request $request)
    {
        // dd($request);
        $id        = $request->id_pic;
        $redto     = 'sales/customer/' . $request->id_cust;
        $deletepic = Customer_pic::findOrFail($id);
        $deletepic->delete();

        return $redto;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('sales.customer.show', [
            'data'   => CustomerModel::where('id', $id)->first(),
            'pic'    => Customer_pic::where('id_customer', $id)->get(),
            'method' => "post",
            'action' => 'Sales\CustomerController@storepic',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = CustomerModel::where('id', $id)->first();
        // dd([$data->city => city($data->city)]);

        return view('sales.customer.edit', [
            'data'     => $data,
            'pic'      => Customer_pic::where('id_customer', $id)->get(),
            'province' => $this->get_province(),
            'city'     => $this->get_city(),
            'ccity'    => [$data->city => city($data->city)],
            'country'  => $this->get_kecamatan(),
            'ccountry' => [$data->country => country($data->country)],
            'method'   => "put",
            'action'   => ['Sales\CustomerController@update', $id],
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
        $data = [
            'company'    => $request->input('company'),
            'phone'      => $request->input('phone'),
            'fax'        => $request->input('fax'),
            'email'      => $request->input('email'),
            'province'   => $request->input('province'),
            'city'       => $request->input('city'),
            'country'    => $request->input('country'),
            'address'    => $request->input('address'),
            'updated_by' => Auth::id()
        ];
        // dd($data);
        CustomerModel::where('id', $id)->update($data);

        if ($request->has('id_pic')) {

            $vend = $request->input('id_pic');
            foreach ($vend as $item => $v) {
                $datapic = [
                    'id_customer' => $id,
                    'name'        => $request->input('name')[$item],
                    'jabatan'     => $request->input('jabatan')[$item],
                    'mobile'      => $request->input('mobile')[$item],
                    'email'       => $request->input('email_pic')[$item],
                    'updated_by'  => Auth::id()
                ];
                Customer_pic::where('id', $vend[$item])->update($datapic);
            }
        }

        $redto = 'sales/customer/' . $id;
        return redirect($redto)->with('success', ucwords($request->input('company')) . ' Customer Data successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }

    // other function

    public function save($request, $save, $id = 0)
    {
        // dd($request,$save,$id);
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
        $qry = $save == 'created' ? CustomerModel::create($data) : CustomerModel::where('id', $id)->update($data);
        if ($qry) {

            $datapic = [
                'id_customer' => $qry->id,
                'name'        => $request->input('name'),
                'jabatan'     => $request->input('jabatan'),
                'mobile'      => $request->input('mobile'),
                'email'       => $request->input('email_pic'),
                $save . '_by' => Auth::id()
            ];
            $qry2 = $save == 'created' ? Customer_pic::create($datapic) : Customer_pic::where('id', $id)->update($datapic);
            $redto = $request->has('other') ? 'sales/quotation/create' : 'sales/customer';
            return redirect($redto)->with('success', ucwords($request->input('company')) . ' Customer Data' . $save . ' successfully');
        }
    }

    public function get_province()
    {

        $data = Provinsi::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }

    public function get_city()
    {

        $data = Kota::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->kota);
        }
        return $arr;
    }


    public function get_Kecamatan()
    {

        $data = Kecamatan::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->nama);
        }
        return $arr;
    }

    public function find_customer(Request $request)
    {

        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = CustomerModel::select("id", "company")
                ->where('company', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function find_cabang(Request $request)
    {
        // dd($request);

        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = Role_cabang::select("id", "nama_perusahaan", "cabang_name")
                ->where('nama_perusahaan', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }


    public function find_vendor(Request $request)
    {
        // dd($request);
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data   = VendorModel::select("id", "vendor_name")
                ->where('vendor_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function modal_call(Request $request)
    {

        return view('sales.customer.form', [
            'province' => $this->get_province(),
            'city'     => [],
            'country'  => [],
            'method'   => "post",
            'action'   => 'Sales\CustomerController@store',
        ]);
    }

    public function modal_cabang(Request $request)
    {

        return view('Role.cabang.form');
    }

    public function modal_vendor(Request $request)
    {

        return view('sales.vendor.form');
    }

    public function ajax_data(Request $request)
    {
        $columns = array(
            0 => 'company',
            1 => 'email',
            2 => 'address',
            3 => 'created_at',
            4 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order')[0]['column']];
        $dir   = $request->input('order')[0]['dir'];

        $menu_count    = CustomerModel::all();
        $totalData     = $menu_count->count();
        $totalFiltered = $totalData;

        if (empty($request->input('search')['value'])) {
            $posts = CustomerModel::select('*')->offset($start)->limit($limit)
                ->orderby($order, $dir)->get();
        } else {
            $search        = $request->input('search')['value'];
            $posts         = CustomerModel::where('company', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->get();
            $totalFiltered = CustomerModel::where('company', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orderby($order, $dir)->offset($start)->limit($limit)->count();
        }
        // dd($posts);

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = [
                    'company'    => $post->company,
                    'email'      => $post->email,
                    'address'    => $post->address,
                    'created_at' => $post->created_at == null ? '' : $post->created_at->format('Y-m-d'),
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
