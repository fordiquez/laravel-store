<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodTag extends Model
{
    protected $table = 'good_tag';

    protected $fillable = ['good_id', 'tag_id'];
}
