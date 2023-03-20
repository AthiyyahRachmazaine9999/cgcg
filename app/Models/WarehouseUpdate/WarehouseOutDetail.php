<?php

namespace App\Models\WarehouseUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOutDetail extends Model
{
    use HasFactory;
    protected $table = 'warehouse_outbound_detail';
    protected $guarded = [];
}