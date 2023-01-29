<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\GoodStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class GoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_code' => fake()->unique()->numberBetween(1000000000),
            'title' => fake()->unique()->text(20),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title']);
            },
            'category_id' => Category::whereNotIn('id', [1, 6, 16, 17, 20, 23, 24])->inRandomOrder()->value('id'),
            'description' => fake()->paragraph(10),
            'short_description' => fake()->sentence(50),
            'warning_description' => fake()->sentence(200),
            'old_price' => fake()->numberBetween(100, 10000),
            'price' => fake()->numberBetween(100, 10000),
            'quantity' => fake()->numberBetween(0, 100),
            'status_id' => GoodStatus::query()->inRandomOrder()->value('id')
        ];
    }
}
