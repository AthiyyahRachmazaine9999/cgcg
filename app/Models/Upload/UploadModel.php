<?php

namespace App\Models\Upload;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadModel extends Model
{
    use HasFactory;
    protected $table = 'act_upload_legalitas';
    protected $guarded = [];

}