<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse_history extends Model
{
    use HasFactory;
    protected $table = 'warehouse_history';
    protected $guarded = [];
}