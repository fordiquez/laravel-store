<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public array $networkCodes = [63, 73, 93, 67, 68, 96, 97, 98, 50, 66, 95, 99];

    public function definition(): array
    {
        $gender = fake()->boolean ? 'male' : 'female';

        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-30 years', '-14 years'),
            'gender' => $gender,
            'phone' => '+380' . $this->networkCodes[rand(0, count($this->networkCodes) - 1)] . fake()->unique()->numberBetween(1000000, 9999999),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt(static::$password ??= fake()->password()),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'email_verified_at' => null,
            ],
        );
    }
}
