<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActQuoModel;
use App\Models\Sales\Customer_pic;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Storage;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function kirim_sq(Request $request)
    {
        $idsq     = $request->idsq;
        $so       = QuotationModel::where('id', $idsq)->first();
        $customer = CustomerModel::where('id', $so->id_customer)->first();
        $pic      = Customer_pic::where('id_customer', $customer->id_customer)->get();
        // dd($view);
        return view('sales.quotation.attribute.sales_emailsq', [
            'idsq'      => $idsq,
            'customer'  => $customer,
            'pic'       => $pic,
            'penawaran' => 'Penawaran PT Mitra Era Global',
            'getemail'  => [getAllEmail()],
            'method'    => "post",
            'action'    => 'Sales\SalesController@exec_kirim_sq',
        ]);
    }

    public function defaulttext(Request $request)
    {
        $idsq     = $request->idsq;
        $so       = QuotationModel::where('id', $idsq)->first();
        $customer = CustomerModel::where('id', $so->id_customer)->first();
        $pic      = Customer_pic::where('id_customer', $customer->id_customer)->get();
        // dd($view);
        return view('sales.quotation.attribute.defaulttext', [
            'customer' => $customer,
            'pic'      => $pic,
        ]);
    }

    public function exec_kirim_sq(Request $request)
    {
        $u_creator  = getUserEmp(Auth::id())->id_emp;
        $vendormail = $request->name;
        $cc         = $request->has('cc_mail') ? $request->cc_mail : 'no';

        $order    = QuotationModel::where('id', $request->idsq)->first();

        if ($request->has('lampiran')) {
            dd(count($request->lampiran));
            foreach ($request->lampiran as $value => $y) {
                $file = $request->file('lampiran')[$value]->getClientOriginalName();
                Storage::disk('public')->putFileAs('attachment_sq/' . $order->id, $request->file('lampiran')[$value], $file);
            }
        }

        $detail   = [$order, $request->body];
        $event    = "Penawaran PT Mitra Era Grobal";
        $subject  = $request->subject;
        $testdata = [$u_creator, $vendormail, $cc, $detail, $event, $order->po_number];
        // dd($testdata);
        SendEmailCustomer($u_creator, $vendormail, $cc, $detail, $event, $order->id, $subject);

        $log = array(
            'activity_id_quo'       => $order->id,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => "Quotation sudah dikirim melalui email" ,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($log);
        ActQuoModel::insert($log);

        return redirect("sales/quotation/" . $order->id)->with('success', ' Email Penawaran berhasil dikirim');
    }
}
