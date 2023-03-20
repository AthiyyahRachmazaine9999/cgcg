<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMigrationOtherPrice extends Model
{
    protected $table = 'quotation_other_prices_old';
    protected $guarded = [];
}
