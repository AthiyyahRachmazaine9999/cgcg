<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLive2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql_com';
    protected $table = 'ocbz_product';
    protected $guarded = [];


}
