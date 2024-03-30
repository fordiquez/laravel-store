<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Storage::directories() as $directory) {
            Storage::deleteDirectory($directory);
        }

        Cache::flush();

        $this->call([
            SettingSeeder::class,
            LocationSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            GoodSeeder::class,
            PromoCodeSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
