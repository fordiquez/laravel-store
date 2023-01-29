<?php

namespace Database\Factories;

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
            'country' => fake()->country(),
            'city' => fake()->city(),
            'street' => fake()->streetName(),
            'house' => fake()->buildingNumber(),
            'flat' => fake()->numberBetween(0, 100),
            'is_active' => rand(0, 1),
        ];
    }
}
