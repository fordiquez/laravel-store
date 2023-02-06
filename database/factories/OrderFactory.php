<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
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
            'ref_id' => fake()->unique()->uuid,
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'user_address_id' => UserAddress::query()->inRandomOrder()->value('id'),
            'delivery_method' => Order::$deliveryMethods[fake()->numberBetween(0, count(Order::$deliveryMethods) - 1)],
            'payment_method' => Order::$paymentMethods[fake()->numberBetween(0, count(Order::$paymentMethods) - 1)],
            'goods_cost' => fake()->numberBetween(100, 10000),
            'delivery_cost' => fake()->numberBetween(0, 100),
            'total_cost' => fn (array $attributes) => $attributes['goods_cost'] + $attributes['delivery_cost'],
            'status' => Order::$statuses[rand(0, count(Order::$statuses) - 1)]
        ];
    }
}
