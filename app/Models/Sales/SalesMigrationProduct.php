<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMigrationProduct extends Model
{
    protected $table = 'quotation_product_old';
    protected $guarded = [];

    public static function sumongkir($id){
        return  self::where('id_quo',$id)->sum('det_quo_harga_ongkir');
    }
}
