<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseMigrateDetail extends Model
{
    protected $table = 'purchase_detail_old';
    protected $guarded = [];
}
