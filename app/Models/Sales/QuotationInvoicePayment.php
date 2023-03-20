<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationInvoicePayment extends Model
{
    use HasFactory;
    protected $table = 'quotation_invoice_payment';
    protected $guarded = [];
}