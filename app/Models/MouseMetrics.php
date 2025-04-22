<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouseMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mouse_speed',
        'max_speed',
        'max_positive_acc',
        'max_negative_acc',
        'total_x_distance',
        'total_y_distance',
        'total_distance',
        'left_click_count',
        'right_click_count',
    ];
}
