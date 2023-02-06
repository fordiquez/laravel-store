<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class OrderRecipientFactory extends Factory
{
    public array $networkCodes = [63, 73, 93, 67, 68, 96, 97, 98, 50, 66, 95, 99];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'description' => fake()->text(50),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => "+380" . $this->networkCodes[rand(0, count($this->networkCodes) - 1)] . fake()->unique()->numberBetween(1000000, 9999999),
            'is_default' => fake()->boolean,
        ];
    }
}
