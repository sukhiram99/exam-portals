<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create roles if not exist
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin']
        );

        Role::firstOrCreate(
            ['slug' => 'user'],
            ['name' => 'User']
        );

        // 2. Create or update the super admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // change if you want another email
            [
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'phone'    => '9898989988',
                'password' => Hash::make('Admin@123'), // change password if you want
            ]
        );

        // 3. Sync ONLY the Admin role (removes any other roles)
        $admin->roles()->sync([$adminRole->id]);

        // Optional: nice message in console
        $this->command->info('Admin user created/updated: admin@example.com / admin123');
        $this->command->info('Only "Admin" role is assigned.');
    }
}
