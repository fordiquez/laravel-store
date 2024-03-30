<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->jobTitle(),
            'slug' => fn (array $attributes) => str($attributes['title'])->slug(),
            'description' => fake()->sentence(rand(2, 10)),
            'is_active' => true,
        ];
    }
}
