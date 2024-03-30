<?php

namespace Database\Factories;

use App\Enums\OrderDelivery;
use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'uuid' => fake()->unique()->uuid,
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
