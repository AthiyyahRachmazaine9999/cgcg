<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationInvoiceDetail extends Model
{
    use HasFactory;
    protected $table = 'quotation_invoice_detail';
    protected $guarded = [];
}