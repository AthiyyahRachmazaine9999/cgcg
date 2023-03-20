<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashCode extends Model
{
    use HasFactory;
    protected $table='finance_code_accounting';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}