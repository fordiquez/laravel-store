<?php

namespace Database\Seeders;

use Database\Factories\CountryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

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
            'UA', 'PL', 'EE', 'LV', 'LT', 'DK', 'US', 'CA', 'GB', 'DE', 'FR', 'NO',
            'AU', 'AT', 'BE', 'BG', 'CZ', 'ES', 'FI', 'GR', 'IS', 'IE', 'IT', 'JP', 'LU', 'MD', 'MA', 'NL', 'NZ', 'PT', 'RO', 'SA', 'SK', 'SI', 'KR', 'SE', 'CH', 'TR'
        ];

        $countries = Http::acceptJson()->get('https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries.json')->json();

        foreach ($countries as $country) {
            if (in_array($country['iso2'], $validCountries)) {
                CountryFactory::new()->create([
                    'name' => $country['name'],
                    'capital' => $country['capital'] === 'Kiev' ? 'Kyiv' : $country['capital'],
                    'iso2' => $country['iso2'],
                    'iso3' => $country['iso3'],
                    'phone_code' => $country['phone_code'],
                    'currency_code' => $country['currency'],
                    'tld' => $country['tld'],
                    'region' => $country['region'],
                    'subregion' => $country['subregion'],
                    'timezone' => $country['timezones'][0]['zoneName'],
                    'is_active' => true
                ]);
            }
        }
    }
}
