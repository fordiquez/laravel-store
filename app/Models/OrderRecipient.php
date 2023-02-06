<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'first_name',
        'last_name',
        'second_name',
        'phone',
        'is_default'
    ];
}
