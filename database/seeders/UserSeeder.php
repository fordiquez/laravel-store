<?php

namespace Database\Seeders;

use Database\Factories\OrderRecipientFactory;
use Database\Factories\UserAddressFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = UserFactory::new()
//            ->has(OrderRecipientFactory::new()->count(rand(1, 3)))
//            ->has(UserAddressFactory::new()->count(rand(1, 3)))
            ->create([
                'first_name' => 'Gerald',
                'last_name' => 'Ford',
                'birth_date' => '2001-02-22',
                'gender' => 'male',
                'email' => 'fordiquez@store.com',
                'password' => '$2y$10$Y.tltioAYrGTul6J8GeDoOqjv/98LM8iSj4PCIDsVYkE3KWFah.lC',
            ]);

        $avatarUrl = config('services.multiavatar.url') . $user->getFilamentName() . '.svg?apikey=' . config('services.multiavatar.key');
        try {
            $user->addMediaFromUrl($avatarUrl)
                ->sanitizingFileName(fn($fileName) => strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName)))
                ->toMediaCollection('avatars', 'public');
        } catch (FileCannotBeAdded) {}
    }
}
