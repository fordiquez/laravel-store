<?php

namespace Database\Seeders;

use Database\Factories\BrandFactory;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BrandFactory::new()->count(22)->create();
    }
}
