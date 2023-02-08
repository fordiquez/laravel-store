<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->jobTitle(),
            'slug' => fn(array $attributes) => str($attributes['title'])->slug(),
            'description' => fake()->sentence(rand(2, 10)),
            'is_active' => true,
        ];
    }
}
