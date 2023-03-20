<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveManModel extends Model
{
    use HasFactory;
       protected $connection='mysql_com';
    protected $table='ocbz_manufacturer';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'manufacturer_id';
    

}
