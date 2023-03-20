<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPriceModel extends Model
{
    use HasFactory;
    protected $table='product_price';
    protected $guarded = [];
    public $timestamps=false;
    protected $primaryKey ="price_id";

    public function ProPriceModel(){
        return $this->hasMany(ListContent::class);
    }
    
}
