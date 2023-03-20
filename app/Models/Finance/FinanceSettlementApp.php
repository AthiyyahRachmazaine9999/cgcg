<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceSettlementApp extends Model
{
    use HasFactory;
    protected $table='finance_settlement_app';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}