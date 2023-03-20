<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxModel extends Model
{
    use HasFactory;
    protected $table='finance_tax';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}