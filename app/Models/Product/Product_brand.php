<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_brand extends Model
{
    use HasFactory;
    protected $table='product_brand';
   // public $timestamps = true;

    protected $fillable = [
        'brand_name',
        'created_by',
        'update_by',
        'created_at',
     
    ];

  
}
