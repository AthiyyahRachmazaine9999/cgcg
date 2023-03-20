<?php

namespace App\Models\WarehouseUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseSN extends Model
{
    use HasFactory;
    protected $table   = 'warehouse_serial_number';
    protected $guarded = [];

    public static function search($order, $dir, $limit, $start, $search)
    {
        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%')";
        return self::select('warehouse_outbound.id as id','id_quo', 'id_customer')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw(
                " id_quo >= 0 $ssearch ORDER BY $order $dir limit  $limit OFFSET $start"
            );
    }

    public static function countsearch($search)
    {
        
        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%')";
        return self::select('warehouse_outbound.id as id','id_quo', 'id_customer')
            ->join('quotation_models', 'quotation_models.id', '=', 'warehouse_outbound.id_quo')
            ->join('customer_company', 'quotation_models.id_customer', '=', 'customer_company.id')
            ->whereRaw(
                " id_quo >= 0 $ssearch "
            );
    }
}
