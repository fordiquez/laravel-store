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
        $goods = Good::all();
        $orders = OrderFactory::new()
            ->count(10)
            ->has(OrderHistory::factory()->count(rand(1, 3)))
            ->create();

        if ($goods->count()) {
            $orders->each(function ($order) use ($goods) {
                $order->items()->attach(
                    $goods
                        ->random(rand(1, $goods->count() > 1 ? $goods->count() / 2 : $goods->count()))
                        ->pluck('id')
                        ->toArray(),
                );
            });
        }
    }
}
