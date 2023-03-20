<?php

namespace App\Models\Distribution;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping_pic extends Model
{
    use HasFactory;
    protected $table='shipping_pic';
    protected $guarded=[];

    protected $primaryKey = 'id';
}
