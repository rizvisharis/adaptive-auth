<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContextData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'browser_name',
        'browser_version',
        'user_agent',
        'color_depth',
        'canvas_fingerprint',
        'os',
        'cpu_class',
        'resolution',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
