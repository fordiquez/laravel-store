<?php

namespace Database\Seeders;

use Database\Factories\CountryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $validCountries = [
            'UA', 'PL', 'EE', 'LV', 'LT', 'CZ', 'DK', 'US', 'CA', 'GB', 'DE', 'FR', 'NO',
            'AU', 'AT', 'BE', 'BG', 'ES', 'FI', 'GR', 'IS', 'IE', 'IL', 'IT', 'JP', 'LU',
            'MD', 'MA', 'NL', 'NZ', 'PT', 'RO', 'SA', 'SK', 'SI', 'KR', 'SE', 'CH', 'TR',
        ];

        $countries = Http::acceptJson()
            ->get('https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries.json')
            ->json();

        foreach ($countries as $country) {
            if (in_array($country['iso2'], $validCountries)) {
                $model = CountryFactory::new()->create([
                    'name' => $country['name'],
                    'capital' => $country['capital'] === 'Kiev' ? 'Kyiv' : $country['capital'],
                    'iso2' => $country['iso2'],
                    'iso3' => $country['iso3'],
                    'phone_code' => $country['phone_code'],
                    'currency_code' => $country['currency'],
                    'tld' => $country['tld'],
                    'region' => $country['region'],
                    'subregion' => $country['subregion'],
                    'is_active' => true,
                ]);
                $iso2 = strtolower($model->iso2);
                $flagUrl = "https://raw.githubusercontent.com/MohmmedAshraf/blade-flags/main/resources/svg/country-$iso2.svg";
                try {
                    $model->addMediaFromUrl($flagUrl)->toMediaCollection('flags', 'public');
                } catch (FileCannotBeAdded) {}
            }
        }
    }
}
