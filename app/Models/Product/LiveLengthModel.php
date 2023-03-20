<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveLengthModel extends Model
{
    use HasFactory;
       protected $connection='mysql_com';
    protected $table='ocbz_length_class_description';
    public $timestamps=false;
      
    protected $guarded = [];
    protected $primaryKey = 'length_class_id';
    }
