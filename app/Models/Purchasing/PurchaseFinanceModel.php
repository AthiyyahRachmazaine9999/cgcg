<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseFinanceModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_finance_detail';
    protected $guarded = [];
}