<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sales\QuotationModel;

class QuotationInvoice extends Model
{
    protected $table = 'quotation_invoice';
    protected $guarded = [];

    public static function filtersearch($id_quo, $st_date, $end_date, $k_lunas){
        $id_quo = $id_quo== 'kosong' ? '' : "AND quotation_invoice.id_quo = '$id_quo'" ;
        if($k_lunas == "Finish"){
            $lunas = $k_lunas== 'kosong' ? '': "AND ket_lunas = '$k_lunas'" ;
        }else if($k_lunas == "null"){
            $lunas = $k_lunas== 'kosong' ? '': "AND isnull(ket_lunas)" ;
        }
        $sdates = $st_date=='kosong' ? '' : "AND tgl_invoice >= '$st_date'" ;
        $edates = $end_date=='kosong' ? '': "AND tgl_invoice <= '$end_date'" ;
        return  QuotationModel:: join('quotation_invoice', 'quotation_invoice.id_quo','=','quotation_models.id')
        ->whereRaw("id_quo > 0 $id_quo $sdates $edates $lunas"
        );

    }

    public static function filtersearchlimit($id_quo, $st_date, $end_date, $k_lunas,$start,$limit,$order, $dir){
        $id_quo = $id_quo== 'kosong' ? '' : "AND quotation_invoice.id_quo = '$id_quo'" ;
        if($k_lunas == "Finish"){
            $lunas = $k_lunas== 'kosong' ? '': "AND ket_lunas = '$k_lunas'" ;
        }else if($k_lunas == "null"){
            $lunas = $k_lunas== 'kosong' ? '': "AND isnull(ket_lunas)" ;
        }
        $sdates = $st_date=='kosong' ? '' : "AND tgl_invoice >= '$st_date'" ;
        $edates = $end_date=='kosong' ? '': "AND tgl_invoice <= 'end_date'" ;
        return  QuotationModel:: join('quotation_invoice', 'quotation_invoice.id_quo','=','quotation_models.id')
        ->whereRaw("id_quo > 0 $id_quo $sdates $edates $lunas ORDER BY quotation_invoice.$order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filtersearchfind($id_quo, $st_date, $end_date, $k_lunas, $start,$limit,$order, $dir,$search){
        $id_quo = $id_quo== 'kosong' ? '' : "AND quotation_invoice.id_quo = '$id_quo'" ;
        if($k_lunas == "Finish"){
            $lunas = $k_lunas== 'kosong' ? '': "AND ket_lunas = '$k_lunas'" ;
        }else if($k_lunas == "null"){
            $lunas = $k_lunas== 'kosong' ? '': "AND isnull(ket_lunas)" ;
        }
        $sdates  = $st_date== 'kosong' ? ''  : "AND tgl_invoice >= '$st_date'" ;
        $edates  = $end_date== 'kosong' ? ''  : "AND tgl_invoice <= '$end_date'" ;
        $ssearch = "AND (id_quo LIKE '%$search%')" ;
        return QuotationModel:: join('quotation_invoice', 'quotation_invoice.id_quo','=','quotation_models.id')->
        whereRaw("id_quo > 0 $id_quo $sdates $edates $lunas $ssearch ORDER BY quotation_invoice.$order $dir limit  $limit OFFSET $start "
        );
    }

    public static function filterexport($id_quo, $st_date, $end_date, $k_lunas){
        $id_quo = $id_quo=='kosong' ? '' : "AND quotation_invoice.id_quo = '$id_quo'" ;
        if($k_lunas == "Finish"){
            $lunas  = $k_lunas=='kosong' ? '': "AND ket_lunas = '$k_lunas'" ;
        }else if($k_lunas == "null"){
            $lunas  = $k_lunas=='kosong' ? '': "AND isnull(ket_lunas)" ;
        }
        $sdates = $st_date=='kosong' ? ''  : "AND tgl_invoice >= '$st_date'" ;
        $edates = $end_date=='kosong' ? ''  : "AND tgl_invoice <= '$end_date'" ;
        return  QuotationModel::join('quotation_invoice', 'quotation_invoice.id_quo','=','quotation_models.id')
        ->whereRaw("quo_type > 0 $id_quo $sdates $edates $lunas")
        ->groupBy('id_quo');

    }
}