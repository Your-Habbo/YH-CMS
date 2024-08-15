<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (only if they don't already exist)
        $permissions = [
            'view admin dashboard',
            'manage forum',
            'create forum threads',
            'edit forum threads',
            'delete forum threads',
            'create forum posts',
            'edit forum posts',
            'delete forum posts',
            'manage radio',
            'view radio analytics',
            'manage events',
            'create events',
            'edit events',
            'delete events',
            'manage news',
            'create news',
            'edit news',
            'delete news',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
        
        Role::firstOrCreate(['name' => 'member']);

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $forumModeratorRole = Role::firstOrCreate(['name' => 'forum moderator']);
        $forumModeratorRole->syncPermissions([
            'manage forum',
            'create forum threads',
            'edit forum threads',
            'delete forum threads',
            'create forum posts',
            'edit forum posts',
            'delete forum posts',
        ]);

        $radioDJRole = Role::firstOrCreate(['name' => 'radio dj']);
        $radioDJRole->syncPermissions([
            'manage radio',
            'view radio analytics',
        ]);

        $eventManagerRole = Role::firstOrCreate(['name' => 'event manager']);
        $eventManagerRole->syncPermissions([
            'manage events',
            'create events',
            'edit events',
            'delete events',
        ]);

        $newsManagerRole = Role::firstOrCreate(['name' => 'news manager']);
        $newsManagerRole->syncPermissions([
            'manage news',
            'create news',
            'edit news',
            'delete news',
        ]);

        // Create cosmetic roles without any permissions
        Role::firstOrCreate(['name' => 'owner']);
        Role::firstOrCreate(['name' => 'developer']);
        Role::firstOrCreate(['name' => 'website manager']);
        

        // Assign the admin role to the first user (make sure Spatie's HasRoles trait is used in the User model)
        $user = \App\Models\User::find(1); // Adjust user ID as necessary
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
