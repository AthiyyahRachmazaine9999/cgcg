<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAssetModel extends Model
{
    use HasFactory;
    protected $table='employees_asset';
    protected $guarded = []; 
    protected $primaryKey="id";
}