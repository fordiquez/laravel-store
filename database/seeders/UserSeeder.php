<?php

namespace Database\Seeders;

use Database\Factories\OrderRecipientFactory;
use Database\Factories\UserAddressFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        UserFactory::new()
            ->has(OrderRecipientFactory::new()->count(rand(1, 3)))
            ->has(UserAddressFactory::new()->count(rand(1, 3)))
            ->create([
            'first_name' => 'Gerald',
            'last_name' => 'Ford',
            'birth_date' => '2001-02-22',
            'gender' => 'male',
//            'role_id' => 1,
            'email' => 'fordiquez@store.com',
            'password' => '$2y$10$Y.tltioAYrGTul6J8GeDoOqjv/98LM8iSj4PCIDsVYkE3KWFah.lC'
        ]);

        UserFactory::new()->count(1)
            ->has(OrderRecipientFactory::new()->count(rand(1, 3)))
            ->has(UserAddressFactory::new()->count(rand(1, 3)))
            ->create();
    }
}
