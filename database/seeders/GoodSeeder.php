<?php

namespace Database\Seeders;

use Database\Factories\GoodFactory;
use Database\Factories\OptionFactory;
use Database\Factories\OptionValueFactory;
use Database\Factories\PropertyFactory;
use Database\Factories\GoodPropertyFactory;
use Database\Factories\TagFactory;
use Illuminate\Database\Seeder;

class GoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = TagFactory::new()->count(22)->create();

        $properties = PropertyFactory::new()->count(22)->create();

        OptionFactory::new()->count(5)->create();
        $optionValues = OptionValueFactory::new()->count(10)->create();

        GoodFactory::new()->count(222)
            ->hasReviews(rand(1, 3))
            ->hasAttached($tags->toQuery()->inRandomOrder()->take(rand(1, 5))->get())
            ->hasAttached($properties->toQuery()->inRandomOrder()->take(rand(1, 5))->get(), fn () => ['value' => fake()->sentence(rand(1, 3))])
            ->hasAttached($optionValues->toQuery()->inRandomOrder()->take(rand(1, 5))->get())
            ->create();
    }
}
