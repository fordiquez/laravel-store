<?php

namespace Database\Seeders;

use App\Models\State;
use Database\Factories\CityFactory;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        CityFactory::new()->createMany([
            [
                'name' => 'Mykolaiv',
                'state_id' => State::whereName('Mykolaivska oblast')->value('id'),
                'is_state_center' => true,
            ],
            [
                'name' => 'Odesa',
                'state_id' => State::whereName('Odeska oblast')->value('id'),
                'is_state_center' => true,
                'big_city' => true,
            ],
        ]);
    }
}
