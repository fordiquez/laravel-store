<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class PromoCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(10),
            'key' => Str::random(8),
            'value' => fake()->numberBetween(1, 99),
            'deadline' => fake()->dateTimeThisYear(),
            'is_active' => fake()->numberBetween(0, 1)
        ];
    }
}
