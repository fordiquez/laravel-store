<?php

namespace Database\Seeders;

use Database\Factories\StreetFactory;
use Illuminate\Database\Seeder;

class StreetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        StreetFactory::new()
            ->count(222)
            ->create();
    }
}
