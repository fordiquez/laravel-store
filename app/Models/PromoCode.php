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
        'start_date',
        'expire_date',
        'greater_than',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'type' => \App\Enums\PromoCode::class,
        'used_times' => 'integer',
        'start_date' => 'datetime',
        'expire_date' => 'datetime',
        'greater_than' => 'float',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];
}
