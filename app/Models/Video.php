<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'url',
        'type',
        'is_active',
    ];

    protected $casts = [
        'type' => 'string',
        'is_active' => 'boolean',
    ];
}
