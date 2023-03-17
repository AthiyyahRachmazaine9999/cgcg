<?php

use App\Models\Activity\ActQuoModel;
use App\Models\UI\MenuModel;
use App\Models\UI\IconModel;
use App\Models\Role\UserModel;
use App\Models\Role\Role_cabang;
use App\Models\Role\Role_address;
use App\Models\Role\Role_division;
use App\Models\HR\EmployeeModel;
use App\Models\HR\EmployeeStatus;
use App\Models\HR\Req_LeaveModel;
use App\Models\HR\ReqLeaveSpecial;
use App\Models\HR\Req_LeaveApp;
use App\Models\HR\Req_MassLeave;
use App\Models\HR\Req_MassLeaveDetail;
use App\Models\Receptionist\MeetingRoom;
use App\Models\Receptionist\BookingRoom;
use App\Models\Product\ProductLive;
use App\Models\Product\LiveCatModel;
use App\Models\Product\LiveWeightModel;
use App\Models\Product\LiveManModel;
use App\Models\Product\LiveLengthModel;
use App\Models\Product\ProductModalHistory;
use App\Models\Product\ProductReq;
use App\Models\Product\ProHargaHist;
use App\Models\Product\ProStatusHist;
use App\Models\Sales\VendorModel;
use App\Models\Sales\CustomerModel;
use App\Models\Sales\Customer_pic;
use App\Models\Sales\QuotationModel;
use App\Models\Sales\QuotationProduct;
use App\Models\Sales\QuotationStatus;
use App\Models\Sales\Quo_TypeModel;
use App\Models\Sales\QuotationInvoice;
use App\Models\Sales\QuotationInvoiceOthers;
use App\Models\Sales\QuotationInvoiceDetail;
use App\Models\Sales\QuotationInvoicePaymentDetail;
use App\Models\Sales\QuotationOtherPrice;
use App\Models\Sales\InvoiceModel;
use App\Models\Sales\InvoiceModelBarang;
use App\Models\Finance\CashAdvance;
use App\Models\Finance\PettyCashCode;
use App\Models\Finance\PettyCashModel;
use App\Models\Finance\PettyCashDokumenModel;
use App\Models\Purchasing\Purchase_model;
use App\Models\Purchasing\Purchase_detail;
use App\Models\Purchasing\Purchase_order;
use App\Models\Purchasing\Purchase_address;
use App\Models\Purchasing\PurchaseFinanceBank;
use App\Models\Purchasing\PurchaseMigrateDetail;
use App\Models\Purchasing\PurchaseMigrate;
use App\Models\Warehouse\Warehouse_detail;
use App\Models\Warehouse\Warehouse_order;
use App\Models\Warehouse\Warehouse_resi;
use App\Models\Warehouse\Warehouse_address;
use App\Models\Warehouse\warehouse_out;
use App\Models\Warehouse\Warehouse_pengiriman;
use App\Models\Inventory\InventoryModel;
use App\Models\Distribution\ShippingModel;
use App\Models\Distribution\Shipping_pic;
use App\Models\Location\Kecamatan;
use App\Models\WarehouseUpdate\WarehouseIn;
use App\Models\WarehouseUpdate\WarehouseInDetail;
use App\Models\WarehouseUpdate\WarehouseOutDetail;
use App\Models\WarehouseUpdate\WarehouseOut;
use App\Models\WarehouseUpdate\WarehouseSN;
use App\Models\Location\Kota;
use App\Models\Location\Provinsi;
use App\Models\Finance\FinanceSettlementModel;
use App\Models\Finance\FinanceSettlementDetail;
use App\Models\Finance\Pay_VoucherPayment;
use App\Models\Finance\Pay_VoucherForwardApproval;
use App\Models\Finance\Pay_VoucherDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreate;
use App\Mail\ApproveMail;
use App\Mail\ApproveHrEmail;
use App\Mail\ApproveSpvMail;
use App\Mail\Settlement\ApprovalToSpv;
use App\Mail\Settlement\ApprovalToFin;
use App\Mail\SendPOmail;
use App\Mail\SendSQmail;
use App\Mail\ResetMasterKey;
use App\Mail\MailDeadline;
use App\Mail\MailDeadlineVendor;
use Carbon\Carbon;

if (!function_exists('getOther')) {
    function getOther($id)
    {
        $pos = MenuModel::where('position', '1')->get();
        return $pos;
    }
}
// ========== Role ================= //
//================================== //
if (!function_exists('getAllUser')) {
    function getAllUser()
    {
        $pos = UserModel::All();
        $get = [];
        foreach ($pos as $p) {
            $get[$p->id] = $p->name;
        }
        return $get;
    }
}
if (!function_exists('getAllEmail')) {
    function getAllEmail()
    {
        $pos = UserModel::All();
        $get = [];
        foreach ($pos as $p) {
            $get[$p->email] = $p->email;
        }
        return $get;
    }
}
if (!function_exists('div_name')) {
    function div_name($id)
    {
        $name = $id == 0 ? '' : Role_division::where('id', $id)->first()->div_name;
        return $name;
    }
}
if (!function_exists('getCabang')) {
    function getCabang($id)
    {
        $name = $id == 0 ? '' : Role_cabang::where('id', $id)->first();
        return $name;
    }
}
if (!function_exists('getAddress')) {
    function getAddress($id)
    {
        $name = Role_address::where('id', $id)->first();
        return $name;
    }
}
if (!function_exists('cabang_name')) {
    function cabang_name($id)
    {
        $name = $id == 0 ? '' : Role_cabang::where('id', $id)->first()->cabang_name;
        return $name;
    }
}

if (!function_exists('user_name')) {
    function user_name($id)
    {
        $name = $id == 0 ? '' : UserModel::where('id', $id)->first()->name;
        return $name;
    }
}

if (!function_exists('Codetype')) {
    function Codetype($id)
    {
        $name = PettyCashCode::where('id', $id)->first();
        return $name;
    }
}


// ========== Receptionist ============== //
//================================== //
if (!function_exists('room_name')) {
    function room_name($id)
    {
        $name = MeetingRoom::where('id', $id)->first();
        return $name;
    }
}


// ========== Employee ============== //
//================================== //
if (!function_exists('emp_name')) {
    function emp_name($id)
    {
        $name = $id == 0 ? '' : EmployeeModel::where('id', $id)->first()->emp_name;
        return $name;
    }
}


if (!function_exists('getEmpStatus')) {
    function getEmpStatus($id)
    {
        $name = EmployeeStatus::where('id', $id)->first();
        return $name;
    }
}



if (!function_exists('checkLeave')) {
    function checkLeave($id_emp)
    {
        $yesterday_month = 12;
        $nows       = Carbon::now('GMT+7');
        $this_year  = Carbon::parse($nows)->format('Y');
        $this_month = Carbon::parse($nows)->format('F');
        $last_year  = (Carbon::now()->year) - 1;
        $yesterday  = Carbon::yesterday()->format('Y');
        $endMonth   = $nows->endOfMonth()->format('Y-m-d');

        $emps       = EmployeeModel::select('tgl_bergabung')->where('id', $id_emp)->first();
        $join_date  = Carbon::parse($emps->tgl_bergabung)->format('Y');
        $join_month = Carbon::parse($emps->tgl_bergabung)->format('n');

        // dd($nows, $endMonth);

        if ($join_date == $this_year) {
            $ch_month = Carbon::parse($nows)->format('n') - Carbon::parse($emps->tgl_bergabung)->format('n');
        } else {
            $ch_month = Carbon::parse($nows)->format('n');
        }

        //check tahun sebelumnya
        //penambahan bulan
        if ($this_month == "December") { // apabila sekarang di bulan desember
            $total_month = $ch_month;
        } else {
            if ($join_date == $this_year) { //apabila tgl bergabung sama dengan tahun ini
                $total_month = $ch_month;
            } else {
                if (Carbon::now()->format('Y-m-d') == $endMonth) { //apabila sekarang sama dengan akhir bulan
                    $total_month = $ch_month;
                } else {
                    $total_month = $ch_month - 1;
                }
            }
        }
        // dd($total_month);

        if ($yesterday == $this_year) {
            $annual    = Req_LeaveModel::select('*')->where([['employee_id', $id_emp], ['type_leave', 'Annual Leave'], ['status', '!=', 'Rejected']])
            ->whereYear('created_at', $this_year)->get();
            $arr = [];
            if (count($annual) != 0) {
                foreach ($annual as $anns) {
                    $begin = Carbon::createFromFormat('Y-m-d', $anns->date_from);
                    $end   = Carbon::createFromFormat('Y-m-d', $anns->date_finish);

                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($begin, $interval, $end);

                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                        $arr[] = $i->format("l Y-m-d");
                        $getCount = CountWithoutWeekEnd($arr);
                    }
                }
            } else {
                $getCount = $annual;
            }

            $count_annual = count($getCount);
            $permit    = Req_LeaveModel::where([
                ['employee_id', $id_emp],
                ['type_leave', 'Permission'],
                ['purpose', '!=', 'Izin Sakit'],
                ['status', '!=', 'Rejected']
            ])
                ->whereYear('created_at', $this_year)->get()->count();

            if ($join_date == $this_year) {
                $massLeave = Req_MassLeaveDetail::whereMonth('date_of_days', '>', $join_month)
                ->whereYear('date_of_days', $this_year)->get()->count();
            } else {
                $massLeave = Req_MassLeaveDetail::whereYear('date_of_days', $this_year)->get()->count();
            }
            $vals = ($total_month - $count_annual - $permit - $massLeave);
            $bal  = getBalanceLastYear($id_emp);
            
            $sisa = $join_date == $this_year ? $vals : $vals + $bal;
        } else {
            $sisa = $total_month;
        }
        return $sisa;
    }
}


