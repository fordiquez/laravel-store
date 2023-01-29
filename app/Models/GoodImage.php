<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'good_id',
        'src',
        'is_preview'
    ];
}
