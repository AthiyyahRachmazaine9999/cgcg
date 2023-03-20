<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pay_VoucherDetail extends Model
{
    use HasFactory;
    protected $table='finance_pay_voucher_detail';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';

    public static function filtersearch($status,$customer, $vendor,$sdate,$edate){
        // dd($status, $customer, $vendor, $sdate, $edate);
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        // dd($statuses, $customss, $vendors, $sdates, $edates);
        return self::whereRaw("id > 0 $statuses $customss $vendors $sdates $edates"
        );
    }

    public static function filtersearchlimit($status,$customer,$vendor,$sdate,$edate,$start,$limit,$order, $dir){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        // dd($statuses, $customss, $vendors, $sdates, $edates);
        return self::whereRaw("id > 0 $statuses $customss $vendors $sdates $edates ORDER BY $order $dir limit  $limit OFFSET $start"
        );
    }

    public static function filtersearchfind($status,$customer,$vendor,$sdate,$edate,$start,$limit,$order,$dir,$search){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        $ssearch  = "AND (id_customer LIKE '%$search%' OR id_vendor LIKE '%$search%')" ;
        return  self::whereRaw("id > 0 $statuses $customss $vendors $sdates $edates $ssearch ORDER BY $order $dir limit  $limit OFFSET $start"
        );
    }




    public static function filtersearchOther($status,$customer, $vendor,$sdate,$edate){
        // dd($status, $customer, $vendor, $sdate, $edate);
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        // dd($statuses, $customss, $vendors, $sdates, $edates);
        return self::where('no_po', null)->whereRaw("id > 0 $statuses $customss $vendors $sdates $edates"
        );
    }

    public static function filtersearchlimitOther($status,$customer,$vendor,$sdate,$edate,$start,$limit,$order, $dir){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        // dd($statuses, $customss, $vendors, $sdates, $edates);
        return self::where('no_po', null)->whereRaw("id > 0 $statuses $customss $vendors $sdates $edates ORDER BY $order $dir limit  $limit OFFSET $start"
        );
    }

    public static function filtersearchfindOther($status,$customer,$vendor,$sdate,$edate,$start,$limit,$order,$dir,$search){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        $ssearch  = "AND (id_customer LIKE '%$search%' OR id_vendor LIKE '%$search%')" ;
        return self::where('no_po', null)->whereRaw("id > 0 $statuses $customss $vendors $sdates $edates $ssearch ORDER BY $order $dir limit  $limit OFFSET $start"
        );
    }

    public static function filterexport($status,$customer,$vendor,$sdate,$edate){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $customss = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $vendors  = $vendor=='kosong' ? '' : "AND id_vendor = '$vendor'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return self::select('*')->whereRaw("id > 0 $statuses $customss $vendors $sdates $edates"
        );
    }
    
}