if (!function_exists('getBalanceLastYear')) {
    function getBalanceLastYear($id_emp)
    {
        $yesterday_month = 12;
        $last_year  = (Carbon::now()->year) - 1;
        $yesterday  = Carbon::yesterday()->format('Y');

        $emps       = EmployeeModel::select('tgl_bergabung')->where('id', $id_emp)->first();
        $join_date  = Carbon::parse($emps->tgl_bergabung)->format('Y');
        $join_month = Carbon::parse($emps->tgl_bergabung)->format('n');

        // dd($nows, $endMonth);

        if ($join_date == $last_year) {
            $ch_month = 12 - Carbon::parse($emps->tgl_bergabung)->format('n');
        } else {
            $ch_month = 12;
        }

        //check tahun sebelumnya
        //penambahan bulan
        $total_month = $ch_month;

        if ($yesterday != $last_year) {
            $annual    = Req_LeaveModel::select('*')->where([['employee_id', $id_emp], ['type_leave', 'Annual Leave'], ['status', '!=', 'Rejected']])
            ->whereYear('created_at', $last_year)->get();
            $arr = [];
            if (count($annual) != 0) {
                foreach ($annual as $anns) {
                    $begin = Carbon::createFromFormat('Y-m-d', $anns->date_from);
                    $end   = Carbon::createFromFormat('Y-m-d', $anns->date_finish);

                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($begin, $interval, $end);

                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                        $arr[] = $i->format("l Y-m-d");
                        $getCount = CountWithoutWeekEnd($arr);
                    }
                }
            } else {
                $getCount = $annual;
            }
            // dd($getCount);

            $count_annual = count($getCount);
            $permit    = Req_LeaveModel::where([
                ['employee_id', $id_emp],
                ['type_leave', 'Permission'],
                ['purpose', '!=', 'Izin Sakit'],
                ['status', '!=', 'Rejected']
            ])
                ->whereYear('created_at', $last_year)->get()->count();

            if ($join_date == $last_year) {
                $massLeave = Req_MassLeaveDetail::whereMonth('date_of_days', '>', $join_month)
                ->whereYear('date_of_days', $last_year)->get()->count();
            } else {
                $massLeave = Req_MassLeaveDetail::whereYear('date_of_days', $last_year)->get()->count();
            }
            $sisa      = $total_month - $count_annual - $permit - $massLeave;
        } else {
            $sisa = $total_month;
        }
        // dd($sisa);
        // dd($total_month, $count_annual, $permit, $massLeave, $sisa);
        return $sisa;
    }
}




if (!function_exists('CountWithoutWeekEnd')) {
    function CountWithoutWeekEnd($arr)
    {
        $weekend = [];
        $weekdays = [];
        foreach ($arr as $days) {
            if (Carbon::parse($days)->format('l') == 'Saturday' || Carbon::parse($days)->format('l') == "Sunday") {
                $weekend[] = $days;
            } else {
                $weekdays[] = $days;
            }
        }
        return $weekdays;
    }
}



if (!function_exists('getDataEmp')) {
    function getDataEmp($where, $id)
    {
        $pos = EmployeeModel::where($where, $id)->first();
        return $pos;
    }
}

if (!function_exists('getEmpSelect')) {
    function getEmpSelect($where, $id)
    {
        $data = EmployeeModel::where($where, $id)->get();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = strtoupper($reg->emp_name);
        }
        return $arr;
    }
}

if (!function_exists('getEmp')) {
    function getEmp($id)
    {
        $pos = EmployeeModel::where('id', $id)->first();
        return $pos;
    }
}


if (!function_exists('getSPV')) {
    function getSPV($id)
    {
        $name = $id == 0 ? '' : EmployeeModel::where('id', $id)->first()->spv_id;
        return $name;
    }
}

if (!function_exists('getTeam')) {
    function getTeam($id)
    {
        $name = UserModel::select("users.id as idku")
        ->where('spv_id', $id)
        ->join('employees as e', 'e.id', '=', 'users.id_emp')
        ->get();
        
        $test = array();
        foreach ($name as $key => $value) {
            $test[]= $value->idku;
        }
        return $test;
    }
}

if (!function_exists('getUserEmp')) {
    function getUserEmp($id)
    {
        $user = UserModel::where('users.id', $id)
            ->join('employees as e', 'e.id', '=', 'users.id_emp')->first();
        return $user;
    }
}


if (!function_exists('getIdUser')) {
    function getIdUser($id)
    {
        $user = UserModel::where('id_emp', $id)->first();
        return $user;
    }
}

//============= Sales =============//
//=================================//


if (!function_exists('getPriceInvoice')) {
    function getPriceInvoice($id)
    {

        $quo_mo     = QuotationModel::where('id', $id)->first();
        $quo_in     = QuotationInvoice::where('id_quo', $quo_mo->id)->first();
        $quo_pro    = QuotationProduct::select(DB::raw('det_quo_harga_order*det_quo_qty as subtotal'))
            ->join('quotation_models', 'quotation_product.id_quo', '=', 'quotation_models.id')
            ->where('quotation_models.id', $quo_in->id_quo)->get()->sum('subtotal');
        $time_inv   = $quo_in == null ? '0000-00-00' : $quo_in->tgl_invoice;
        $price      = QuotationOtherPrice::where('id_quo', $quo_mo->id)->first();
        $nego_ongkir = $price->ongkir_customer == null ? 0 : $price->ongkir_customer;
        $times      = $quo_mo->quo_type == 1 ? $quo_mo->created_at : $quo_mo->quo_order_at;
        $hitung     = $quo_pro * (GetPPN($time_inv, $times) / 100);
        $ttl        = ($quo_pro + $hitung + $nego_ongkir);

        return $hasil = [
            'total'      => $ttl,
            'ppn'        => $hitung,
            'time_inv'   => $time_inv,
            'VAT'        => GetPPN($time_inv, $times),
            'ongkir'     => $nego_ongkir,
        ];
    }
}


if (!function_exists('getPriceInvoiceInvoice')) {
    function getPriceInvoiceInvoice($id, $pro)
    {
        $quo_mo     = QuotationModel::where('id', $id)->first();
        if($quo_mo == null)
        {
            dd($id, $pro);
        }

        $quo_in     = QuotationInvoice::where('id_quo', $quo_mo->id)->first();
        $quo_pro    = QuotationProduct::select(DB::raw('det_quo_harga_order*det_quo_qty as subtotal'))
            ->join('quotation_models', 'quotation_product.id_quo', '=', 'quotation_models.id')
            ->where('quotation_models.id', $quo_in->id_quo)->get()->sum('subtotal');
        $time_inv   = $quo_in == null ? '0000-00-00' : $quo_in->tgl_invoice;
        $price      = QuotationOtherPrice::where('id_quo', $quo_mo->id)->first();
        $nego_ongkir = $price->ongkir_customer == null ? 0 : $price->ongkir_customer;
        $times      = $quo_mo->quo_type == 1 ? $quo_mo->created_at : $quo_mo->quo_order_at;
        $hitung     = $quo_pro * (GetPPN($time_inv, $times) / 100);
        $ttl        = ($quo_pro + $hitung + $nego_ongkir);

        return $hasil = [
            'total'      => $ttl,
            'ppn'        => $hitung,
            'time_inv'   => $time_inv,
            'VAT'        => GetPPN($time_inv, $times),
            'ongkir'     => $nego_ongkir,
        ];
    }
}


if (!function_exists('getProductDetail')) {
    function getProductDetail($id)
    {
        $user = ProductLive::where('sku', $id)->join('ocbz_product_description as pd', 'pd.product_id', '=', 'ocbz_product.product_id')->first();
        return $user;
    }
}

if (!function_exists('getProductReq')) {
    function getProductReq($id)
    {
        $user = ProductReq::where('id', $id)->first();
        return $user;
    }
}


if (!function_exists('req_product')) {
    function req_product($id)
    {
        $name = $id == 0 ? '' : ProductReq::where('id', $id)->first()->req_product;

        return $name;
    }
}

if (!function_exists('ProHargaHist')) {
    function ProHargaHist($id)
    {
        $user = ProHargaHist::where('sku', $id)->get();
        return $user;
    }
}






