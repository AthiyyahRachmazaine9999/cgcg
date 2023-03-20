<?php

namespace App\Models\UI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    protected $table = 'ui_menu';
    protected $fillable = ['parent_id', 'title', 'sequence_to', 'position', 'link', 'icon_id', 'is_actions', 'actions', 'description', 'created_by'];
}
