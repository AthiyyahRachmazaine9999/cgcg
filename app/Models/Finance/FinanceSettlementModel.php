<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceSettlementModel extends Model
{
    use HasFactory;
    protected $table='finance_settlement';
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}

