<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Good;
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
            'slug' => fn (array $attributes) => Str::slug($attributes['title']),
            'category_id' => Category::whereNotIn('id', [1, 6, 16, 17, 20, 23, 24])->inRandomOrder()->value('id'),
            'brand_id' => Brand::inRandomOrder()->value('id'),
            'description' => fake()->paragraph(10),
            'short_description' => fake()->sentence(50),
            'warning_description' => fake()->sentence(200),
            'old_price' => fake()->numberBetween(100, 10000),
            'price' => fn (array $attributes) => $attributes['old_price'] - (($attributes['old_price'] * rand(1, 80)) / 100),
            'quantity' => fake()->numberBetween(0, 100),
            'status' => Good::$statuses[rand(0, count(Good::$statuses) - 1)]
        ];
    }
}