if (!function_exists('costsetsettlementmenu')) {
    function costsetsettlementmenu($id)
    {




        $costsetmenus    = FinanceSettlementModel::join('finance_settlement_detail', 'finance_settlement_detail.id_settlement', '=', 'finance_settlement.id')
        ->select(DB::raw('SUM(qty*est_biaya) as noms'))

        
        ->where([
           
            ['finance_settlement.employee_id' , $id ],
            ['finance_settlement.status', '=', 'Completed']

        ])

            
            ->get()->sum('noms');

    




        return $costsetmenus;
    }
}







if (!function_exists('costsetsettlementmenudetailtotal')) {
    function costsetsettlementmenudetailtotal($id)
    {



        $costsetmenustotal    =  FinanceSettlementDetail::select(DB::raw('SUM(qty*est_biaya) as noms'))
        ->where('id_settlement', $id)
        ->first();
        



        return $costsetmenustotal;
    }
}








if (!function_exists('ListHargaHist')) {
    function ListHargaHist($id)
    {
        $user = ProHargaHist::where('id_pro', $id)->get();
        return $user;
    }
}

if (!function_exists('ProStatusHist')) {
    function ProStatusHist($id)
    {
        $user = ProStatusHist::where('sku', $id)->get();
        return $user;
    }
}

if (!function_exists('getModalHistory')) {
    function getModalHistory($id)
    {
        $user = ProductModalHistory::where('id_product', $id)->get();
        return $user;
    }
}

if (!function_exists('getProductQuo')) {
    function getProductQuo($id)
    {
        $user = QuotationProduct::where('id', $id)->first();
        return $user;
    }
}


if (!function_exists('getProductPurchase')) {
    function getProductPurchase($id)
    {
        $user = Purchase_model::where('id', $id)->first();
        return $user;
    }
}


if (!function_exists('getBankName')) {
    function getBankName($id)
    {
        $user = PurchaseFinanceBank::where('id', $id)->first();
        return $user;
    }
}



if (!function_exists('getPurchasingQuo')) {
    function getPurchasingQuo($id)
    {
        $user = Purchase_model::where('id', $id)->first();
        return $user;
    }
}


if (!function_exists('getIdPO')) {
    function getIdPO($id_quo, $id_vendor)
    {
        $user = Purchase_order::where([['id_quo', $id_quo], ['id_vendor', $id_vendor]])->first();
        return $user;
    }
}

if (!function_exists('getPurchasing')) {
    function getPurchasing($where, $id)
    {
        $user = Purchase_model::where($where, $id)->first();
        return $user;
    }
}


if (!function_exists('checkPayVoucher')) {
    function checkPayVoucher($id_pay)
    {
        $user = Pay_VoucherPayment::where('id_pay', $id_pay)->orderBy('id', 'desc')->first();
        return $user == null ? null : $user->status;
    }
}

if (!function_exists('checkPayAppr')) {
    function checkPayAppr($id_pay)
    {
        $user = Pay_VoucherForwardApproval::where('id_pay', $id_pay)->first();
        return $user;
    }
}




if (!function_exists('ProductToPurchase')) {
    function ProductToPurchase($id)
    {
        $product  = QuotationProduct::where('id', $id)->first();
        $purchase = Purchase_model::where('id_quo_pro', $id)->first();

        if ($product->id_product <> $purchase->id_product) {
            $data = [
                'id_product' => $product->id_product,
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('GMT+7')->toDateTimeString()
            ];
            Purchase_model::where('id_quo_pro', $id)->update($data);
        }
    }
}

if (!function_exists('countproduct')) {
    function countproduct($id)
    {
        $count = QuotationProduct::where('id_quo', $id)->count();
        return $count;
    }
}

if (!function_exists('getProductPo')) {
    function getProductPo($id)
    {
        $user = ProductLive::where('ocbz_product.product_id', $id)
            ->join('ocbz_product_description as pd', 'pd.product_id', '=', 'ocbz_product.product_id')->first();
        return $user;
    }
}


