<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadAbsen extends Model
{
    use HasFactory;
    protected $table='upload_absen';
    protected $guarded=[];

    protected $primaryKey = 'id';

}
