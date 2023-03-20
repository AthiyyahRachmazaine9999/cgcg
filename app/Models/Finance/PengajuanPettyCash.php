<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPettyCash extends Model
{
    use HasFactory;
    protected $table='finance_pengajuan_pettycash';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}