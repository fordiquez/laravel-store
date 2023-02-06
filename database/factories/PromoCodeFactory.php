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
            'key' => Str::random(8),
            'type' => fake()->boolean ? 'fixed' : 'percentage',
            'value' => fake()->numberBetween(1, 99),
            'description' => fake()->sentence(10),
            'used_times' => fake()->numberBetween(0, 1000),
            'start_date' => fake()->dateTimeThisYear(),
            'expire_date' => fake()->dateTimeThisYear(),
            'is_active' => fake()->boolean
        ];
    }
}
