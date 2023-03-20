<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseMigrateDetail extends Model
{
    protected $table = 'warehouse_details_old';
    protected $guarded = [];
}
