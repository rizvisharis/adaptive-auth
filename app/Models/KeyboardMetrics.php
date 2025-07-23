<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyboardMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_typing_time',
        'password_typing_time',
        'shift_count',
        'caps_lock_count',
        'average_dwell_time',
        'average_flight_duration',
        'average_up_down_time',
    ];
}
