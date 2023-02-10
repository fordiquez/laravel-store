<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (Storage::directories() as $directory) {
            Storage::deleteDirectory($directory);
        }

        $this->call([
            LocationSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
//            CategorySeeder::class,
//            GoodSeeder::class,
//            PromoCodeSeeder::class,
//            OrderSeeder::class,
        ]);
    }
}
