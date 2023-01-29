<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_title',
        'first_name',
        'last_name',
        'mobile_phone',
        'is_active'
    ];
}
