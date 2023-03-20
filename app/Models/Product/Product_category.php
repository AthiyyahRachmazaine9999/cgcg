<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_category extends Model
{
    use HasFactory;
    protected $table='product_category';
    //protected $table='product';
    protected $guarded=[];

    protected $primaryKey = 'id';

    public function parent(){

        return $this->belongsTo(self::class, 'parent_id');
    }

    public function childs(){

        return $this->hasMany(self::class, 'child_id');
    }

    public static function tree()
    {
      return static::where('parent_id', '=', NULL)->get(); 
    
    }

    private static function formatTree($categories, $allCategories)
    {
        foreach ($categories as $category) {
            $category->children = $allCategories->where('parent_id', $category->id)->values();

            if ($category->children->isNotEmpty()) {
                self::formatTree($category->children, $allCategories);
            }
        }
    }

    
}
