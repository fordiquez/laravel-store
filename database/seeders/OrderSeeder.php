<?php

namespace Database\Seeders;

use Database\Factories\OrderFactory;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        OrderFactory::new()->count(10)->hasOrderItems(rand(1, 5))->create();
    }
}
