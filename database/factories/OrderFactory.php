<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->unique()->numberBetween(100000000, 999999999),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'delivery_method' => Order::$deliveryMethods[fake()->numberBetween(0, count(Order::$deliveryMethods) - 1)],
            'goods_cost' => fake()->numberBetween(100, 10000),
            'delivery_cost' => fake()->numberBetween(0, 100),
            'total_cost' => fn (array $attributes) => $attributes['goods_cost'] + $attributes['delivery_cost']
        ];
    }
}
