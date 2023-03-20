<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLive extends Model
{
    use HasFactory;
   protected $connection='mysql_com';
    protected $table='ocbz_product';
    public $timestamps=false;
    protected $guarded = [];
    protected $primaryKey = "product_id";

    public static function filtersearch($brand,$status,$sdate,$edate){
            // dd($brand, $status);
        $brand  = $brand=='kosong' ? '' : "AND manufacturer_id = '$brand'" ;
        $status = $status=='kosong' ? '': "AND status = '$status'" ;
        $sdate  = $sdate=='kosong' ? '' : "AND date_added >= '$sdate'";
        $edate  = $edate=='kosong' ? '' : "AND date_added <= '$edate'";
        return  self:: whereRaw("manufacturer_id > 0 $brand $status $sdate $edate"
        );

    }

    public static function filtersearchlimit($brand,$status,$sdate, $edate,$start,$limit,$order, $dir){
        // dd($sdate, $edate, $brand, $status);
        $brand  = $brand=='kosong' ? ''  : "AND manufacturer_id = '$brand'" ;
        $status = $status=='kosong' ? '': "AND status = '$status'" ;
        $sdate  = $sdate=='kosong' ? '' : "AND date_added >= '$sdate'";
        $edate  = $edate=='kosong' ? '' : "AND date_added <= '$edate'";
        return  self::join('ocbz_product_description as obd', 'obd.product_id','=', 'ocbz_product.product_id')
        ->whereRaw("manufacturer_id > 0 $brand $status $sdate $edate ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filtersearchfind($brand,$status,$sdate, $edate,$start,$limit,$order, $dir,$search){
        $brand  = $brand=='kosong' ? ''  : "AND manufacturer_id = '$brand'" ;
        $status = $status=='kosong' ? '': "AND status = '$status'" ;
        $sdate  = $sdate=='kosong' ? '' : "AND date_added >= '$sdate'";
        $edate  = $edate=='kosong' ? '' : "AND date_added <= '$edate'";
        $ssearch  = "AND (sku LIKE '%$search%' OR model LIKE '%$search%')" ;
        return  self::join('ocbz_product_description as obd', 'obd.product_id','=', 'ocbz_product.product_id')
        ->whereRaw("manufacturer_id > 0 $brand $status $sdate $edate $ssearch ORDER BY $order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filterexport($brand,$status,$sdate, $edate){
        $brand  = $brand=='kosong' ? ''  : "AND manufacturer_id = '$brand'" ;
        $status = $status=='kosong' ? '': "AND status = '$status'" ;
        $sdate  = $sdate=='kosong' ? '' : "AND date_added >= '$sdate'";
        $edate  = $edate=='kosong' ? '' : "AND date_added <= '$edate'";
        return  self::join('ocbz_product_description as p','p.product_id','=','ocbz_product.product_id')
        ->join('ocbz_product_attribute as at', 'at.product_id', '=', 'ocbz_product.product_id')
        ->whereRaw("manufacturer_id > 0 $brand $status $sdate $edate"
        );

    }
}