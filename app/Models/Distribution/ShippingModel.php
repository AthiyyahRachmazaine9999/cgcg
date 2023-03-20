<?php

namespace App\Models\Distribution;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingModel extends Model
{
    use HasFactory;
    protected $table='shipping_company';
    protected $guarded=[];

    protected $primaryKey = 'id';
}
