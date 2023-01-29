<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class UserContactFactory extends Factory
{
    public array $networkCodes = [63, 73, 93, 67, 68, 96, 97, 98, 50, 66, 95, 99];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mobile_phone' => "+380" . $this->networkCodes[rand(0, count($this->networkCodes) - 1)] . fake()->unique()->numberBetween(1000000, 9999999)
        ];
    }
}
