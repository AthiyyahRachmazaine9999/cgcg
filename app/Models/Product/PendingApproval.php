<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingApproval extends Model
{
    use HasFactory;
    protected $table='product_approval';
    //protected $table='product';
    protected $guarded=[];

    protected $primaryKey = 'pro_id';

    public function status()
    {
        return $this->belongsTo(ListContent::class,'pro_status');
    }

}
