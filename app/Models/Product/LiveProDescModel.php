<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveProDescModel extends Model
{
    use HasFactory;
    protected $connection='mysql_com';
    protected $table='ocbz_product_description';
    public $timestamps=false;

      
    protected $guarded = [];
    protected $primaryKey = 'product_id';
    

       public function desc(){

        return $this->hashMany(Live::class);
    }

}
