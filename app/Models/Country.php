<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class Country extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'short_name',
        'capital',
        'iso2',
        'iso3',
        'phone_code',
        'currency',
        'tld',
        'region',
        'subregion',
        'is_active',
    ];

    public static array $validCountries = [
        'UA', 'PL', 'EE', 'LV', 'LT', 'CZ', 'DK', 'US', 'CA', 'GB', 'DE', 'FR', 'NO',
        'AU', 'AT', 'BE', 'BG', 'ES', 'FI', 'GR', 'IS', 'IE', 'IL', 'IT', 'JP', 'LU',
        'MD', 'MA', 'NL', 'NZ', 'PT', 'RO', 'SA', 'SK', 'SI', 'KR', 'SE', 'CH', 'TR',
    ];

    public const DEFAULT_COUNTRY = 'UA';

    public static array $regions = [
        'Asia' => ['Southern Asia', 'Eastern Asia', 'South-Eastern Asia', 'Western Asia', 'Central Asia'],
        'Africa' => ['Eastern Africa', 'Western Africa', 'Northern Africa', 'Southern Africa', 'Middle Africa'],
        'Europe' => ['Eastern Europe', 'Western Europe', 'Southern Europe', 'Northern Europe'],
        'Americas' => ['Northern America', 'South America', 'Central America', 'Caribbean'],
        'Oceania' => ['Australia and New Zealand', 'Melanesia', 'Polynesia', 'Micronesia'],
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Country $country) {
            $iso2 = strtolower($country->iso2);
            $flagUrl = "https://raw.githubusercontent.com/MohmmedAshraf/blade-flags/main/resources/svg/country-$iso2.svg";
            try {
                $country->addMediaFromUrl($flagUrl)->toMediaCollection('flags', 'public');
            } catch (FileCannotBeAdded $exception) {
                Log::error($exception->getMessage());
            }
        });
    }

    public static function getRegions($withNamedKeys = false): array
    {
        $regionsWithNamedKeys = Arr::map(self::$regions, fn($value, $key) => $key);

        return $withNamedKeys ? $regionsWithNamedKeys : array_keys($regionsWithNamedKeys);
    }

    public static function getSubregions($region = null, $withNamedKeys = false): array
    {

        $subregions = $region ? Arr::get(self::$regions, $region) : [];
        $subregionsWithNamedKeys = [];

        if (!$region) {
            foreach (self::$regions as $subregion) {
                array_push($subregions, ...$subregion);
            }
        }

        foreach ($subregions as $subregion) {
            $subregionsWithNamedKeys[$subregion] = $subregion;
        }

        return $withNamedKeys ? $subregionsWithNamedKeys : $subregions;
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
