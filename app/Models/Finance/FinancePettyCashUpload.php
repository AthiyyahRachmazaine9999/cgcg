<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancePettyCashUpload extends Model
{
    use HasFactory;
    protected $table='finance_pettycash_dokumen';
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}