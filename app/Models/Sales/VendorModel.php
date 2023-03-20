<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class VendorModel extends Model
{
    protected $table = 'vendor_company';
    protected $guarded = [];


    public static function filterexport($vendor,$start,$end_date){
        $vendorr   = $vendor=='kosong' ? '': "AND vendor_company.id = '$vendor'" ;
        $sdatess   = $start=='kosong' ? '' : "AND pd.created_at >= '$start'" ;
        $edatess   = $end_date=='kosong' ? '' : "AND pd.created_at <= '$end_date'" ;

        return self::select('*',DB::raw('SUM(po.qty*po.price) as total'))
        ->join('purchase_orders as pd', 'pd.id_vendor', '=', 'vendor_company.id')
        ->join('purchase_detail as po', 'po.id_po', '=', 'pd.id')
        ->whereRaw("vendor_company.id > 0 $vendorr $sdatess $edatess");
    }
}