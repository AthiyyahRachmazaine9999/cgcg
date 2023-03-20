<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseMigrate extends Model
{
    protected $table = 'purchase_orders_old';
    protected $guarded = [];
}
