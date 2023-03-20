<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Purchase_order extends Model
{
    protected $table = 'purchase_orders';
    protected $guarded = [];

    public static function filterbarang($st, $vendor, $product, $sdate, $edate){
        $statuss  = $st=='kosong' ? ''      : "AND purchase_orders.status = '$st'" ;
        $vendorr  = $vendor=='kosong' ? ''  : "AND purchase_orders.id_vendor = '$vendor'" ;
        $sdatess  = $sdate=='kosong' ? ''  : "AND purchase_orders.created_at >= '$sdate'" ;
        $edatess  = $edate=='kosong' ? '': "AND purchase_orders.created_at <= '$edate'" ;
        $namaproo = $product=='kosong' ? '' : "AND pd.sku = '$product'";
        return self::join('purchase_detail as pd', 'pd.id_po','=', 'purchase_orders.id')
        ->whereRaw("purchase_orders.id > 0 $statuss $vendorr  $namaproo $sdatess $edatess")
        ->select('*','purchase_orders.id as idku')->groupBy('purchase_orders.id');

    }


    public static function filtersearch($st, $vendor, $product, $s_date, $end_date){
        $statuss  = $st=='kosong' ? ''      : "AND purchase_orders.status = '$st'" ;
        $vendorr  = $vendor=='kosong' ? ''  : "AND purchase_orders.id_vendor = '$vendor'" ;
        $sdatess  = $s_date=='kosong' ? ''  : "AND purchase_orders.created_at >= '$s_date'" ;
        $edatess  = $end_date=='kosong' ? '': "AND purchase_orders.created_at <= '$end_date'" ;
        $namaproo = $product=='kosong' ? '' : "AND pd.sku = '$product'";
        return self:: join('purchase_detail as pd', 'pd.id_po','=', 'purchase_orders.id')
        ->whereRaw("purchase_orders.id > 0 $statuss $vendorr  $namaproo $sdatess $edatess")
        ->select('*','purchase_orders.id as idku')->groupBy('purchase_orders.id');
    }
    public static function filtersearchlimit($st, $vendor, $product, $s_date, $end_date, $start, $limit, $order, $dir){
        $statuss  = $st=='kosong' ? ''      : "AND purchase_orders.status = '$st'" ;
        $vendorr  = $vendor=='kosong' ? ''  : "AND purchase_orders.id_vendor = '$vendor'" ;
        $sdatess  = $s_date=='kosong' ? ''  : "AND purchase_orders.created_at >= '$s_date'" ;
        $edatess  = $end_date=='kosong' ? '': "AND purchase_orders.created_at <= '$end_date'" ;
        $namaproo = $product=='kosong' ? '' : "AND pd.sku = '$product'";
        $sorder   = $order<>'id' ? $order : "purchase_orders.id" ;
        return self:: join('purchase_detail as pd', 'pd.id_po','=', 'purchase_orders.id')
        ->whereRaw("purchase_orders.id > 0 $statuss $vendorr $namaproo $sdatess $edatess GROUP BY purchase_orders.id ORDER BY $sorder $dir limit  $limit OFFSET $start")
        ->select('*','purchase_orders.id as idku');
    }

    public static function filtersearchfind($st, $vendor, $product, $s_date, $end_date, $start, $limit, $order, $dir,$search){
        $statuss  = $st=='kosong' ? ''      : "AND purchase_orders.status = '$st'" ;
        $vendorr  = $vendor=='kosong' ? ''  : "AND purchase_orders.id_vendor = '$vendor'" ;
        $sdatess  = $s_date=='kosong' ? ''  : "AND purchase_orders.created_at >= '$s_date'" ;
        $edatess  = $end_date=='kosong' ? '': "AND purchase_orders.created_at <= '$end_date'" ;
        $namaproo = $product=='kosong' ? '' : "AND pd.sku = '$product'";
        $sorder   = $order<>'id' ? $order : "purchase_orders.id" ;
        $ssearch  = "AND (po_number LIKE '%$search%' OR id_quo LIKE '%$search%')" ;
        return self:: join('purchase_detail as pd', 'pd.id_po','=', 'purchase_orders.id')
        ->whereRaw("purchase_orders.id > 0 $statuss $vendorr $namaproo $sdatess $edatess GROUP BY purchase_orders.id ORDER BY $sorder $dir limit  $limit OFFSET $start")
        ->select('*','purchase_orders.id as idku');
    }

        public static function filterexport($st,$vendor,$start,$end_date){
        $statuss   = $st=='kosong' ? ''  :"AND purchase_orders.status = '$st'" ;
        $vendorr   = $vendor=='kosong' ? '': "AND purchase_orders.id_vendor = '$vendor'" ;
        $sdatess   = $start=='kosong' ? '' : "AND purchase_orders.created_at >= '$start'" ;
        $edatess   = $end_date=='kosong' ? '' : "AND purchase_orders.created_at <= '$end_date'" ;

        return self::select('*', DB::raw('SUM(pd.qty*pd.price) as total'))
        ->join('purchase_detail as pd', 'pd.id_po', '=', 'purchase_orders.id')
        ->whereRaw("purchase_orders.id > 0 $statuss $vendorr $sdatess $edatess");
    }

    public static function filterexportproduct($st,$vendor,$namapro,$s_date,$end_date){
        // dd($st, $vendor, $id_product, $start, $end_date);
        $statuss   = $st=='kosong' ? ''  : "AND purchase_orders.status = '$st'" ;
        $vendorr   = $vendor=='kosong' ? '': "AND purchase_orders.id_vendor = '$vendor'" ;
        $sdatess   = $s_date=='kosong' ? '' : "AND purchase_orders.created_at >= '$s_date'" ;
        $edatess   = $end_date=='kosong' ? '' : "AND purchase_orders.created_at <= '$end_date'" ;
        $namaproo  = $namapro=='kosong' ? '' : "AND op.sku LIKE '%$namapro%'"; 
        // dd($namaproo);
        return self::join('purchase_detail as pd', 'pd.id_po','=', 'purchase_orders.id')
        ->whereRaw("purchase_orders.id > 0 $statuss $vendorr  $namaproo $sdatess $edatess")
        ->select('*','purchase_orders.id as idku', DB::raw('(pd.qty*pd.price) as total'));
    }

}