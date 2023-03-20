<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListContent extends Model
{
    use HasFactory;
    protected $table='product';
    protected $guarded=[];

    protected $primaryKey = 'pro_id';


    public function ListContent()
    {

        return $this->belongsTo(ListContent::class);
    }

    public function brand()
    {
        return $this->belongsTo(Product_brand::class,'id');
    }
    public function category()
    {
        return $this->belongsTo(Product_category::class,'id');
    }

}
