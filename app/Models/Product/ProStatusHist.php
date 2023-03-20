<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProStatusHist extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey='id';
    protected $table = 'product_status_hist';

}
