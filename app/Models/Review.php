<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'good_id',
        'content',
        'advantages',
        'disadvantages',
        'rating',
        'video_src',
        'ip_address',
    ];
}
