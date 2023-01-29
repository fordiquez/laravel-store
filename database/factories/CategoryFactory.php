<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title']);
            },
            'src' => function (array $attributes) {
                return $this->faker->loremflickr('categories', $attributes['slug']);
            },
        ];
    }
}
