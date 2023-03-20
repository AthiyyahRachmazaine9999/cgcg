<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceSettlementDetail extends Model
{
    use HasFactory;
    protected $table='finance_settlement_detail';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}