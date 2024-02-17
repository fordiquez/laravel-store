<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromoCodeFactory extends Factory
{
    protected $model = PromoCode::class;

    public function definition(): array
    {
        $type = fake()->boolean ? 'fixed' : 'percentage';

        return [
            'key' => Str::random(8),
            'type' => $type,
            'value' => fake()->numberBetween(1, $type === 'percentage' ? 99 : 1000),
            'description' => fake()->sentence(10),
            'used_times' => fake()->numberBetween(0, 1000),
            'starts_at' => fake()->dateTimeThisYear(),
            'expires_at' => fake()->dateTimeThisYear(),
            'greater_than' => fake()->boolean ? fake()->numberBetween(0, 1000) : null,
            'is_active' => fake()->boolean(77),
            'is_public' => fake()->boolean(99),
        ];
    }
}
