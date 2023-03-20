<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\NeracaModel;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationInvoice;
use App\Models\Purchasing\Purchase_order;
use App\Models\Finance\PettyCashModel;
use App\Models\Finance\PettyCashCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class NeracaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // ========= Neraca Start here ============== //
    public function index()
    {
        $now       = Carbon::now();
        $hitung    = 0;
        $penjualan = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')
            ->whereYear('q.created_at', Carbon::parse($now)->format('Y'))->get();
        foreach ($penjualan as $row) {
            $hitung += GetTotalAkhir($row->id);
        }

        return view('finance.neraca.index', [
            'penjualan'  => $hitung,
        ]);
    }


    public function detail(Request $request)
    {
        if ($request->type == 'aktiva') {
            return $this->aktiva($request);
        } elseif ($request->type == 'hutang') {
            return $this->hutang($request);
        } elseif ($request->type == 'labarugitahan') {
            return $this->labarugitahan($request);
        }
    }

    public function aktiva(Request $request)
    {
        $now  = Carbon::now();
        $data = [];

        return view('finance.neraca.attribute.neraca_aktiva', $data);
    }

    public function hutang(Request $request)
    {
        $now  = Carbon::now();
        $data = [];

        return view('finance.neraca.attribute.neraca_hutang', $data);
    }

    public function labarugitahan(Request $request)
    {
        $now  = Carbon::now();
        $data = [];

        return view('finance.neraca.attribute.neraca_labarugitahan', $data);
    }

    public function rincian(Request $request)
    {
        $now = Carbon::now();
        if ($request->where == "pettycash") {
            $getdata = NeracaModel::detailTotal($now, $request->code);
        } else {
        }
        $data = [
            'getdata'   => $getdata,
        ];

        return view('finance.neraca.detail', $data);
    }

    // ========= Laba Rugi Start here ============== //
    
    public function indexLabarugi(Request $request)
    {
        $now       = Carbon::now();
        $hitung    = 0;
        $penjualan = QuotationInvoice::join('quotation_models as q', 'q.id', '=', 'quotation_invoice.id_quo')
            ->whereYear('q.created_at', Carbon::parse($now)->format('Y'))->get();
        foreach ($penjualan as $row) {
            $hitung += GetTotalAkhir($row->id);
        }

        return view('finance.neraca.index_labarugi', [
            'penjualan'  => $hitung,
        ]);
    }

    public function detailLabarugi(Request $request)
    {
        if ($request->type == 'hargapokok') {
            return $this->hargapokok($request);
        } elseif ($request->type == 'hargapemasaran') {
            return $this->hargapemasaran($request);
        } elseif ($request->type == 'hargaadmin') {
            return $this->hargaadmin($request);
        } elseif ($request->type == 'hargaincome') {
            return $this->hargaincome($request);
        } elseif ($request->type == 'hargaexpense') {
            return $this->hargaexpense($request);
        }
    }


    public function hargapokok(Request $request)
    {
        $now             = Carbon::now();
        $hitungpembelian = $nonppn = 0;
        $sosialisasi     = NeracaModel::hitTotal($now, '98');
        $pembelian       = Purchase_order::where([
            ['status', 'approve'],
            ['isppn', 'yes'],
        ])
            ->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();
        $pembeliannonppn = Purchase_order::where([
            ['status', 'approve'],
            ['isppn', 'no'],
        ])
            ->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();

        foreach ($pembelian as $key => $value) {
            $hitungpembelian += PurchaseTotal($value->id);
        }
        foreach ($pembeliannonppn as $key => $value) {
            $nonppn += PurchaseTotal($value->id);
        }

        $data = [
            'pembelian'   => $hitungpembelian,
            'nonppn'      => $nonppn,
            'sosialisasi' => $sosialisasi,
        ];

        $sum = [
            'pembelian'   => $hitungpembelian,
            'nonppn'      => $nonppn,
            'sosialisasi' => $sosialisasi->total,
        ];
        $data['total'] = array_sum($sum);

        return view('finance.neraca.attribute.labarugi_hargapokok', $data);
    }

    public function hargapemasaran(Request $request)
    {
        $now       = Carbon::now();
        $entertain = NeracaModel::hitTotal($now, '83');
        $data      = [
            'entertain'  => $entertain,
        ];
        $sum      = [
            'entertain'  => $entertain->total,
        ];
        $data['total'] = array_sum($sum);

        return view('finance.neraca.attribute.labarugi_hargapemasaran', $data);
    }

    public function hargaadmin(Request $request)
    {
        $now        = Carbon::now();
        $atk        = NeracaModel::hitTotal($now, '74');
        $kantor     = NeracaModel::hitTotal($now, '75');
        $tla        = NeracaModel::hitTotal($now, '77');  // telp, listri, air 
        $bpjstk     = NeracaModel::hitTotal($now, '63');
        $bpjsk      = NeracaModel::hitTotal($now, '99');
        $transport  = NeracaModel::hitTotal($now, '84');
        $pengiriman = NeracaModel::hitTotal($now, '97');
        $webi       = NeracaModel::hitTotal($now, '85');  // webmail & internet
        $opl        = NeracaModel::hitTotal($now, '82');  // Biaya Operational Lainnya
        $kservice   = NeracaModel::hitTotal($now, '96');  // Biaya Service Kendaraan

        $sum = [
            'atk'        => $atk->total,
            'kantor'     => $kantor->total,
            'tla'        => $tla->total,
            'bpjs'       => $bpjstk->total + $bpjsk->total,
            'transport'  => $transport->total,
            'pengiriman' => $pengiriman->total,
            'webi'       => $webi->total,
            'opl'        => $opl->total,
            'kservice'   => $kservice->total,
        ];

        $data = [
            'atk'        => $atk,
            'kantor'     => $kantor,
            'tla'        => $tla,
            'bpjs'       => [$bpjstk, $bpjsk],
            'transport'  => $transport,
            'pengiriman' => $pengiriman,
            'webi'       => $webi,
            'opl'        => $opl,
            'kservice'   => $kservice,
        ];

        $data['total'] = array_sum($sum);

        return view('finance.neraca.attribute.labarugi_hargaadmin', $data);
    }

    public function hargaincome(Request $request)
    {
        return view('finance.neraca.attribute.labarugi_hargaincome', [
            'data'  => null,
        ]);
    }

    public function hargaexpense(Request $request)
    {
        $now  = Carbon::now();
        $bank = NeracaModel::hitTotal($now, '64');
        $data = [
            'bank'  => $bank,
        ];
        $sum = [
            'bank'  => $bank->total,
        ];
        $data['total'] = array_sum($sum);
        return view('finance.neraca.attribute.labarugi_hargaexpense', $data);
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
}
