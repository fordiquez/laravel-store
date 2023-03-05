<?php

namespace Database\Factories;

use App\Enums\OrderDelivery;
use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
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
            'delivery_method' => OrderDelivery::getRandomValue(),
            'payment_method' => OrderPayment::getRandomValue(),
            'goods_cost' => fake()->numberBetween(100, 10000),
            'delivery_cost' => fake()->numberBetween(0, 100),
            'total_cost' => fn (array $attributes) => $attributes['goods_cost'] + $attributes['delivery_cost'],
            'status' => OrderStatus::getRandomValue(),
        ];
    }
}
