<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodUser extends Model
{
    protected $fillable = ['good_id', 'user_id'];

    protected $table = 'good_user';
}
