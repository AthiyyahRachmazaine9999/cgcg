<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_division extends Model
{
    use HasFactory;
    protected $table='role_divisions';
    // public $timestamps = true;

    protected $fillable = [
        'div_name',
        'created_by',
        'updated_by',
        'created_at',
     
    ];

    public function division()
    {

        return $this->hasMany(employee::class);
    }
}
