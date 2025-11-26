<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Permission,Role,User};

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/RolePermissionSeeder.php
public function run(): void
{
    // Create Permissions
    $permissions = [
        'view-users', 'create-users', 'edit-users', 'delete-users',
        'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
        'view-dashboard',
    ];

    foreach ($permissions as $perm) {
        Permission::firstOrCreate([
            'slug' => $perm
        ], [
            'name' => ucwords(str_replace('-', ' ', $perm)),
        ]);
    }

    // Create Roles
    $admin = Role::create([
        'name' => 'Admin',
        'slug' => 'admin',
        'description' => 'Full access'
    ]);

    $editor = Role::create([
        'name' => 'User',
        'slug' => 'user',
    ]);

    // Assign all permissions to Admin
    $admin->permissions()->attach(Permission::all()->pluck('id'));

    // Assign limited permissions to Editor
    $editor->permissions()->attach(
        Permission::whereIn('slug', ['view-users', 'view-dashboard'])->pluck('id')
    );

    // Assign Admin role to user ID 1 (or create a new user)
    $user = User::find(1);
    if ($user) {
        $user->roles()->attach($admin->id);
    }
}

}
