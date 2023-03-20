<?php

namespace App\Models\Receptionist;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    use HasFactory;
    protected $table = 'meeting_room_booking';
    protected $guarded = [];
}