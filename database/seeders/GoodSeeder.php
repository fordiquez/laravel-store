<?php

namespace Database\Seeders;

use Database\Factories\GoodFactory;
use Database\Factories\PropertyFactory;
use Database\Factories\TagFactory;
use Illuminate\Database\Seeder;

class GoodSeeder extends Seeder
{
    public function run(): void
    {
        $tags = TagFactory::new()->count(22)->create();

        $properties = PropertyFactory::new()->count(10)->create();

        GoodFactory::new()->count(222)
            ->hasReviews(rand(1, 3))
            ->hasAttached($tags->toQuery()->inRandomOrder()->take(rand(1, 5))->get())
            ->hasAttached($properties, fn () => [
                'property_id' => $properties->toQuery()->inRandomOrder()->value('id'),
                'value' => fake()->unique()->sentence(rand(1, 3)),
            ])
            ->create();
    }
}
