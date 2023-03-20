<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProSpekModel extends Model
{
    use HasFactory;
    protected $table='product_spesifikasi';
    public $timestamps=false;
    protected $fillable= [
        'pro_spek_id',
        'pro_spek_sku',
        'pro_spek_name',
        'pro_spek_value',


    ];
    protected $primaryKey ="pro_spek_id";
    
    

    public function ProSpekModel (){
       return $this->hasMany(ListContent::class);
    }
}
