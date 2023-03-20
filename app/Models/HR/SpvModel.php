<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SpvModel extends Model
{
    protected $fillable = [
        'spv_name',
        'emp_nip',
        'created_by',
        'updated_by',
        'created_at',
     
    ];

    public function spv()
    {

        return $this->hasMany(employee::class);
    }

}