if (!function_exists('getQuoType')) {
    function getQuoType($id)
    {
        $quo = Quo_TypeModel::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('typename')) {
    function typename($id)
    {
        $pos = Quo_TypeModel::where('id', $id)->first();
        return $pos;
    }
}

if (!function_exists('getQuo')) {
    function getQuo($id)
    {
        $quo = QuotationModel::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('getQuo_No')) {
    function getQuo_No($id)
    {
        $name = $id == 0 ? '' : QuotationModel::where('id', $id)->first()->quo_no;

        return $name;
    }
}


if (!function_exists('getCustWh')) {
    function getCustWh($id)
    {
        $quo = Purchase_order::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('getPOdet')) {
    function getPOdet($id)
    {
        $quo = Purchase_detail::where('id_product', $id)
            ->join('purchase_orders as p', 'p.id', '=', 'purchase_detail.id_po')->first();
        return $quo;
    }
}
if (!function_exists('getPOdetNew')) {
    function getPOdetNew($id, $quo, $ven)
    {
        $quo = Purchase_detail::where([
            ['sku', $id],
            ['id_vendor', $ven],
            ['purchase_detail.id_quo', $quo],
            ['p.status', '!=', 'reject']
        ])->join('purchase_orders as p', 'p.id', '=', 'purchase_detail.id_po')->first();
        
        $ref = Purchase_detail::where([
            ['sku', $id],
            ['id_vendor', $ven],
            ['purchase_detail.no_ref', $quo],
            ['p.status', '!=', 'reject']
        ])->join('purchase_orders as p', 'p.id', '=', 'purchase_detail.id_po')->first();


      if($quo == null )
      {
        $datas = $ref;
      }else if($quo!=null){
        $datas = $quo;
      }else{
        $datas = null;
      }

      return $datas;
    }
}
if (!function_exists('getPO')) {
    function getPO($id)
    {
        $quo = Purchase_order::where('purchase_orders.id_quo', $id)
            ->join('quotation_product as p', 'p.id_quo', '=', 'purchase_orders.id_quo')->first();
        // dd($quo);
        return $quo;
    }
}

if (!function_exists('getSumPObackup')) {
    function getSumPObackup($id)
    {
        $quo = PurchaseMigrateDetail::where('id_po', $id)->get();
        $sum = 0;
        foreach ($quo as $key => $value) {
            $sum += $value->qty * $value->price;
        }
        $total = $sum + ($sum / 10);
        return $total;
    }
}

if (!function_exists('getVendor')) {
    function getVendor($id)
    {
        $quo = VendorModel::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('getCustomer')) {
    function getCustomer($id)
    {
        $quo = CustomerModel::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('getCustomerPIC')) {
    function getCustomerPIC($id)
    {
        $quo = Customer_pic::where('id_customer', $id)->first();
        return $quo;
    }
}

if (!function_exists('getForwarder')) {
    function getForwarder($id)
    {
        $quo = ShippingModel::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('getOutbound')) {
    function getOutbound($where, $id)
    {
        $quo = Warehouse_out::where($where, $id)->first();
        return $quo;
    }
}

if (!function_exists('getWarehouse')) {
    function getWarehouse($where, $id)
    {
        $quo = Warehouse_order::where($where, $id)->first();
        return $quo;
    }
}

if (!function_exists('warehouse_addr')) {
    function warehouse_addr($id)
    {
        $quo = Warehouse_address::where('id', $id)->first();
        return $quo;
    }
}

if (!function_exists('getWarehouseDet')) {
    function getWarehouseDet($where, $id, $idwo)
    {
        $quo = Warehouse_detail::where([[$where, $id], ["id_wo", $idwo]])->first();
        return $quo;
    }
}



if (!function_exists('getWarehouseResi')) {
    function getWarehouseResi($where, $id)
    {
        $quo = Warehouse_resi::where($where, $id)->first();
        // dd($quo);
        return $quo;
    }
}


if (!function_exists('getWarehouseResiFirst')) {
    function getWarehouseResiFirst($idwo, $id)
    {
        // dd($idwo, $id);
        $quo = Warehouse_resi::where([['id_wo', $idwo],['id_address', $id]])->first();
        // dd($quo);
        return $quo;
    }
}


if (!function_exists('getDoNumber')) {
    function getDoNumber($quo)
    {
        $main     = WarehouseOut::where('id_quo', $quo)->first();
        if (is_null($main)) {
            $donumber = [
                'number' => "-",
                'date'   => "-"
            ];
        } else {
            $tanda    = Warehouse_pengiriman::where('id_wh_out', $main->id)->first();
            $donumber = [
                'number' => "WH/OUT/" . Carbon::now()->format('y') . "/" . sprintf("%06d", $main->id),
                'date'   => $tanda == null ? "-" : $tanda->tanggal_kirim
            ];
        }
        // dd($main);


        return $donumber;
    }
}

if (!function_exists('AddActivity')) {
    function AddActivity($id_quo, $detail)
    {
        $log = array(
            'activity_id_quo'       => $id_quo,
            'activity_id_user'      => Auth::id(),
            'activity_name'         => $detail,
            'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
        );
        // dd($data2);
        ActQuoModel::insert($log);
    }
}



//////////////// WAREHOUSE UPDATE /////////////////
///////////////////////////////////////////////////

if (!function_exists('CheckWhInbound')) {
    function CheckWhInbound($where, $id, $idwo, $id_quo, $qty)
    {
        $quo = WarehouseIn::join('warehouse_inbound_detail', 'warehouse_inbound.id', '=', 'warehouse_inbound_detail.id_inbound')
            ->where([[$where, $id],['qty_terima', $qty], ['id_po', $idwo], ['warehouse_inbound_detail.id_quo', '=', $id_quo]])->first();
        return $quo;
    }
}



if (!function_exists('getWarehouseIn')) {
    function getWarehouseIn($where, $id)
    {
        $quo = WarehouseIn::where($where, $id)->first();
        return $quo;
    }
}



if (!function_exists('WarehouseAddress')) {
    function WarehouseAddress($id)
    {
        $quo = Warehouse_address::where('id', $id)->first();
        return $quo;
    }
}


if (!function_exists('CountQtyKirim')) {
    function CountQtyKirim($id, $sku)
    {
        $quo = WarehouseOutDetail::select('qty_kirim')->where([['id_outbound', $id], ['sku', $sku], ['type_do', '!=', 'rekap']])->sum('qty_kirim');
        return $quo;
    }
}


if (!function_exists('getWhOutDetail')) {
    function getWhOutDetail($where, $id, $sku)
    {
        $quo = WarehouseOutDetail::where([[$where, $id], ['sku', $sku]])->orderBy('id', 'desc')->first();
        return $quo;
    }
}


if (!function_exists('SendToOut')) {
    function SendToOut($id_quo)
    {
        $check = WarehouseOut::where('id_quo', $id_quo)->first();
        $data  = WarehouseOut::where('id_quo', $id_quo)->get()->max('id');
        if ($check == null) {
            $data_quo = [
                'id_quo'      => $id_quo,
                'created_by'  => Auth::id(),
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
            ];
            $qrys = WarehouseOut::create($data_quo);
            if ($qrys) {
                $test = WarehouseOut::where('id_quo', $id_quo)->first();
                $data = [
                    'no_do'   => "WH/OUT/" . Carbon::now()->format('y') . "/" . sprintf("%06d", $test->id),
                ];
                $save = WarehouseOut::where('id', $qrys->id)->update($data);
            }
        }
        return $save;
    }
}


if (!function_exists('getQtyTerima')) {
    function getQtyTerima($id_quo, $sku)
    {
        $quo = WarehouseIn::join('warehouse_inbound_detail', 'warehouse_inbound.id', '=', 'warehouse_inbound_detail.id_inbound')
            ->where([['warehouse_inbound_detail.id_quo', $id_quo], ['sku', $sku]])->first();
        $hasil = $quo == null ? "Belum Diterima" : $quo->qty_terima;
        return $hasil;
    }
}


if (!function_exists('getStatusQuo')) {
    function getStatusQuo($id_quo)
    {
        $quo = $id_quo == 0 ? '' : QuotationModel::where('id', $id_quo)->first()->quo_ekskondisi;
        return $quo;
    }
}


if (!function_exists('CheckWhInventory')) {
    function CheckWhInventory($id_quo, $sku)
    {
        $sku = getProductDetail($sku)->product_id;
        $check   = InventoryModel::where([
            ['sku', $sku],
            ['id_quo', $id_quo],
        ])->first();
        return $check;
    }
}


if (!function_exists('getCheckList')) {
    function getCheckList($id_quo, $sku, $qty)
    {
        // //check inventory
        $prd_id    = getProductDetail($sku)->product_id;
        $check_inv = InventoryModel::where([
            ['sku', $prd_id],
            ['id_quo', $id_quo],
        ])->first();

        $check_outbound = WarehouseOut::where('id_quo', $id_quo)->first();
        $quo_mo        = QuotationProduct::where([['id_quo', $id_quo], ['id_product', $sku],['det_quo_qty', $qty]])->first();
        // dd($quo_mo);
        if ($check_inv == null && $id_quo != 681) {
            $check_inbound = WarehouseIn::join('warehouse_inbound_detail', 'warehouse_inbound.id', '=', 'warehouse_inbound_detail.id_inbound')
                ->where([['warehouse_inbound_detail.id_quo', $id_quo], ['sku', $sku],['qty_po', $qty]])->first();

            $check_again = WarehouseIn::join('warehouse_inbound_detail', 'warehouse_inbound.id', '=', 'warehouse_inbound_detail.id_inbound')
                ->where([['warehouse_inbound_detail.id_quo', $id_quo], ['sku', $sku],['qty_terima', $qty]])->get()->sum('qty_terima');
            // dd($check_again, $check_inbound);

            $accept     = $check_inbound == null ? $check_again : $check_inbound->qty_terima;
            $posisi     = 'inbound';
            $qty_terima = $accept == 0 || $accept == null ? 0 : $accept;
            $green      = $qty_terima == 0 ? 'no' : 'yes';
            $close      = $green == 'no' ? 'show' : 'not_show';
            $wh_detail  = $check_outbound == null ? null : WarehouseOutDetail::select('qty_kirim')->where([['id_outbound', $check_outbound->id], ['sku', $sku],['qty_kirim', $qty], ['type_do', '!=', 'rekap']])->sum('qty_kirim');
            $qty_kirim  = $wh_detail;
            $read       = $qty_terima == $qty_kirim ? 'readonly' : 'required';
            $blue       = $qty_terima != 0 && $qty_terima == $qty_kirim ? "oke" : "not";
        // dd($posisi, $qty_terima, $green, $close, $qty_kirim, $read, $blue, $accept);
        } else if ($check_inv == null && $id_quo == 681) {
            $wh_detail  = $check_outbound == null ? null : WarehouseOutDetail::select('qty_kirim')->where([['id_outbound', $check_outbound->id], ['sku', $sku],['qty_kirim', $qty], ['type_do', '!=', 'rekap']])->sum('qty_kirim');

            $posisi     = "inventory";
            $qty_terima = $quo_mo->det_quo_qty;
            $green      = $qty_terima == $wh_detail ? "no" : "yes";
            $close      = 'not_show';
            $qty_kirim  = $wh_detail;
            $blue       = $qty_terima == $qty_kirim ? "oke" : "not";
            $read       = $qty_terima == $qty_kirim ? 'readonly' : 'required';
        } else {
            $wh_detail  = $check_outbound == null ? null : WarehouseOutDetail::select('qty_kirim')->where([['id_outbound', $check_outbound->id], ['sku', $sku],['qty_kirim', $qty], ['type_do', '!=', 'rekap']])->sum('qty_kirim');
            $posisi     = "inventory";
            $qty_terima = $quo_mo->det_quo_qty;
            $green      = $qty_terima == $wh_detail ? "no" : "yes";
            $close      = 'not_show';
            $qty_kirim  = $wh_detail;
            $blue       = $qty_terima == $qty_kirim ? "oke" : "not";
            $read       = $qty_terima == $qty_kirim ? 'readonly' : 'required';

        }

        return $hasil = [
            'posisi'     => $posisi,
            'qty_terima' => $qty_terima == 0 ? "Belum Diterima" : $qty_terima,
            'green'      => $green,
            'close'      => $close,
            'qty_kirim'  => $qty_kirim == 0 ? null : $qty_kirim,
            'read'       => $read,
            'blue'       => $blue,
        ];
    }
}



//////////////// WAREHOUSE UPDATE /////////////////
///////////////////////////////////////////////////



if (!function_exists('SearchActivity')) {
    function SearchActivity($id_quo, $search)
    {
        return ActQuoModel::where([
            ['activity_id_quo', $id_quo],
            ['activity_name', 'like', '%' . $search . '%'],
        ])->first();
    }
}

if (!function_exists('CheckLengkap')) {
    function CheckLengkap($id_quo)
    {
        $product = QuotationProduct::where('id_quo', $id_quo)->count();
        $sudah   = QuotationProduct::whereRaw("id_quo = '$id_quo' AND det_quo_harga_modal IS NOT NULL")->count();
        return $product - $sudah;
    }
}

if (!function_exists('StockCheck')) {
    function StockCheck($sku, $id_quo)
    {
        if ($sku <> 'new') {
            $prd_id = getProductDetail($sku)->product_id;

            $product = QuotationProduct::where([
                ['id_quo', $id_quo],
                ['id_product', $sku],
            ])->first();
            $masuk   = InventoryModel::where([
                ['sku', $prd_id],
                ['status', 'order'],
            ])->orderBy('id', 'desc')->first();
            $cinv = InventoryModel::where([
                ['sku', $prd_id],
                ['id_quo', $id_quo],
                ['status', 'use'],
            ])->first();
            $helpqty  = StockQty($sku);
            $stockqty = $cinv == null ? $helpqty : $cinv->qty ;
            $cmargin  = MarginCheck($id_quo);
            $allowmin = in_array($sku, array('SKU123238387', 'SKU123236965'));
            $izin = $allowmin == true ? $allowmin : $stockqty > 0;
            if ($product->id_vendor == null && $izin) {

                $condition  = 'yes';
                $vendor     = $stockqty == 0 ? 'normal' : 'Warehouse Stock';
                $last_price = $masuk->price;
                $stock      = StockAddMin($cmargin, $sku, $id_quo);
                // $outbound   = $stock == 'yes' ? SendToOutbound($id_quo) : '';
            } else {
                $condition = 'no';
                $vendor    = $stockqty == 0 ? 'normal' : 'Stock';
                $last_price = 0;
            }


            $data = [
                'condition' => $condition,
                'vendor'    => $vendor,
                'sisa'      => $helpqty,
                'price'     => $last_price,
            ];

            return $data;
        } else {
            $data = [
                'condition' => 'no',
            ];

            return $data;
        }
    }
}



if (!function_exists('saveInventory')) {
    function saveInventory($id_quo)
    {
        //inventory
        $quo_pro    = QuotationProduct::where('id_quo', $id_quo)->get();
        $quo_mos    = QuotationModel::where('id', $id_quo)->first();
        foreach ($quo_pro as $quo) {
            $prd_id = getProductDetail($quo->id_product)->product_id;
            $product = QuotationProduct::where([
                ['id_quo', $id_quo],
                ['id_product', $quo->id_product],
            ])->first();
            $masuk   = InventoryModel::where([
                ['sku', $prd_id],
                ['status', 'order'],
            ])->orderBy('id', 'desc')->first();
            $order_awal = InventoryModel::where([
                ['sku', $prd_id],
                ['status', 'order'],
            ])->first();
            $cinv = InventoryModel::where([
                ['sku', $prd_id],
                ['id_quo', $id_quo],
                ['status', 'use'],
            ])->first();
            $stockqty = StockQty($quo->id_product);
            $cmargin  = MarginCheck($id_quo);
            $allowmin = in_array($quo->id_product, array('SKU123238387', 'SKU123236965'));
            $izin = $allowmin == true ? $allowmin : $stockqty > 0;
            if ($product->id_vendor == null && $izin) {
                if ($quo_mos->quo_type > 1) {
                    if ($cmargin >= getConfig('automargin')) {
                        if ($cinv == null) {
                            $datainv = [
                                'id_quo'     => $id_quo,
                                'sku'        => $prd_id,
                                'qty'        => $product->det_quo_qty,
                                'price'      => $order_awal->price,
                                'jenis'      => "sales",
                                'status'     => "use",
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                            ];
                            InventoryModel::insert($datainv);
                        }
                        $checkharga = QuotationProduct::where('id', $product->id)->first();
                        if ($checkharga->det_quo_harga_modal == null) {
                            $data2 = [
                                'det_quo_harga_modal'   => $order_awal->price,
                            ];
                            QuotationProduct::where('id', $product->id)->update($data2);
                        }
                        $outbound = "yes";
                    } else {
                        if (CheckApprove($id_quo) == 'yes') {
                            if ($cinv == null) {
                                $datainv = [
                                    'id_quo'     => $id_quo,
                                    'sku'        => $prd_id,
                                    'qty'        => $product->det_quo_qty,
                                    'price'      => $order_awal->price,
                                    'jenis'      => "sales",
                                    'status'     => "use",
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                ];
                                InventoryModel::insert($datainv);
                            }
                            $checkharga = QuotationProduct::where('id', $product->id)->first();
                            if ($checkharga->det_quo_harga_modal == null) {
                                $data2 = [
                                    'det_quo_harga_modal'   => $order_awal->price,
                                ];
                                QuotationProduct::where('id', $product->id)->update($data2);
                            }
                            $outbound = "yes";
                        } else {
                            if ($cinv !== null) {
                                InventoryModel::where('id', $cinv->id)->delete();
                            }
                            $outbound = "no";
                        }
                    }
                }
                return $outbound;
            }
        }
    }
}


if (!function_exists('StockQty')) {
    function StockQty($sku)
    {
        $prd_id = getProductDetail($sku)->product_id;
        $masuk  = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'order'],
        ])->sum('qty');
        $keluar = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'use'],
        ])->sum('qty');
        $pinjam = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'pinjam'],
        ])->sum('qty');

        return $masuk - $keluar - $pinjam;
    }
}

