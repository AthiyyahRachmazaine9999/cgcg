<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceHistory extends Model
{
    use HasFactory;
    protected $table='finance_history';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}