<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Street;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Street>
 */
class StreetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid,
            'city_id' => City::inRandomOrder()->value('id'),
            'name' => fake()->streetName,
        ];
    }
}
