<?php

namespace App\Models\WarehouseUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOut extends Model
{
    use HasFactory;
    protected $table = 'warehouse_outbound';
    protected $guarded = [];

    public static function search($order, $dir, $limit, $start, $search)
    {
        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%' OR warehouse_outbound.id LIKE '%$search%')";
        return self::select('*','warehouse_outbound.id as idku')
            ->leftjoin('warehouse_outbound_detail', 'warehouse_outbound_detail.id_outbound', '=', 'warehouse_outbound.id')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw(
                " id_quo >= 0 $ssearch GROUP BY warehouse_outbound.id  ORDER BY $order $dir limit  $limit OFFSET $start "
            );
    }

    public static function countsearch($search)
    {

        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%' OR warehouse_outbound.id LIKE '%$search%')";
        return self::select('*','warehouse_outbound.id as idku')
            ->leftjoin('warehouse_outbound_detail', 'warehouse_outbound_detail.id_outbound', '=', 'warehouse_outbound.id')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw(
                " id_quo >= 0 $ssearch GROUP BY warehouse_outbound.id"
            );
    }

    // filter & export 
    public static function filtersearch($sdate, $edate, $icus)
    {
        $sicus    = $icus == 'kosong' ? ''  : "AND id_customer = '$icus'";
        $sdates   = $sdate == 'kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim >= '$sdate'";
        $edates   = $edate == 'kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim <= '$edate'";
        return self::select('*','warehouse_outbound.id as idku')
            ->leftjoin('warehouse_outbound_detail', 'warehouse_outbound_detail.id_outbound', '=', 'warehouse_outbound.id')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw("id_quo >= 0 $sicus $sdates $edates GROUP BY warehouse_outbound.id");
    }

    public static function filtersearchlimit($sdate, $edate, $icus, $start, $limit, $order, $dir)
    {
        $sicus    = $icus == 'kosong' ? ''  : "AND id_customer = '$icus'";
        $sdates   = $sdate == 'kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim >= '$sdate'";
        $edates   = $edate == 'kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim <= '$edate'";
        return self::select('*','warehouse_outbound.id as idku')
            ->leftjoin('warehouse_outbound_detail', 'warehouse_outbound_detail.id_outbound', '=', 'warehouse_outbound.id')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw(
                "id_quo >= 0 $sicus $sdates $edates GROUP BY warehouse_outbound.id ORDER BY $order $dir limit  $limit OFFSET $start"
            );
    }

    public static function filtersearchfind($sdate, $edate, $icus, $start, $limit, $order, $dir, $search)
    {
        $sicus    = $icus == 'kosong' ? ''  : "AND id_customer = '$icus'";
        $sdates   = $sdate == 'kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim >= '$sdate'";
        $edates   = $edate == 'kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim <= '$edate'";
        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%')";
        return self::select('*','warehouse_outbound.id as idku')
            ->leftjoin('warehouse_outbound_detail', 'warehouse_outbound_detail.id_outbound', '=', 'warehouse_outbound.id')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw(
                "id_quo >= 0 $sicus $sdates $edates $ssearch GROUP BY warehouse_outbound.id ORDER BY $order $dir limit  $limit OFFSET $start "
            );
    }

    public static function filterexport($start_date, $end_date, $id_cust, $type)
    {   
        // dd($start_date, $end_date, $id_cust);
        if($type == "normal")
        {
            $type_do = "utama";
        }else if($type == "partial")
        {
            $type_do = "split";
        }else{
            $type_do ="all";
        }
        
        $sdate  = $start_date=='kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim >= '$start_date'";
        $edate  = $end_date=='kosong' ? '' : "AND warehouse_outbound_detail.tgl_kirim <= '$end_date'";
        $idcust = $id_cust=='kosong' ? '': "AND id_customer = '$id_cust'";
        $types  = $type == 'kosong' || $type_do == "all" ? ''  : "AND type_do = '$type_do'";
        return  self::join('warehouse_outbound_detail','warehouse_outbound_detail.id_outbound','=','warehouse_outbound.id')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw("id_quo > 0 $sdate $edate $idcust $types"
        );
    }

}
