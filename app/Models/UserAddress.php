<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_main',
        'country_id',
        'state_id',
        'city_id',
        'street_id',
        'house',
        'flat',
        'postal_code',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function street(): BelongsTo
    {
        return $this->belongsTo(Street::class);
    }
}
