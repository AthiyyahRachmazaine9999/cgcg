<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPettycashDetail extends Model
{
    use HasFactory;
    protected $table='finance_pengajuan_detail_pettycash';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}