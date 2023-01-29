<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodStatus extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public static array $statuses = [
        'ready for dispatch',
        'in stock',
        'ends',
        'is over',
        'out of stock',
        'discontinued'
    ];
}
