<?php

namespace Database\Seeders;

use Database\Factories\PromoCodeFactory;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromoCodeFactory::new()
            ->count(10)
            ->create();
    }
}
