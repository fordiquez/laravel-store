<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value',
        'description',
        'used_times',
        'starts_at',
        'expires_at',
        'greater_than',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'type' => \App\Enums\PromoCode::class,
        'used_times' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'greater_than' => 'float',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];
}
