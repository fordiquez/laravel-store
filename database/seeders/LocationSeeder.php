<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class LocationSeeder extends Seeder
{
    /** @throws FileCannotBeAdded|FileIsTooBig|FileDoesNotExist */
    public function run(): void
    {
        $countries = Http::acceptJson()->withHeader('X-CSCAPI-KEY', config('services.csc.key'))->get(config('services.csc.url'))->json();

        collect($countries)->each(
            function (array $country) {
                $countryDetails = Http::acceptJson()
                    ->withHeader('X-CSCAPI-KEY', config('services.csc.key'))
                    ->get(config('services.csc.url') . $country['iso2'])
                    ->json();

                $createdCountry = Country::create([
                    'name' => $countryDetails['name'],
                    'capital' => $countryDetails['capital'],
                    'iso2' => $countryDetails['iso2'],
                    'iso3' => $countryDetails['iso3'],
                    'phone_code' => $countryDetails['phonecode'],
                    'currency' => $countryDetails['currency'],
                    'region' => $countryDetails['region'],
                    'subregion' => $countryDetails['subregion'],
                ]);

                $countryISO2 = strtolower($country['iso2']);
                $createdCountry->addMediaFromUrl("https://flagcdn.com/$countryISO2.svg")->toMediaCollection('flag', 'public');

                $states = Http::acceptJson()
                    ->withHeader('X-CSCAPI-KEY', config('services.csc.key'))
                    ->get(config('services.csc.url') . "$countryISO2/states")
                    ->json();

                collect($states)->sortBy('name')->each(function (array $state) use ($createdCountry, $countryISO2) {
                    $createdState = $createdCountry->states()->create(['name' => $state['name']]);

                    $cities = Http::acceptJson()
                        ->withHeader('X-CSCAPI-KEY', config('services.csc.key'))
                        ->get(config('services.csc.url') . "$countryISO2/states/" . $state['iso2'] . '/cities')
                        ->json();

                    collect($cities)
                        ->filter(fn ($city) => !empty($city['name']))
                        ->sortBy('name')
                        ->each(fn (array $city) => $createdState->cities()->create(['name' => $city['name']]));
                });
            });
    }
}
