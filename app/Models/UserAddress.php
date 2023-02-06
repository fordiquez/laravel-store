<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_main',
        'country',
        'city',
        'street',
        'house',
        'flat',
        'postal_code',
    ];
}
