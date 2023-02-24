<?php

namespace Database\Seeders;

use App\Models\Good;
use App\Models\OrderHistory;
use Database\Factories\OrderFactory;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        OrderFactory::new()
            ->count(10)
            ->hasOrderHistories(rand(1, 3))
            ->hasOrderItems(rand(1, 5))
            ->create();
    }
}
