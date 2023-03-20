<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseMigrateOrder extends Model
{
    protected $table = 'warehouse_orders_old';
    protected $guarded = [];
}
