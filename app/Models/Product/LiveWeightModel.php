<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveWeightModel extends Model
{
    use HasFactory;
    protected $connection='mysql_com';

    protected $table='ocbz_weight_class_description';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'weight_class_id';

}
