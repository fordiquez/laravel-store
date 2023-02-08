<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Country extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'short_name',
        'capital',
        'iso2',
        'iso3',
        'phone_code',
        'currency_code',
        'tld',
        'region',
        'subregion',
        'is_active',
    ];

    public static array $regions = ['Asia', 'Africa', 'Europe', 'Americas', 'Oceania'];

    public static array $subregions = [
        'Asia' => ['Southern Asia', 'Eastern Asia', 'South-Eastern Asia', 'Western Asia', 'Central Asia'],
        'Africa' => ['Eastern Africa', 'Western Africa', 'Northern Africa', 'Southern Africa', 'Middle Africa'],
        'Europe' => ['Eastern Europe', 'Western Europe', 'Southern Europe', 'Northern Europe'],
        'Americas' => ['Northern America', 'South America', 'Central America', 'Caribbean'],
        'Oceania' => ['Australia and New Zealand', 'Melanesia', 'Polynesia', 'Micronesia'],
    ];

    public static function getSubregions(): array
    {
        $subregions = [];
        foreach (self::$subregions as $subregion) {
            array_push($subregions, ...$subregion);
        }

        return $subregions;
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
