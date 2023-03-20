<?php

namespace App\Models\WarehouseUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOutHistory extends Model
{
    use HasFactory;
    protected $table = 'warehouse_outbound_history';
    protected $guarded = [];
}