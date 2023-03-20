<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveProAtribut extends Model
{
    use HasFactory;
    protected $connection = 'mysql_com';
    protected $table='ocbz_product_attribute';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'product_id';
}
