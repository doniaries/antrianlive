<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningTeks extends Model
{
    protected $fillable = [
        'id',
        'text',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
