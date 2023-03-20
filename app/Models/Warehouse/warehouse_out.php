<?php

namespace App\Models\warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class warehouse_out extends Model
{
    protected $table   = 'warehouse_out';
    protected $guarded = [];

    public static function search($order, $dir, $limit, $start, $search)
    {
        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%')";
        return self::select('warehouse_out.id as id','id_quo', 'id_cust')
            ->join('customer_company', 'warehouse_out.id_cust', '=', 'customer_company.id')
            ->whereRaw(
                " id_quo >= 0 $ssearch ORDER BY $order $dir limit  $limit OFFSET $start"
            );
    }

    public static function countsearch($search)
    {
        
        $ssearch = "AND (id_quo LIKE '%$search%' OR customer_company.company LIKE '%$search%')";
        return self::select('warehouse_out.id as id','id_quo', 'id_cust')
            ->join('customer_company', 'warehouse_out.id_cust', '=', 'customer_company.id')
            ->whereRaw(
                " id_quo >= 0 $ssearch "
            );
    }
}
