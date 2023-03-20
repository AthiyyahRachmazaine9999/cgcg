<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pay_VoucherApp extends Model
{
    use HasFactory;
    protected $table='finance_pay_voucher_app';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}