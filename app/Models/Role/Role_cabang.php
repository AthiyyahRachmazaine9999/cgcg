<?php

namespace App\Models\Role;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_cabang extends Model
{
    use HasFactory;
    protected $table='role_cabangs';
    // public $timestamps = true;

    protected $guarded = [];
public function cabang()
    {

        return $this->hasMany(employee::class);
    }


}
