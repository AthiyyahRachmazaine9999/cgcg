<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class QuotationModel extends Model
{
    protected $table = 'quotation_models';
    protected $guarded = [];

    public static function filtersearch($type,$status,$sales,$sdate,$edate,$icus){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sicus    = $icus=='kosong' ? ''  : "AND id_customer = '$icus'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return self::whereRaw("quo_type > 0 $types $statuses $saleses $sicus $sdates $edates"
        );

    }

    public static function filtersearchlimit($type, $status, $sales, $sdate, $edate, $icus, $start, $limit, $order, $dir){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sicus    = $icus=='kosong' ? ''  : "AND id_customer = '$icus'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return  self::whereRaw("quo_type > 0 $types $statuses $saleses $sicus $sdates $edates ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filtersearchfind($type,$status,$sales,$sdate,$edate,$icus,$start,$limit,$order, $dir,$search){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sicus    = $icus=='kosong' ? ''  : "AND id_customer = '$icus'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        $ssearch  = "AND (quo_no LIKE '%$search%' OR quo_name LIKE '%$search%')" ;
        return  self::whereRaw("quo_type > 0 $types $statuses $saleses $sicus $sdates $edates $ssearch ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filterexport($type,$status,$sales,$sdate,$edate){
        $st = $status!='' ? status($status)->status_name : '';
        $types    = $type=='' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='' ? '': "AND quo_eksstatus = '$st'" ;
        $saleses  = $sales=='' ? '': "AND id_sales = '$sales'" ;
        $sdates   = $sdate=='' ? '' : "AND quotation_models.created_at >= '$sdate'" ;
        $edates   = $edate=='' ? '' : "AND quotation_models.created_at <= '$edate'" ;
        return  self::select('*','quotation_models.created_at AS tanggalbuat', DB::raw('sum(det_quo_harga_order*det_quo_qty) as total'))->join('quotation_product as q', 'q.id_quo', '=', 'quotation_models.id')
        ->whereRaw("quo_type > 0 $types $statuses $saleses $sdates $edates"
        )->groupBy('id_quo');

    }

    // ======= search product ======= //
    public static function filtersearchproduct($type,$status,$sales,$sdate,$edate,$icus,$sku){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '' : "AND id_sales = '$sales'" ;
        $sicus    = $icus=='kosong' ? ''  : "AND id_customer = '$icus'" ;
        $ssku     = $sku=='kosong' ? ''   : "AND q.id_product = '$sku'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return    self::whereRaw("quo_type > 0 $types $statuses $saleses $sicus $ssku $sdates $edates GROUP BY id_quo")
        ->join('quotation_product as q', 'q.id_quo', '=', 'quotation_models.id');

    }

    public static function filtersearchlimitproduct($type,$status,$sales,$sdate,$edate,$icus,$sku,$start,$limit,$order, $dir){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '' : "AND id_sales = '$sales'" ;
        $sicus    = $icus=='kosong' ? ''  : "AND id_customer = '$icus'" ;
        $ssku     = $sku=='kosong' ? ''   : "AND q.id_product = '$sku'" ;
        $sorder   = $order<>'id' ? $order : "quotation_models.id" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return    self:: whereRaw("quo_type > 0 $types $statuses $saleses $sicus $ssku $sdates $edates GROUP BY id_quo ORDER BY $sorder $dir limit  $limit OFFSET $start ")
        ->join('quotation_product as q', 'q.id_quo', '=', 'quotation_models.id');
    }

    public static function filtersearchfindproduct($type,$status,$sales,$sdate,$edate,$icus,$sku,$start,$limit,$order, $dir,$search){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sicus    = $icus=='kosong' ? ''  : "AND id_customer = '$icus'" ;
        $ssku     = $sku=='kosong' ? ''   : "AND q.id_product = '$sku'" ;
        $sorder   = $order<>'id' ? $order : "quotation_models.id" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        $ssearch  = "AND (quo_no LIKE '%$search%' OR quo_name LIKE '%$search%')" ;
        return  self::whereRaw("quo_type > 0 $types $statuses $saleses $ssku $sdates $edates $sicus $ssearch GROUP BY id_quo ORDER BY $sorder $dir limit  $limit OFFSET $start ")
        ->join('quotation_product as q', 'q.id_quo', '=', 'quotation_models.id');
    }
}
