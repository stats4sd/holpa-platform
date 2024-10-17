<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $programAdminRole = Role::create(['name' => 'Program Admin']);


        // create permissions
        $permissions = [
            ['name' => 'access admin panel'],
            ['name' => 'access program admin panel'],
            ['name' => 'view all prorgrams'],
            ['name' => 'view all teams'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $programAdminRole->givePermissionTo('access program admin panel');
    }
}
