<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveCatModel extends Model
{
    use HasFactory;
       protected $connection='mysql_com';

    protected $table='ocbz_category_description';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'category_id';
    

}
