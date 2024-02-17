<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::upsert([
            ['group' => 'site', 'name' => 'Site currency', 'key' => 'currency', 'value' => '₴'],
            ['group' => 'delivery', 'name' => 'Courier', 'key' => 'courier', 'value' => 130],
            ['group' => 'delivery', 'name' => 'Meest', 'key' => 'meest', 'value' => 40],
            ['group' => 'delivery', 'name' => 'UkrPoshta', 'key' => 'ukrposhta', 'value' => 30],
            ['group' => 'delivery', 'name' => 'Nova Poshta', 'key' => 'nova_poshta', 'value' => 60],
        ], ['group', 'key'], ['value']);
    }
}
