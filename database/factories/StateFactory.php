<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<State>
 */
class StateFactory extends Factory
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
            'name' => fake()->city,
            'country_id' => Country::inRandomOrder()->value('id'),
            'type' => State::$types[rand(0, count(State::$types) - 1)],
        ];
    }
}
