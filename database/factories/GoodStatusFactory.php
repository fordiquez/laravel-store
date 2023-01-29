<?php

namespace Database\Factories;

use App\Models\GoodStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class GoodStatusFactory extends Factory
{
    public int $index = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => GoodStatus::$statuses[$this->index++]
        ];
    }
}
