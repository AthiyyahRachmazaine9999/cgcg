<?php

namespace App\Models\WarehouseUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseIn extends Model
{
    use HasFactory;
    protected $table = 'warehouse_inbound';
    protected $guarded = [];
}