<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Req_MassLeave extends Model
{
    use HasFactory;
    protected $table='req_massleave';
    protected $guarded=[];
}