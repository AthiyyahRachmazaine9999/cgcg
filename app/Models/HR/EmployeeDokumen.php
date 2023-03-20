<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDokumen extends Model
{
    use HasFactory;
    protected $table='employees_document';
    protected $guarded = []; 
    protected $primaryKey="id";
}