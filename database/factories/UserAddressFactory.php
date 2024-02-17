<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        $country = Country::inRandomOrder()->first();
        $state = $country->has('states') ? $country->states()->inRandomOrder()->first() : null;

        return [
            'country_id' => $country->id,
            'state_id' => $state?->id,
            'city_id' => $state?->has('cities') ? $state->cities()->inRandomOrder()->value('id') : null,
            'street' => fake()->streetName,
            'house' => fake()->buildingNumber,
            'flat' => fake()->boolean ?: fake()->numberBetween(0, 100),
            'postal_code' => intval(fake()->postcode),
        ];
    }
}
