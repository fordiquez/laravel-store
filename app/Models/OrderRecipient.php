<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRecipient extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'first_name', 'last_name', 'second_name', 'phone', 'description', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