if (!function_exists('StockAddMin')) {
    function StockAddMin($margin, $sku, $id_quo)
    {
        $prd_id  = getProductDetail($sku)->product_id;
        $product = QuotationProduct::where([
            ['id_quo', $id_quo],
            ['id_product', $sku],
        ])->first();

        $order_awal = InventoryModel::where([
            ['sku', $prd_id],
            ['status', 'order'],
        ])->first();

        $cinv = InventoryModel::where([
            ['sku', $prd_id],
            ['id_quo', $id_quo],
            ['status', 'use'],
        ])->first();
        $quo      = QuotationModel::where('id', $id_quo)->first();
        $outbound = "no";

        if ($quo->quo_type > 1) {
            if ($margin >= getConfig('automargin')) {

                $checkharga = QuotationProduct::where('id', $product->id)->first();
                if ($checkharga->det_quo_harga_modal == null) {
                    $data2 = [
                        'det_quo_harga_modal'   => $order_awal->price,
                    ];
                    QuotationProduct::where('id', $product->id)->update($data2);
                }
                $outbound = "yes";
            } else {
                if (CheckApprove($id_quo) == 'yes') {
                    $checkharga = QuotationProduct::where('id', $product->id)->first();
                    if ($checkharga->det_quo_harga_modal == null) {
                        $data2 = [
                            'det_quo_harga_modal'   => $order_awal->price,
                        ];
                        QuotationProduct::where('id', $product->id)->update($data2);
                    }
                    $outbound = "yes";
                } else {
                    if ($cinv !== null) {
                        InventoryModel::where('id', $cinv->id)->delete();
                    }
                    $outbound = "no";
                }
            }
        }
        return $outbound;
    }
}


if (!function_exists('MarginCheck')) {
    function MarginCheck($id_quo)
    {
        $price   = QuotationModel::where('id', $id_quo)->first();
        $product = QuotationProduct::where('id_quo', $id_quo)->get();

        $subtotal_modal = $subtotal_ongkir = $subtotal_order = 0;
        $margin = 0;
        foreach ($product as $val) {

            $subtotal_order  += $val->det_quo_harga_order * $val->det_quo_qty;
            $subtotal_modal  += $val->det_quo_harga_modal * $val->det_quo_qty;
            $subtotal_ongkir += $val->det_quo_harga_ongkir * $val->det_quo_qty;
            $margin          += ($val->det_quo_harga_order * $val->det_quo_qty) - ($val->det_quo_harga_modal * $val->det_quo_qty);
        }
        if ($subtotal_modal == 0 || $subtotal_modal == NULL || $subtotal_order == 0 || $subtotal_order == NULL) {
            $grossm = 0;
        } else {
            $vat           = $subtotal_order * (GetPPN(GetInvoiceDate($id_quo), $price->quo_order_at) / 100);
            $invoice_price = $subtotal_order + $vat;
            $grossm        = round(($margin / $invoice_price) * 100, 2);
        }
        return $grossm;
    }
}
if (!function_exists('MarginCheckLama')) {
    function MarginCheckLama($id_quo)
    {
        $price   = QuotationModel::where('id', $id_quo)->first();
        $product = QuotationProduct::where('id_quo', $id_quo)->get();

        $subtotal_modal = $subtotal_ongkir = $subtotal_order = 0;
        $margin = 0;
        foreach ($product as $val) {

            $subtotal_order  += $val->det_quo_harga_order * $val->det_quo_qty;
            $subtotal_modal  += $val->det_quo_harga_modal * $val->det_quo_qty;
            $subtotal_ongkir += $val->det_quo_harga_ongkir * $val->det_quo_qty;
            $margin          += ($val->det_quo_harga_order * $val->det_quo_qty) - ($val->det_quo_harga_modal * $val->det_quo_qty);
        }

        $vat           = $subtotal_order / 10;
        $nego_ongkir   = $price->ongkir_customer == null ? '-' : number_format($price->ongkir_customer);

        $invoice_price = $subtotal_order + $vat;
        $finalongkir   = $subtotal_ongkir - $price->ongkir_customer;
        $pph           = ($subtotal_order * 1.5) / 100;
        $sp2d          = $subtotal_order - $pph;

        $price_if   = $price->price_if_type == 'percen' ? ($sp2d * $price->price_if) / 100 : $price->price_if;
        $otherprice = $price->price_other + $price_if + $finalongkir;
        $submargin  = $margin - $otherprice;
        $vatmodal   = $subtotal_modal / 10;

        $incmodal  = $subtotal_modal + $vatmodal;
        $selisihp  = $vat - $vatmodal;
        $restitusi = ($vatmodal * 80) / 100;
        $fmargin   = $sp2d - $incmodal - $selisihp + $restitusi - $otherprice;
        if ($subtotal_modal == 0 || $subtotal_modal == NULL) {
            $fpercent = 0;
        } else {
            $ftpercent = ($fmargin / $invoice_price) * 100;
            $fpercent  = round($ftpercent, 2);
        }

        $komisia = ($fmargin * 2.2) / 100;
        $komisib = ($fmargin * 2.8) / 100;

        $finalmargin = $fmargin - $komisia - $komisib;

        if ($subtotal_modal == 0 || $subtotal_modal == NULL) {
            $Lpercent = 0;
        } else {
            $Ltpercent = ($finalmargin / $invoice_price) * 100;
            $Lpercent  = round($Ltpercent, 2) . " %";
        }

        return $fpercent;
    }
}

