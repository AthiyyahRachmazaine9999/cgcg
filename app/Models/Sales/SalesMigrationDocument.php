<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMigrationDocument extends Model
{
    protected $table = 'quotation_document_old';
    protected $guarded = [];
}
