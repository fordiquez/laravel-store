<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
      'section',
      'name',
      'key',
      'value',
      'type',
      'rules'
    ];

    public static array $types = ['text', 'number', 'checkbox', 'radio', 'image', 'file', 'editor', 'textarea'];
}
