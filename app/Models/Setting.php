<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Setting extends Model
{
    protected $fillable = ['group', 'name', 'details', 'key', 'value'];

    public static function sendNotification(string $type, string $title, string $text, string $key = 'notification'): void
    {
        Session::flash($key, [
            'id' => rand(),
            'type' => $type,
            'title' => $title,
            'text' => $text,
        ]);
    }
}
