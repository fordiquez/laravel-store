<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Country extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'capital',
        'iso2',
        'iso3',
        'phone_code',
        'currency',
        'region',
        'subregion',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['flag'];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasManyThrough
    {
        return $this->hasManyThrough(City::class, State::class);
    }

    public function getRouteKeyName(): string
    {
        return 'iso2';
    }

    protected function flag(): Attribute
    {
        return Attribute::get(fn () => $this->getFirstMediaUrl('flag'));
    }
}
