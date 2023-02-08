<?php

namespace Database\Seeders;

use App\Models\Country;
use Database\Factories\StateFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $validCountries = [
            'UA',
            'PL',
            'EE',
            'LV',
            'LT',
            'DK',
            'US',
            'CA',
            'GB',
            'DE',
            'FR',
            'NO',
            'AU',
            'AT',
            'BE',
            'BG',
            'CZ',
            'ES',
            'FI',
            'GR',
            'IS',
            'IE',
            'IT',
            'JP',
            'LU',
            'MD',
            'MA',
            'NL',
            'NZ',
            'PT',
            'RO',
            'SA',
            'SK',
            'SI',
            'KR',
            'SE',
            'CH',
            'TR',
        ];

        $states = Http::acceptJson()
            ->get('https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/states.json')
            ->json();

        foreach ($states as $state) {
            if (in_array($state['country_code'], $validCountries)) {
                StateFactory::new()->create([
                    'name' => $state['name'],
                    'country_id' => Country::whereIso2($state['country_code'])->value('id'),
                    'type' => $state['type'],
                ]);
            }
        }
    }
}
