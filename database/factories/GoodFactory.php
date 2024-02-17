<?php

namespace Database\Factories;

use App\Enums\GoodStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Good;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GoodFactory extends Factory
{
    protected $model = Good::class;

    public function definition(): array
    {
        return [
            'vendor_code' => fake()->unique()->numberBetween(1000000000),
            'title' => fake()->unique()->text(rand(5, 20)),
            'slug' => fn (array $attributes) => Str::slug($attributes['title']),
            'category_id' => Category::whereDoesntHave('subcategories')->inRandomOrder()->value('id'),
            'brand_id' => Brand::inRandomOrder()->value('id'),
            'description' => fake()->paragraph(rand(5, 10)),
            'short_description' => fake()->sentence(rand(25, 75)),
            'warning_description' => fake()->sentence(rand(10, 50)),
            'old_price' => fake()->boolean ? fake()->numberBetween(100, 100000) : null,
            'price' => fn (array $attributes) => $attributes['old_price'] ? $attributes['old_price'] - ($attributes['old_price'] * rand(1, 80)) / 100 : rand(100, 100000),
            'quantity' => fake()->numberBetween(0, 100),
            'status' => GoodStatus::getRandomValue(),
        ];
    }
}
