<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProImageModel extends Model
{
    use HasFactory;
    protected $table = 'product_image';
    protected $guarded=[];
    protected $primaryKey ="img_id";
        public $timestamps=false;


    public function ProImageModel(){
        return $this->hasMany(ListContent::class);
    }

}
