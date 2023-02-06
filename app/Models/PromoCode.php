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

    public static array $types = ['fixed', 'percentage'];
}
