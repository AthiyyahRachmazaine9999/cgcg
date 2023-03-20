<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Req_LeaveModel extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table= "req_leave";
    protected $primaryKey="id";
}
