<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        collect(UserRole::getValues())
            ->filter(fn (string $role) => $role !== UserRole::SUPER_ADMIN)
            ->each(fn (string $role) => Role::create(['name' => $role, 'guard_name' => 'web']));

        User::where('id', '>', 1)->get()->each(fn (User $user) => $user->assignRole(UserRole::CUSTOMER));
    }
}
