<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveProToStore extends Model
{
    use HasFactory;
    protected $connection = 'mysql_com';
    protected $table='ocbz_product_to_store';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'product_id';
}
