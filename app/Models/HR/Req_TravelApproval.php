<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Req_TravelApproval extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="req_travel_app";
    protected $primaryKey="id";
}