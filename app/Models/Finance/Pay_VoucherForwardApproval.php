<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pay_VoucherForwardApproval extends Model
{
    use HasFactory;
    protected $table='finance_pay_voucher_forward_approval';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}