<?php

namespace Database\Factories;

use App\Models\Good;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class GoodPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::inRandomOrder()->value('id'),
            'good_id' => Good::inRandomOrder()->value('id'),
            'value' => fake()->sentence(rand(1, 3)),
        ];
    }
}
