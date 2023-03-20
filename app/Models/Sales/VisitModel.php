<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitModel extends Model
{
    use HasFactory;
    protected $table   = 'visit_plan';
    protected $guarded = [];

    public static function filtersearch($status, $customer, $sales, $sdate, $edate){
        $statuses  = $status=='kosong' ? '': "AND status = '$status'" ;
        $saleses   = $sales=='kosong' ? '': "AND created_by = '$sales'" ;
        $sicus     = $customer=='kosong' ? ''  : "AND id_customer = '$customer'" ;
        $sdates    = $sdate=='kosong' ? '' : "AND date >= '$sdate'" ;
        $edates    = $edate=='kosong' ? '' : "AND date <= '$edate'" ;
        return self::whereRaw("id > 0 $statuses $saleses $sicus $sdates $edates"
        );
    }

    public static function filtersearchlimit($status, $customer, $sales, $sdate, $edate, $start, $limit, $order, $dir){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND created_by = '$sales'" ;
        $sicus     = $customer=='kosong' ? '' : "AND id_customer = '$customer'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND date >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND date <= '$edate'" ;
        return  self::whereRaw("id > 0 $statuses $saleses $sicus $sdates $edates ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filtersearchfind($status, $customer, $sales, $sdate, $edate,$start,$limit,$order, $dir,$search){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND created_by = '$sales'" ;
        $sicus    = $customer=='kosong' ? ''  : "AND id_customer = '$customer'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND date >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND date <= '$edate'" ;
        $ssearch  = "AND (aktivitas LIKE '%$search%' OR forecast_value LIKE '%$search%')" ;
        return  self::whereRaw("id > 0 $statuses $saleses $sicus $sdates $edates $ssearch ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filterexport($status, $customer, $sales, $sdate, $edate){
        $statuses = $status=='kosong' ? '': "AND status = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND created_by = '$sales'" ;
        $sicus    = $customer=='kosong' ? ''  : "AND id_customer = '$customer'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND date >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND date <= '$edate'" ;
        return self::whereRaw("id > 0 $statuses $saleses $sicus $sdates $edates"
        );
    }

}