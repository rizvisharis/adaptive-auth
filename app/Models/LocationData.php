<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'country_name',
        'country_code',
        'region',
        'city',
    ];
}
