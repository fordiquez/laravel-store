<?php

namespace Database\Seeders;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $laptopsAndComputers = CategoryFactory::new()->hasImage()->create([
            'title' => 'Laptops and Computers',
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Laptops',
                'parent_id' => $laptopsAndComputers->id
            ],
            [
                'title' => 'Computers, nettops, monoblocs',
                'parent_id' => $laptopsAndComputers->id
            ],
            [
                'title' => 'Monitors',
                'parent_id' => $laptopsAndComputers->id
            ],
        ]);

        $tablets = CategoryFactory::new()->hasImage()->create([
            'title' => 'Tablets'
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Apple iPad',
                'parent_id' => $tablets->id,
            ],
            [
                'title' => 'Samsung',
                'parent_id' => $tablets->id,
            ],
            [
                'title' => 'Lenovo',
                'parent_id' => $tablets->id,
            ],
            [
                'title' => 'Xiaomi',
                'parent_id' => $tablets->id,
            ],
        ]);

        $computerComponents = CategoryFactory::new()->hasImage()->create([
            'title' => 'Computer components',
            'parent_id' => $laptopsAndComputers->id
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Motherboards',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Processors',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'RAM',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Video cards',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Sound cards',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Hard drives',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Optical drives',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Power supply units',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Cases',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Cooling systems',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Uninterrupted power supply',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'SSD',
                'parent_id' => $computerComponents->id,
            ],
            [
                'title' => 'Video capture cards',
                'parent_id' => $computerComponents->id,
            ],
        ]);

        $keyboardsAndMice = CategoryFactory::new()->hasImage()->create([
            'title' => 'Keyboards and mice',
            'parent_id' => $computerComponents->id,
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Computer mice',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Gaming mice',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Gaming keyboards',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Keyboards',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Playing surfaces',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Keyboard + mouse',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Accessories for keyboards and mice',
                'parent_id' => $keyboardsAndMice->id,
            ],
            [
                'title' => 'Gaming keyboard + mouse',
                'parent_id' => $keyboardsAndMice->id,
            ],
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Cables and adapters',
                'parent_id' => $laptopsAndComputers->id
            ],
            [
                'title' => 'Headphones and accessories',
                'parent_id' => $laptopsAndComputers->id
            ],
            [
                'title' => 'Accessories for electronics',
                'parent_id' => $laptopsAndComputers->id
            ],
        ]);

        $gamerGoods = CategoryFactory::new()->hasImage()->create([
            'title' => 'Goods for gamers',
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Game consoles',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Gaming laptops',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Gaming computers',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Gaming monitors',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Armchairs for gamers',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Gaming tables',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Gaming routers',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Accessories and souvenirs',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Games',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Game peripherals',
                'parent_id' => $gamerGoods->id
            ],
            [
                'title' => 'Glasses and helmets of virtual reality',
                'parent_id' => $gamerGoods->id
            ],
        ]);

        $gamerComponents = CategoryFactory::new()->hasImage()->create([
            'title' => 'Components for gamers',
            'parent_id' => $gamerGoods->id
        ]);

        CategoryFactory::new()->hasImage()->createMany([
            [
                'title' => 'Gaming Video cards',
                'parent_id' => $gamerComponents->id
            ],
            [
                'title' => 'Gaming Processors',
                'parent_id' => $gamerComponents->id
            ],
            [
                'title' => 'Gaming Motherboards',
                'parent_id' => $gamerComponents->id
            ],
            [
                'title' => 'Gaming Power supply units',
                'parent_id' => $gamerComponents->id
            ],
            [
                'title' => 'Gaming Cooling systems',
                'parent_id' => $gamerComponents->id
            ],
            [
                'title' => 'Gaming Cases',
                'parent_id' => $gamerComponents->id
            ],
        ]);
    }
}
