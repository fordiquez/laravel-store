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
        CategoryFactory::new()->hasImage(1)->createMany([
            [
                'title' => 'Laptops and Computers',
                'slug' => 'laptops-and-computers'
            ],
            [
                'title' => 'Laptops',
                'slug' => 'laptops',
                'parent_id' => 1
            ],
            [
                'title' => 'Computers',
                'slug' => 'computers',
                'parent_id' => 1
            ],
            [
                'title' => 'Monitors',
                'slug' => 'monitors',
                'parent_id' => 1
            ],
            [
                'title' => 'Tablets',
                'slug' => 'tablets',
                'parent_id' => 1
            ],
            [
                'title' => 'Computer components',
                'slug' => 'computer-components',
                'parent_id' => 1
            ],
            [
                'title' => 'SSD',
                'slug' => 'ssd',
                'parent_id' => 6
            ],
            [
                'title' => 'Cooling systems',
                'slug' => 'cooling-systems',
                'parent_id' => 6
            ],
            [
                'title' => 'Video cards',
                'slug' => 'video-cards',
                'parent_id' => 6
            ],
            [
                'title' => 'RAM',
                'slug' => 'ram',
                'parent_id' => 6
            ],
            [
                'title' => 'Processors',
                'slug' => 'processors',
                'parent_id' => 6
            ],
            [
                'title' => 'Motherboards',
                'slug' => 'motherboards',
                'parent_id' => 6
            ],
            [
                'title' => 'Hard drives',
                'slug' => 'hard-drives',
                'parent_id' => 6
            ],
            [
                'title' => 'Power supply units',
                'slug' => 'power-supply-units',
                'parent_id' => 6
            ],
            [
                'title' => 'Headphones and accessories',
                'slug' => 'headphones-and-accessories',
                'parent_id' => 1
            ],
            [
                'title' => 'Keyboards and mouses',
                'slug' => 'keyboards-and-mouses',
                'parent_id' => 1
            ],
            [
                'title' => 'Computer mouses',
                'slug' => 'computer-mouses',
                'parent_id' => 16
            ],
            [
                'title' => 'Mouse mats',
                'slug' => 'mouse-mats',
                'parent_id' => 16
            ],
            [
                'title' => 'Keyboards',
                'slug' => 'keyboards',
                'parent_id' => 16
            ],
            [
                'title' => 'Accessories for electronics',
                'slug' => 'accessories-for-electronics',
                'parent_id' => 1
            ],
            [
                'title' => 'USB flash drives',
                'slug' => 'usb-flash-drives',
                'parent_id' => 20
            ],
            [
                'title' => 'Bags, backpacks and laptop cases',
                'slug' => 'bags-backpacks-and-laptop-cases',
                'parent_id' => 20
            ],
            [
                'title' => 'Smartphones, TV and Electronics',
                'slug' => 'smartphones-tv-and-electronics'
            ],
            [
                'title' => 'Goods for gamers',
                'slug' => 'goods-for-gamers'
            ],
        ]);
    }
}
