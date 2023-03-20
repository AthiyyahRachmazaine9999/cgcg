<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitHistory extends Model
{
    use HasFactory;
    protected $table = 'visit_plan_history';
    protected $guarded = [];
}