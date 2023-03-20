<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Req_OvertimeApp extends Model
{
    use HasFactory;
    protected $table='req_overtime_app';
    protected $guarded=[];
    protected $primaryKey='id';
}