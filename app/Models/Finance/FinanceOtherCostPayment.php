<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceOtherCostPayment extends Model
{
    use HasFactory;
    protected $table='finance_othercost';
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}