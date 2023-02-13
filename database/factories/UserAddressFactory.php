<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Street;
use App\Models\User;
use App\Models\UserAddress;
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
        $street = Street::inRandomOrder()->first();

        return [
            'title' => fake()->unique()->words(rand(1, 3), true),
            'country_id' => $street->city->state->country_id,
            'state_id' => $street->city->state_id,
            'city_id' => $street->city_id,
            'street_id' => $street->id,
            'house' => fake()->buildingNumber,
            'flat' => fake()->boolean ?: fake()->numberBetween(0, 100),
            'postal_code' => fake()->postcode,
        ];
    }
}
