<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationReplacement;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationInvoiceDetail;
use App\Models\Sales\QuotationInvoicePayment;
use App\Models\Sales\QuotationInvoiceOthers;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Finance\Pay_VoucherDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
use DB;

class CronJobController extends Controller
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

    public function deadline(Request $request)
    {
        $now     = time();
        $tanggal = $request->segment(4);
        $range   = "+".$tanggal." day";
        $sdate   = date('Y-m-d',$now);
        $edate   = date('Y-m-d',strtotime($sdate.$range));
        $check   = QuotationModel::whereBetween('quo_deadline', [$sdate,$edate])->get();
        foreach ($check as $key => $value) {

            SendEmailDeadline($value->id);
        }
    }

    public function deadlinevendor(Request $request)
    {
        $now     = time();
        $tanggal = $request->segment(4);
        $range   = "+".$tanggal." day";
        $sdate   = date('Y-m-d',$now);
        $edate   = date('Y-m-d',strtotime($sdate.$range));
        $check   = Pay_VoucherDetail::where('from_date','>=',$sdate)
        ->where('to_date','<=',$edate)
        ->where("status", "Completed")->get();
        if(count($check)==0){
           $gets = Pay_VoucherDetail::whereNotNull('to_date')
           ->where("status", "Completed")
           ->where('to_date','>=',$sdate)->get();
        }else{
            $gets = $check;

        }
        foreach ($gets as $key => $value) {

            SendEmailDeadlineVendor($value->id);
        }
    }
}
