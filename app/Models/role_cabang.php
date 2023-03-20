<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role_cabang extends Model
{
    use HasFactory;
    protected $table='role_cabangs';
    // public $timestamps = true;

    protected $fillable = [
        'cabang_name',
        'cabang_phone',
        'cabang_address',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
  
];
public function cabang()
    {

        return $this->hasMany(employee::class);
    }


}
