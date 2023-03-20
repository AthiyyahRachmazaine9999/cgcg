<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReqLeaveSpecial extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table= "req_leave_special";
}