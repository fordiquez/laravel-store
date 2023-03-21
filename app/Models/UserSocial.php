<?php

namespace App\Models;

use App\Enums\UserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserSocial extends Model
{
    protected $fillable = ['user_id', 'provider', 'provider_id', 'provider_token'];

    protected $casts = [
        'provider' => UserProvider::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getGithubUsername(string $username): array
    {
        $username = Str::wordCount($username) > 1 ? Str::of($username) : Str::of($username)->append(' user');

        return Arr::map($username->explode(' ', 2)->all(), fn ($value) => Str::limit($value, 50, null));
    }
}