if (!function_exists('CheckApprove')) {
    function CheckApprove($id_quo)
    {
        $check = ActQuoModel::where([
            ['activity_id_quo', $id_quo],
            ['activity_name', 'like', '%Paket ini sudah di approve%'],
        ])->orderBy('activity_id', 'DESC')->first();

        return $check == null ? 'no' : 'yes';
    }
}

if (!function_exists('GetInvoice')) {
    function GetInvoice($where, $id)
    {
        $cinv     = QuotationInvoice::where($where, $id)->first();
        return $cinv;
    }
}


if (!function_exists('sumInvoicePaid')) {
    function sumInvoicePaid($id)
    {
        $cinv     = QuotationInvoicePaymentDetail::where('id_quo_inv', $id)->get()->sum('payment_amount');
        return $cinv;
    }
}


if (!function_exists('SisaHargaInvoice')) {
    function SisaHargaInvoice($id)
    {
        $quo_in     = QuotationInvoice::where('id', $id)->first();
        $quo_mo     = QuotationModel::where('id', $quo_in->id_quo)->first();
        $harga      = getPriceInvoice($quo_in->id_quo);
        $other      = QuotationInvoiceOthers::where('id_quo_inv', $quo_in->id)->get()->sum('nilai_potongan');
        $dtl        = QuotationInvoicePaymentDetail::where('id_quo_inv', $quo_in->id)->get()->sum('payment_amount');
        $inv_oth    = QuotationInvoiceOthers::where('id_quo_inv', $quo_in->id)->get();
        $coth       = count($inv_oth);
        $sisa       = $coth==0 ? ($harga['total'] - $dtl - $quo_in->potongan_ntpn_ppn - $quo_in->potongan_ntpn_pph) : ($quo_mo->quo_price - $dtl - $other - $quo_in->potongan_ntpn_ppn - $quo_in->potongan_ntpn_pph); 
        return $sisa;
    }
}


if (!function_exists('getTotalInvDetail')) {
    function getTotalInvDetail($id)
    {
        $cinv     = QuotationInvoicePaymentDetail::where('id_dtl_payment', $id)->get()->sum('payment_amount');
        return $cinv;
    }
}


if (!function_exists('getAllInvPayment')) {
    function getAllInvPayment($id)
    {
        $cinv     = QuotationInvoicePaymentDetail::where('id_dtl_payment', $id)->get();
        return $cinv;
    }
}





if (!function_exists('GetInvoiceDate')) {
    function GetInvoiceDate($id_quo)
    {
        $cinv     = QuotationInvoice::where('id_quo', $id_quo)->first();
        if ($cinv == null) {
            $res = '0000-00-00';
        } else {
            $countinv = InvoiceModel::where('id_quo', $id_quo)->orderBy('id', 'desc')->first();
            if ($countinv == null) {
                $res = $cinv->tgl_invoice;
            } else {
                $res = $countinv->tgl_invoice;
            }
        }

        return $res;
    }
}

if (!function_exists('SendToOutbound')) {
    function SendToOutbound($id_quo)
    {
        $check = warehouse_out::where('id_quo', $id_quo)->first();
        if ($check == null) {
            $data_quo = [
                'id_quo'      => $id_quo,
                'id_cust'     => getQuo($id_quo)->id_customer,
                'created_by'  => Auth::id(),
                'created_at'  => Carbon::now('GMT+7')->toDateTimeString()
            ];
            warehouse_out::insert($data_quo);
        }
    }
}



if (!function_exists('getQtyIn')) {
    function getQtyIn($sku, $status, $qtys, $hitung)
    {
        $first = InventoryModel::where([
            ['sku', $sku],
            ['status', 'use'],
        ])->orderBy('id', 'asc')->first();

        $brw = InventoryModel::where([
            ['sku', $sku],
            ['status', 'use'],
        ])->orderBy('id', 'asc')->first();

        $order  = InventoryModel::where([
            ['sku', $sku],
            ['status', 'order'],
        ])->sum('qty');

        $limits  = InventoryModel::where([
            ['sku', $sku],
            ['status', 'use'],
        ])->orderBy('id', 'asc')->limit($hitung)->get();

        if ($status == "order") {
            $qty  = InventoryModel::where([
                ['sku', $sku],
                ['status', 'order'],
            ])->sum('qty');
        }
        // else if ($first->qty == $qtys) {
        //     $qty  = InventoryModel::where([
        //         ['sku', $sku],
        //         ['status', 'order'],
        //     ])->sum('qty');
        // }
        //use lanjutan
        else {
            if (count($limits) != 1 && $status == "use") {
                $skip = InventoryModel::where([
                    ['sku', $sku],
                    ['status', '!=', 'order'],
                ])->orderBy('id', 'asc')->limit($hitung)->get()->sum('qty');
                $qty  = $order - $skip;
            } else if ($status == "pinjam") {
                $skip = InventoryModel::where([
                    ['sku', $sku],
                    ['status', '!=', 'order'],
                ])->orderBy('id', 'asc')->limit($hitung)->get()->sum('qty');
                $qty = $order - $skip;
            } else {
                $qty = $order - $first->qty;
            }
        }
        return $qty;
    }
}

