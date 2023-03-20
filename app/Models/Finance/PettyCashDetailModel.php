<?php

namespace App\Models\Finance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashDetailModel extends Model
{
    use HasFactory;
    protected $table='';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'id';
}