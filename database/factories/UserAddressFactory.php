<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Street;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word,
            'is_main' => fake()->boolean,
            'country_id' => Country::inRandomOrder()->value('id'),
            'state_id' => State::inRandomOrder()->value('id'),
            'city_id' => City::inRandomOrder()->value('id'),
            'street_id' => Street::inRandomOrder()->value('id'),
            'house' => fake()->buildingNumber,
            'flat' => fake()->boolean ?: fake()->numberBetween(0, 100),
            'postal_code' => fake()->postcode,
        ];
    }
}