if (!function_exists('QtySisa')) {
    function QtySisa($id)
    {

        $checkid = InventoryModel::where('id', $id)->first();
        $checkstock = InventoryModel::where([
            ['sku', $checkid->sku],
            ['id', '<', $id],
            ['status', 'order'],
        ])->sum('qty');
        $lastuse = InventoryModel::whereRaw("sku = $checkid->sku AND id <= $id
        AND (status = 'use' or status = 'pinjam')")
            ->sum('qty');

        if ($checkstock == 0) {
            $laststock = InventoryModel::where([
                ['sku', $checkid->sku],
                ['id', $id],
                ['status', 'order'],
            ])->sum('qty');
            $stock = $laststock;
        } else {
            $laststock = InventoryModel::where([
                ['sku', $checkid->sku],
                ['id', '<=', $id],
                ['status', 'order'],
            ])->sum('qty');
            $stock = $laststock;
        }
        $qty = $stock - $lastuse;
        return $qty;
    }
}

if (!function_exists('CheckDODetail')) {
    function CheckDODetail($id, $sku)
    {
        $get = WarehouseOut::select('*', 'd.no_do as nomer', 'd.tgl_kirim as kirim')
            ->where([
                ['id_quo', $id],
                ['sku', $sku]
            ])
            ->join('warehouse_outbound_detail as d', 'd.id_outbound', '=', 'warehouse_outbound.id')->first();
        return $get;
    }
}

if (!function_exists('SendEmailDeadline')) {
    function SendEmailDeadline($id)
    {
        $check    = QuotationModel::where('id', $id)->first();
        $datediff = strtotime($check->quo_deadline) - time();
        $hari     = round($datediff / (60 * 60 * 24));

        $nmail          = new \stdClass();
        $nmail->subject = '[' . $hari . ' Hari] Deadline paket ' . $check->quo_no . ' ' . getCustomer($check->id_customer)->company;
        $nmail->title   = 'Paket ' . $check->quo_no . ' akan berakhir pada ' . $check->quo_deadline;
        $nmail->detail  = $check;
        $nmail->from    = 'noreply@maleser.co.id';

        $emailsales = getDataEmp('id_emp', $check->id_sales);
        $emailadmin = getDataEmp('id_emp', $check->id_admin);
        $cc         = array(
            'management@maleser.com',
            'operation@maleser.com',
            $emailsales->emp_email,
            $emailadmin->emp_email
        );
        // dd($nmail);

        Mail::to('record@maleser.com')
            ->cc($cc)->send(new MailDeadline($nmail));
    }
}

if (!function_exists('SendEmailDeadlineVendor')) {
    function SendEmailDeadlineVendor($id)
    {
        $check    = Pay_VoucherDetail::where('id', $id)->first();
        $po       = Purchase_order::where('po_number', $check->no_po)->first();
        $datediff = strtotime($check->to_date) - time();
        $hari     = round($datediff / (60 * 60 * 24));

        $nmail              = new \stdClass();
        $nmail->subject     = '[' . $hari . ' Hari] Deadline pembayaran vendor ' . getVendor($po->id_vendor)->vendor_name . ' ' . $check->no_po;
        $nmail->title       = $check->no_po . ' deadline bayar pada ' . $check->to_date;
        $nmail->detail      = [$check, $po];
        $nmail->from        = 'noreply@maleser.co.id';
        // dd($nmail);

        Mail::to('record@maleser.com')
            ->cc('management@maleser.com', 'product@maleser.com', 'purchasing@maleser.com')->send(new MailDeadlineVendor($nmail));
    }
}



//======= email section =============//

if (!function_exists('getEmpEmailSpecial')) {
    function getEmpEmailSpecial($id)
    {
        $dev = 'noreply@maleser.co.id';
        $emp = EmployeeModel::select('emp_email')->where('id', $id)->first();
        if (empty($emp->emp_email)) {
            return $dev;
        } else {
            return $emp->emp_email;
        }
        //dd($emp->email);

    }
}



if (!function_exists('SendEmailNotif')) {
    function SendEmailNotif($jenis, $u_creator, $detail, $url, $event, $menu)
    {
        $nmail              = new \stdClass();
        $nmail->created_by  = $u_creator;
        $nmail->subject     = $menu . ' Created';
        $nmail->title       = $menu . ' Created';
        $nmail->description = $event;
        $nmail->detail      = $detail;
        $nmail->curent_url  = $url;
        $nmail->from        = getEmpEmailSpecial($u_creator);
        // dd($nmail);

        Mail::to(getEmpEmailSpecial($u_creator))->send(new UserCreate($nmail));
    }
}

if (!function_exists('SendEmailVendor')) {
    function SendEmailVendor($u_creator, $vendormail, $cc, $detail, $event, $nomer_po)
    {
        $nmail              = new \stdClass();
        $nmail->subject     = 'PT MITRA ERA GLOBAL Order (Ref.' . $nomer_po . ')';
        $nmail->title       = $nomer_po;
        $nmail->description = $event;
        $nmail->detail      = $detail;
        $nmail->from        = getEmpEmailSpecial($u_creator);

        if ($cc == 'no') {
            Mail::to($vendormail)
                ->bcc(['purchasing@maleser.com', 'product@maleser.com'])
                ->send(new SendPOmail($nmail));
        } else {
            Mail::to($vendormail)
                ->cc($cc)
                ->bcc(['purchasing@maleser.com', 'product@maleser.com'])
                ->send(new SendPOmail($nmail));
        }
    }
}

if (!function_exists('SendEmailResetMaster')) {
    function SendEmailResetMaster($detail, $url, $event, $menu)
    {
        $nmail              = new \stdClass();
        $nmail->subject     = $menu . ' Reset Master Key';
        $nmail->title       = $menu . ' Reset Master Key';
        $nmail->description = $event;
        $nmail->detail      = $detail;
        $nmail->curent_url  = $url;
        $nmail->from        = 'noreply@maleser.co.id';
        // dd($nmail);

        Mail::to('ariskindo@gmail.com')->send(new ResetMasterKey($nmail));
    }
}


if (!function_exists('SendEmailCash')) {
    function SendEmailCash($id, $type)
    {
        $email              = getEmp($type)->emp_email;
        $cash               = CashAdvance::where('id', $id)->first();
        $nmail              = new \stdClass();
        $nmail->created_by  = $cash->created_by;
        $nmail->subject     = 'Cash Advance - Approval Submission';
        $nmail->title       = 'Approval Submission';
        $nmail->description = "Approval For Cash Advance " . $cash->no_cashadv;
        $nmail->no_cashadv  = $cash->no_cashadv;
        $nmail->curent_url  = "https://home.maleser.co.id/finance/cash_advance/" . $id . "/show";
        // dd($nmail);
        Mail::to($email)
            ->cc('aji@maleser.com', 'nurul.aryani@maleser.com')
            ->send(new ApproveMail($nmail));
    }
}


if (!function_exists('SendEmailCashSPV')) {
    function SendEmailCashSPV($id, $type)
    {
        $email              = "operation@maleser.com";
        $cash               = CashAdvance::where('id', $id)->first();
        $nmail              = new \stdClass();
        $nmail->created_by  = $cash->created_by;
        $nmail->subject     = 'Cash Advance - Approval Submission';
        $nmail->title       = 'Approval Submission';
        $nmail->description = "Approval For Cash Advance " . $cash->no_cashadv;
        $nmail->description2 = "Has Been " . $cash->status . " by " . user_name($cash->app_spv);
        $nmail->no_cashadv  = $cash->no_cashadv;
        $nmail->curent_url  = "https://home.maleser.co.id/finance/cash_advance/" . $id . "/show";
        // dd($nmail);
        Mail::to($email)
            ->cc('aji@maleser.com', 'nurul.aryani@maleser.com')
            ->send(new ApproveSpvMail($nmail));
    }
}

if (!function_exists('SendEmailCashHR')) {
    function SendEmailCashHR($id, $type)
    {
        $email              = "aji@maleser.com";
        $cash               = CashAdvance::where('id', $id)->first();
        $nmail              = new \stdClass();
        $nmail->created_by  = $cash->created_by;
        $nmail->subject     = 'Cash Advance - Process Submission';
        $nmail->title       = 'Process Submission';
        $nmail->description = "Please Proccess This Cash Advance " . $cash->no_cashadv;
        $nmail->description2 = "Has Been " . $cash->status . " by " . user_name($cash->app_spv) . " & " . user_name($cash->app_hr);
        $nmail->no_cashadv  = $cash->no_cashadv;
        $nmail->curent_url  = "https://home.maleser.co.id/finance/cash_advance/" . $id . "/show";
        Mail::to($email)
            ->cc('nurul.aryani@maleser.com')
            ->send(new ApproveHrEmail($nmail));
    }
}


if (!function_exists('SendEmailSettle')) {
    function SendEmailSettle($id, $type)
    {
        $email              = getEmp($type)->emp_email;
        $set                = FinanceSettlementModel::where('id', $id)->first();
        $nmail              = new \stdClass();
        $nmail->created_by  = $set->created_by;
        $nmail->subject     = 'Settlement - Approval Submission';
        $nmail->title       = 'Approval Submission';
        $nmail->description = "Approval For Settlement " . $set->no_settlement;
        $nmail->no_cashadv  = $set->no_settlement;
        $nmail->curent_url  = "https://home.maleser.co.id/finance/settlement/" . $id . "/show";
        Mail::to($email)
            ->cc('aji@maleser.com', 'nurul.aryani@maleser.com')
            ->send(new ApprovalToSpv($nmail));
    }
}


if (!function_exists('SendEmailSettleApproval')) {
    function SendEmailSettleApproval($id, $type)
    {
        if ($type == "spv") {
            $email              = "operation@maleser.com";
            $set                = FinanceSettlementModel::where('id', $id)->first();
            $nmail              = new \stdClass();
            $nmail->created_by  = $set->created_by;
            $nmail->subject     = 'Settlement - Approval Submission';
            $nmail->title       = 'Approval Submission';
            $nmail->description = "Approval For Settlement " . $set->no_settlement;
            $nmail->description2 = "Has Been " . $set->status . " by " . user_name($set->app_manage);
            $nmail->no_cashadv  = $set->no_settlement;
            $nmail->curent_url  = "https://home.maleser.co.id/finance/settlement/" . $id . "/show";
            Mail::to($email)
                ->cc('aji@maleser.com', 'nurul.aryani@maleser.com')
                ->send(new ApprovalToFin($nmail));
        } else {
            $email              = "aji@maleser.com";
            $set                = FinanceSettlementModel::where('id', $id)->first();
            $nmail              = new \stdClass();
            $nmail->created_by  = $set->created_by;
            $nmail->subject     = 'Settlement - Process Submission';
            $nmail->title       = 'Process Submission';
            $nmail->description = "Please Proccess This Settlement " . $set->no_settlement;
            $nmail->description2 = "Has Been " . $set->status . " by " . user_name($set->app_manage) . " & " . user_name($set->app_finance);
            $nmail->no_cashadv  = $set->no_settlement;
            $nmail->curent_url  = "https://home.maleser.co.id/finance/settlement/" . $id . "/show";
            Mail::to($email)
                ->cc('nurul.aryani@maleser.com')
                ->send(new ApprovalToFin($nmail));
        }
    }
}



if (!function_exists('SendEmailCustomer')) {
    function SendEmailCustomer($u_creator, $vendormail, $cc, $detail, $event, $nomer_po, $subject)
    {
        $nmail              = new \stdClass();
        $nmail->subject     = $subject;
        $nmail->title       = $nomer_po;
        $nmail->description = $event;
        $nmail->detail      = $detail;
        $nmail->from        = getEmpEmailSpecial($u_creator);

        if ($cc == 'no') {
            Mail::to($vendormail)
                ->bcc('farried.akhmad@maleser.com')
                ->send(new SendSQmail($nmail));
        } else {
            Mail::to($vendormail)
                ->cc($cc)
                ->bcc('farried.akhmad@maleser.com')
                ->send(new SendSQmail($nmail));
        }
    }
}

//============= Content =============//
//=================================//
if (!function_exists('manufacture')) {
    function Manufacture($id)
    {
        $name = $id == 0 ? '' : LiveManModel::where('manufacturer_id', $id)->first()->name;

        return $name;
    }
}

if (!function_exists('LiveCat')) {
    function LiveCat($id)
    {
        $name = LiveCatModel::where('category_id', $id)->first();

        return $name;
    }
}


if (!function_exists('LiveMan')) {
    function LiveMan($id)
    {
        $name = LiveManModel::where('manufacturer_id', $id)->first();
        return $name;
    }
}

if (!function_exists('CateID')) {
    function CateID($id)
    {
        $name = LiveCatModel::where('name', $id)->first();

        return $name;
    }
}

if (!function_exists('LikeCatID')) {
    function LikeCatID($id)
    {
        $name = LiveCatModel::where('name', 'LIKE', "%$id%")->first();
        return $name;
    }
}



if (!function_exists('ManID')) {
    function ManID($id)
    {
        $name = LiveManModel::where('name', $id)->first();

        return $name;
    }
}

if (!function_exists('LikeManID')) {
    function LikeManID($id)
    {
        $name = LiveManModel::where('name', 'LIKE', "%$id%")->first();
        if ($name == null) {
            $names = [
                'name'       => $id,
                'sort_order' => 0,
            ];
            $brand = [
                'brand_name'  =>  $id,
                'created_by'  => Auth::id(),
                'created_at'  => Carbon::now(),
            ];
            $qry  =  Product_brand::create($brand);
            $qry2 =  LiveManModel::create($names);
            if ($qry2) {
                $name = LiveManModel::where('name', 'LIKE', "%$id%")->first();
            }
        } else {
            $name = LiveManModel::where('name', 'LIKE', "%$id%")->first();
        }
        return $name;
    }
}




if (!function_exists('LiveMan')) {
    function LiveMan($id)
    {
        $name = LiveManModel::where('manufacturer_id', $id)->first();
        return $name;
    }
}


if (!function_exists('Category')) {
    function Category($id)
    {
        $name = $id == 0 ? '' : LiveCatModel::where('category_id', $id)->first()->name;

        return $name;
    }
}

if (!function_exists('Weight')) {
    function Weight($id)
    {
        $name = $id == 0 ? '' : LiveWeightModel::where('weight_class_id', $id)->first()->title;
        return $name;
    }
}


if (!function_exists('getWeightClass')) {
    function getWeightClass($id)
    {
        $name = LiveWeightModel::where('title', $id)->first();
        return $name;
    }
}

if (!function_exists('getCategoryId')) {
    function getCategoryId($id)
    {
        $name = LiveCatModel::where('name', $id)->first();
        return $name;
    }
}


if (!function_exists('FindLocationAPI')) {
    function FindLocationAPI($id)
    {
            // dd($id);
            $cari        = "&q=". $id. '&format=json';
            $locationiq  = 'https://us1.locationiq.com/v1/search?key=' . getConfig('locationIQ') . $cari;
            $json_string = $locationiq;
            $curl        = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $json_string,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_RETURNTRANSFER => true,
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            $get      = json_decode(curl_exec($curl), true);
            curl_close($curl);
            // dd($get);
            return $get!=null ? $get[0]['display_name'] : null;
    }
}



if (!function_exists('getAPIMeeting')) {
    function getAPIMeeting($id)
    {
            $cari        = "&q=". $id . '&format=json';
            $locationiq  = 'https://us1.locationiq.com/v1/search?key=' . getConfig('locationIQ') . $cari;
            $json_string = $locationiq;
            $curl        = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $json_string,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_RETURNTRANSFER => true,
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            $get      = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $get!=null ? $get[0]['display_name'] : null;
    }
}





if (!function_exists('LengthUnit')) {
    function LengthUnit($id)
    {
        $name = LiveLengthModel::where('title', $id)->first();
        // dd($name);
        return $name;
    }
}

if (!function_exists('LengthUnitID')) {
    function LengthUnitID($id)
    {
        $name = LiveLengthModel::where('length_class_id', $id)->first();
        return $name;
    }
}

if (!function_exists('ProtoCat')) {
    function ProtoCat($id)
    {
        $name = LiveCatModel::where('category_id', $id)->first();
        return $name;
    }
}


if (!function_exists('status')) {
    function status($id)
    {
        $name = QuotationStatus::where('id', $id)->first();
        return $name;
    }
}



///////////////////

if (!function_exists('province')) {
    function province($id)
    {
        $name = $id == 0 ? '' : Provinsi::where('id', $id)->first()->nama;
        return $name;
    }
}


if (!function_exists('city')) {
    function city($id)
    {
        $name = $id == 0 ? '' : Kota::where('id', $id)->first()->kota;
        return $name;
    }
}

if (!function_exists('country')) {
    function country($id)
    {
        $name = $id == 0 ? '' : Kecamatan::where('id', $id)->first()->nama;
        return $name;
    }
}

if (!function_exists('getInisial')) {
    function getInisial($name)
    {
        $data = explode(' ', $name);
        $ret = '';
        foreach ($data as $word)
            $ret .= strtoupper($word[0]);
        return $ret;
    }
}

if (!function_exists('CariBeda')) {
    function CariBeda($item, $request, $v)
    {
        $check  = QuotationProduct::where('id', $item)->first();
        $skun   = $check->id_product == 'new' ? getProductReq($check->id_product_request)->req_product : getProductDetail($check->id_product)->name;
        $id_quo = $request->id_quo;
        if ($check->det_quo_harga_modal <> $request->p_price[$v]) {
            $messageharga = " harga modal " . number_format($check->det_quo_harga_modal) . " => " . number_format($request->p_price[$v]);
        } else {
            $messageharga =  '';
        }
        if ($check->det_quo_note <> $request->note[$v]) {
            $messagenote = " catatan stock " . $check->det_quo_note . ' => ' . $request->note[$v];
        } else {
            $messagenote = '';
        }
        if ($check->det_quo_status_vendor <> $request->stock[$v]) {
            $messagestock = " status stock " . ucfirst($check->det_quo_status_vendor) . ' => ' . ucfirst($request->stock[$v]);
        } else {
            $messagestock = '';
        }
        if ($check->id_vendor <> $request->vendor[$v]) {
            $awalvendor = $check->id_vendor == null or $check->id_vendor == '' ? "Belum ada vendor" : getVendor($check->id_vendor)->vendor_name;
            $messagevendor = " vendor " . $awalvendor . ' => ' . getVendor($request->vendor[$v])->vendor_name;
        } else {
            $messagevendor = '';
        }
        $fullmessage = $messageharga . $messagenote . $messagestock . $messagevendor;

        if ($fullmessage <> '') {

            $log  = array(
                'activity_id_quo'       => $id_quo,
                'activity_id_user'      => Auth::id(),
                'activity_name'         => "Merubah " . $skun . " " . $fullmessage,
                'activity_created_date' => Carbon::now('GMT+7')->toDateTimeString()
            );
            // dd($log);
            ActQuoModel::insert($log);
        }
    }
}
if (!function_exists('PurchaseTotal')) {
    function PurchaseTotal($idpo)
    {
        $harga = 0;
        $main  = Purchase_order::where('id', $idpo)->first();
        $data  = Purchase_detail::where('id_po', $idpo)->get();
        foreach ($data as $key => $value) {
            $harga += $value->qty * $value->price;
        }
        $vat      = $harga * GetPPN($main->created_at, $main->created_at) / 100;
        $subtotal = $harga + $vat;
        return $subtotal;
    }
}

if (!function_exists('SNTotal')) {
    function SNTotal($id_out_det,$sku, $id_out, $ids)
    {
        $harga = 0;
        $checksn = WarehouseSN::where([
            ['id_quo', $id_out_det],
            ['sku', $sku],
            ['id_out', $id_out],
            ['id_out_det', $ids]
        ])->get();
        
        return count($checksn);
    }
}




if (!function_exists('CheckSNin')) {
    function CheckSNin($id_quo,$sku)
    {
        $checksn = WarehouseSN::where([
            ['id_quo', $id_quo],
            ['sku', $sku],
        ])->get();
        return count($checksn);
    }
}

if (!function_exists('getCustomerWarehouse')) {
    function getCustomerWarehouse($id, $kondisi)
    {
        $check = $kondisi == 'utama' ? CustomerModel::where('id', $id)->first()->company : Warehouse_address::where('id', $id)->first()->name;
        return $check;
    }
}

if (!function_exists('GetTotalAkhir')) {
    function GetTotalAkhir($id_quo)
    {
        $price          = QuotationOtherPrice::where('id_quo', $id_quo)->first();
        $product        = QuotationProduct::where('id_quo', $id_quo)->get();
        $order          = QuotationModel::where('id', $id_quo)->first();
        $subtotal_order = 0;
        foreach ($product as $val) {

            $subtotal_order  += $val->det_quo_harga_order * $val->det_quo_qty;
        }

        $vat    = $subtotal_order * (GetPPN(GetInvoiceDate($id_quo), $order->created_at) / 100);
        $ongkir = $price->ongkir_customer;

        $invoice_price = $subtotal_order + $vat + $ongkir;

        return $invoice_price;
    }
}

if (!function_exists('GetInvPartQty')) {
    function GetInvPartQty($idinv, $sku)
    {
        $product = InvoiceModelBarang::where([
            ['id_inv_partial', $idinv],
            ['sku', $sku],
        ])->first();

        return $product;
    }
}

if (!function_exists('GetWHdet')) {
    function GetWHdet($out)
    {
        $out = WarehouseOutDetail::where('id_outbound',$out);
        return $out;
    }
}
