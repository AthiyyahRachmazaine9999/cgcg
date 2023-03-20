<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\QuotationDocument;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Activity\ActQuoModel;
use App\Models\Product\ProductLive;
use App\Models\Product\ProductReq;
use App\Models\Product\ProductModalHistory;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Sales\Vendor_pic;
use App\Models\Sales\VendorModel;
use App\Models\Sales\Customer_pic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use PDF;

class ExportController extends Controller
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

    public function generate_draftpo(Request $request)
    {
        $nomer_so = "SO".sprintf("%06d",getQuo($request->id_quo)->id);
        $quo_mo   = QuotationModel::where('id', $request->id_quo)->first();
        $invoice  = QuotationInvoice::where('id_quo', $request->id_quo)->first();
        $pdf = PDF::loadview('pdf.purchasing_draftpo',[
            'main'   => $request,
            'quo_mo' => $quo_mo,
            'invoice'=> $invoice,
            'so'     => $nomer_so,
            'time'   => Carbon::now('GMT+7')->format('d F Y')
        ]);
    	return $pdf->download('MEG - Draft_PO ('.$nomer_so.').pdf');
        
    }

    public function generate_finalpo(Request $request)
    {
        // dd($request);
        $main       = Purchase_order::where('po_number', $request->nopo)->first();
        $vend       = VendorModel::where('id', $main->id_vendor)->first();
        $product    = Purchase_detail::where('id_po', $main->id)->get();
        $altaddress = Purchase_address::where('id_po', $main->id)->first();
        $qr         = QrCode::format('png')->size(300)->generate('MyNotePaper');
        $pdf        = PDF::loadview('pdf.purchasing_po',[
            'main'       => $main,
            'product'    => $product,
            'vend'       => $vend,
            'altaddress' => $altaddress,
            'qr'         => $qr,
            'time'       => Carbon::now('GMT+7')->format('d F Y')
        ]);
    	return $pdf->download('MEG - '.$request->nopo.'.pdf');
        
    }
    

    public function additional_note(Request $request)
    {
        $main = Purchase_order::where('id', $request->idpo)->first();
        return view('purchasing.attribute.additional_note', [
            'req'      => $request,
            'main'     => $main,
            'method'   => "post",
            'action'   => "Purchasing\ExportController@save_note"
        ]);
    }

    public function save_note(Request $request)
    {
        $main = Purchase_order::where('id', $request->id)->first();
        if ($request->has('note_order')) {
            $data = [
                'note_order' => $request->note_order,
            ];
            $quo = Purchase_order::where('id', $request->id)->update($data);
        }
        return redirect("/purchasing/order/" . $main->po_number)->with('success', ucwords($request->input('company')) . 'Update Status successfully');
    }
}
