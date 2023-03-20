<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    use HasFactory;
    protected $table='finance_cash_adv';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
    
}