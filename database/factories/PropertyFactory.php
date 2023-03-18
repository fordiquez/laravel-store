<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'filterable' => fake()->boolean(77),
            'name' => fake()->unique()->sentence(5),
            'slug' => fn (array $attributes) => str($attributes['name'])->slug(),
        ];
    }
}
