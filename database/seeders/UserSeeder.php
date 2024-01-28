<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\OrderRecipientFactory;
use Database\Factories\UserAddressFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = UserFactory::new()
            ->has(OrderRecipientFactory::new()->count(rand(1, 3)))
            ->has(UserAddressFactory::new()->count(rand(1, 3)), 'addresses')
            ->create([
                'first_name' => 'Gerald',
                'last_name' => 'Ford',
                'birth_date' => '2001-02-22',
                'gender' => 'male',
                'email' => User::ADMIN_EMAIL,
                'password' => Hash::make('brandford22'),
            ]);

        $user->addAvatarMedia(config('services.multiavatar.url') . $user->getFilamentName() . '.svg?apikey=' . config('services.multiavatar.key'));
    }
}
