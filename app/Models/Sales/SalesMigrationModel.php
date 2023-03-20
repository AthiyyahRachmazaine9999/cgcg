<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMigrationModel extends Model
{
    protected $table = 'quotation_models_old';
    protected $guarded = [];

    public static function filtersearch($type,$status,$sales,$sdate,$edate){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return self::whereRaw("quo_type > 0 $types $statuses $saleses $sdates $edates"
        );

    }

    public static function filtersearchlimit($type,$status,$sales,$sdate,$edate,$start,$limit,$order, $dir){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        return  self::whereRaw("quo_type > 0 $types $statuses $saleses $sdates $edates ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filtersearchfind($type,$status,$sales,$sdate,$edate,$start,$limit,$order, $dir,$search){
        $types    = $type=='kosong' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='kosong' ? '': "AND quo_eksstatus = '$status'" ;
        $saleses  = $sales=='kosong' ? '': "AND id_sales = '$sales'" ;
        $sdates   = $sdate=='kosong' ? '' : "AND created_at >= '$sdate'" ;
        $edates   = $edate=='kosong' ? '' : "AND created_at <= '$edate'" ;
        $ssearch  = "AND (quo_no LIKE '%$search%' OR quo_name LIKE '%$search%')" ;
        return  self::whereRaw("quo_type > 0 $types $statuses $saleses $sdates $edates $ssearch ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filterexport($type,$status,$sales,$sdate,$edate){
        $st = $status!='' ? status($status)->status_name : '';
        $types    = $type=='' ? ''  : "AND quo_type = '$type'" ;
        $statuses = $status=='' ? '': "AND quo_eksstatus = '$st'" ;
        $saleses  = $sales=='' ? '': "AND id_sales = '$sales'" ;
        $sdates   = $sdate=='' ? '' : "AND quotation_model_old.created_at >= '$sdate'" ;
        $edates   = $edate=='' ? '' : "AND quotation_model_old.created_at <= '$edate'" ;
        return  self::join('quotation_product_old as q', 'q.id_quo', '=', 'quotation_models.id')->whereRaw("quo_type > 0 $types $statuses $saleses $sdates $edates group by quotation_model_old.id"
        );
    }
}
