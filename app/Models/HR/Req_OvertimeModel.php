<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Req_OvertimeModel extends Model
{
    use HasFactory;
    protected $table='req_overtime';
    //protected $table='product';
    protected $guarded=[];
    protected $primaryKey='id';
    
    public function getTimeAttribute($value)
{
    $time = Carbon::createFromFormat('H:i:s', $value);

    return $time->format('H:i');
}
}
