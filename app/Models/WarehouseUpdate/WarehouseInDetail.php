<?php

namespace App\Models\WarehouseUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseInDetail extends Model
{
    use HasFactory;
    protected $table = 'warehouse_inbound_detail';
    protected $guarded = [];
}