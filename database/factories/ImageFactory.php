<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Good;
use App\Models\Image;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fn(array $attributes) => $this->faker->fakeImage($this->directory($attributes['imageable_type'])),
        ];
    }

    public function directory(string $model): string
    {
        return match ($model) {
            Category::class => 'categories',
            Review::class => 'reviews',
            Good::class => 'goods',
            default => 'images',
        };
    }

    public function configure(): ImageFactory
    {
        return $this->for(static::factoryForModel($this->imageable()), 'imageable');
    }

    public function imageable()
    {
        return $this->faker->randomElement([Category::class, Good::class, Review::class]);
    }
}
