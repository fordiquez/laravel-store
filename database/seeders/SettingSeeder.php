<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'section' => 'site',
            'name' => 'Site currency',
            'key' => 'currency',
            'value' => '₴',
        ]);
    }
}
