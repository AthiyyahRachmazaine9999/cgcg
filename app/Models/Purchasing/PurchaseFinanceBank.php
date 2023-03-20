<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseFinanceBank extends Model
{
    use HasFactory;
    protected $table = 'purchase_finance_bank';
    protected $guarded = [];
}