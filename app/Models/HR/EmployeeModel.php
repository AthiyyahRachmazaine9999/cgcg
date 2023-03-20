<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmployeeModel extends Model
{
    use HasFactory;
    protected $table='employees';
   

    protected $fillable = [
        'emp_name',
        'emp_email',
        'emp_phone',
        'emp_address',
        'emp_birthplace',
        'emp_birthdate',
        'emp_nip',
        'position',
        'spv_id',
        'division_id',
        'cabang_id',
        'created_by',
        'update_by',
        'cabang_id',
        
  
];

protected $casts = [
'emp_birthdate' => 'datetime:d-m-Y',

];
    public function cabang()
    {
        return $this->belongsTo(role_cabang::class,'cabang_id');
    }
    public function division()
    {
        return $this->belongsTo(role_division::class,'division_id');
    }
     
    public function spv()
    {
        return $this->belongsTo(SpvModel::class,'spv_id');
    }

    public function getCreateDate(){
        return Carbon::parse($this->attribute['created_at'])
        ->translatedFormat('l, d F Y');
    }
    public function getDateBirth(){
        return Carbon::parse($this->attribute['emp_birthdate'])
        ->format('d/m/Y');
    }
    

}
