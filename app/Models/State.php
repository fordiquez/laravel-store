<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'name', 'old_name', 'country_id', 'parent_id', 'type', 'is_active'];

    public static array $types = [
        'state',
        'province',
        'region',
        'county',
        'district',
        'city',
        'outlying area',
        'islands / groups of islands',
        'autonomous region',
        'metropolitan department',
        'republic',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
