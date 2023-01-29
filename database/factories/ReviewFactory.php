<?php

namespace Database\Factories;

use App\Models\Good;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'good_id' => Good::query()->inRandomOrder()->value('id'),
            'description' => fake()->paragraph(10),
            'advantages' => fake()->sentence(10),
            'disadvantages' => fake()->sentence(10),
            'rating' => fake()->numberBetween(1, 5)
        ];
    }
}
