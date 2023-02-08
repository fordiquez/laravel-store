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
        $storageDirectories = ['avatars', 'categories', 'flags', 'goods', 'reviews'];

        foreach ($storageDirectories as $storageDirectory) {
            if (Storage::directoryExists($storageDirectory)) Storage::deleteDirectory($storageDirectory);
        }

        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            StreetSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
//            GoodSeeder::class,
//            PromoCodeSeeder::class,
//            OrderSeeder::class,
        ]);
    }
}
