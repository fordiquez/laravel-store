<?php

namespace Database\Factories;

use App\Models\Good;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'good_id' => Good::query()->inRandomOrder()->value('id'),
            'is_buyer' => fake()->boolean,
            'content' => fake()->paragraph(10),
            'advantages' => fake()->sentence(10),
            'disadvantages' => fake()->sentence(10),
            'rating' => fake()->numberBetween(1, 5),
            'ip_address' => fake()->ipv4,
        ];
    }
}
