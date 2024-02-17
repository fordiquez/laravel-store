<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'filterable' => fake()->boolean(77),
            'name' => fake()->unique()->sentence(rand(1, 5)),
            'slug' => fn (array $attributes) => str($attributes['name'])->slug(),
        ];
    }
}
