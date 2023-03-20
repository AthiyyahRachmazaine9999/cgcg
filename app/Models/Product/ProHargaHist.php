<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProHargaHist extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey='id';
    protected $table = 'product_harga_hist';

}
