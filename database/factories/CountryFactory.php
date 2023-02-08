<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->country,
            'capital' => fake()->city,
            'iso2' => fake()->countryCode,
            'iso3' => fake()->countryISOAlpha3,
            'phone_code' => fake()->phoneNumber,
            'currency_code' => fake()->currencyCode,
            'tld' => strtolower('.' . fake()->currencyCode),
            'region' => Country::$regions[rand(0, count(Country::$regions) - 1)],
            'is_active' => fake()->boolean(22),
        ];
    }
}
