<?php

namespace Database\Seeders;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laptopsAndComputers = CategoryFactory::new()->create([
            'title' => 'Laptops and Computers',
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Laptops',
                'parent_id' => $laptopsAndComputers->id,
            ],
            [
                'title' => 'Computers, nettops, monoblocs',
                'parent_id' => $laptopsAndComputers->id,
            ],
            [
                'title' => 'Monitors',
                'parent_id' => $laptopsAndComputers->id,
            ],
        ]);

        $computerComponents = CategoryFactory::new()->create([
            'title' => 'Computer components',
            'parent_id' => $laptopsAndComputers->id,
        ]);

        CategoryFactory::new()->createMany([
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

        $keyboardsAndMice = CategoryFactory::new()->create([
            'title' => 'Keyboards and mice',
            'parent_id' => $computerComponents->id,
        ]);

        CategoryFactory::new()->createMany([
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

        $electronicAccessories = CategoryFactory::new()->create([
            'title' => 'Accessories for electronics',
            'parent_id' => $laptopsAndComputers->id,
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'USB flash drives',
                'parent_id' => $electronicAccessories->id,
            ],
            [
                'title' => 'Hubs and card readers',
                'parent_id' => $electronicAccessories->id,
            ],
            [
                'title' => 'Accessories for PCs and laptops',
                'parent_id' => $electronicAccessories->id,
            ],
            [
                'title' => 'Components for laptops',
                'parent_id' => $electronicAccessories->id,
            ],
            [
                'title' => 'Cases and keyboards for tablets',
                'parent_id' => $electronicAccessories->id,
            ],
            [
                'title' => 'Bags, backpacks and laptop cases',
                'parent_id' => $electronicAccessories->id,
            ],
        ]);

        $electronics = CategoryFactory::new()->create([
            'title' => 'Smartphones, TV and Electronics',
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Mobile phones',
                'parent_id' => $electronics->id,
            ],
            [
                'title' => 'Televisions',
                'parent_id' => $electronics->id,
            ],
            [
                'title' => 'Tablets',
                'parent_id' => $electronics->id,
            ],
            [
                'title' => 'Power banks and charging stations',
                'parent_id' => $electronics->id,
            ],
        ]);

        $headPhonesAndAccessories = CategoryFactory::new()->create([
            'title' => 'Headphones and accessories',
            'parent_id' => $electronics->id,
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Headphone',
                'parent_id' => $headPhonesAndAccessories->id,
            ],
            [
                'title' => 'Accessories for headphones',
                'parent_id' => $headPhonesAndAccessories->id,
            ],
        ]);

        $wearableGadgets = CategoryFactory::new()->create([
            'title' => 'Wearable gadgets',
            'parent_id' => $electronics->id,
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Smart watches',
                'parent_id' => $wearableGadgets->id,
            ],
            [
                'title' => 'Fitness bracelets',
                'parent_id' => $wearableGadgets->id,
            ],
            [
                'title' => 'Virtual reality glasses',
                'parent_id' => $wearableGadgets->id,
            ],
        ]);

        $audioEquipment = CategoryFactory::new()->create([
            'title' => 'Audio equipment',
            'parent_id' => $electronics->id,
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Portable speakers',
                'parent_id' => $audioEquipment->id,
            ],
            [
                'title' => 'Music centers',
                'parent_id' => $audioEquipment->id,
            ],
            [
                'title' => 'Computer speakers',
                'parent_id' => $audioEquipment->id,
            ],
            [
                'title' => 'Home cinemas',
                'parent_id' => $audioEquipment->id,
            ],
        ]);

        $mobilePhonesAccessories = CategoryFactory::new()->create([
            'title' => 'Accessories for mobile phones',
            'parent_id' => $electronics->id,
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Cables and adapters',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
            [
                'title' => 'Phone cases',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
            [
                'title' => 'Protective films and glass',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
            [
                'title' => 'Spare parts for phones',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
            [
                'title' => 'Phone chargers',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
            [
                'title' => 'Batteries for phones',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
            [
                'title' => 'Phone holders',
                'parent_id' => $mobilePhonesAccessories->id,
            ],
        ]);

        $gamerGoods = CategoryFactory::new()->create([
            'title' => 'Goods for gamers',
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Game consoles',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Gaming laptops',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Gaming computers',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Gaming monitors',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Armchairs for gamers',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Gaming tables',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Gaming routers',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Accessories and souvenirs',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Games',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Game peripherals',
                'parent_id' => $gamerGoods->id,
            ],
            [
                'title' => 'Glasses and helmets of virtual reality',
                'parent_id' => $gamerGoods->id,
            ],
        ]);

        $gamerComponents = CategoryFactory::new()->create([
            'title' => 'Components for gamers',
            'parent_id' => $gamerGoods->id,
        ]);

        CategoryFactory::new()->createMany([
            [
                'title' => 'Gaming Video cards',
                'parent_id' => $gamerComponents->id,
            ],
            [
                'title' => 'Gaming Processors',
                'parent_id' => $gamerComponents->id,
            ],
            [
                'title' => 'Gaming Motherboards',
                'parent_id' => $gamerComponents->id,
            ],
            [
                'title' => 'Gaming Power supply units',
                'parent_id' => $gamerComponents->id,
            ],
            [
                'title' => 'Gaming Cooling systems',
                'parent_id' => $gamerComponents->id,
            ],
            [
                'title' => 'Gaming Cases',
                'parent_id' => $gamerComponents->id,
            ],
        ]);
    }
}
