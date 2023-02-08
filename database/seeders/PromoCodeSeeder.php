<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Database\Factories\PromoCodeFactory;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        PromoCodeFactory::new()
            ->count(10)
            ->create();
    }
}
