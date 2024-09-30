<?php

namespace Database\Seeders\Test;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::create(['name' => 'Super Admin']);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'latest_team_id' => 1,
        ]);

        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'latest_team_id' => NULL,
        ]);

        $admin->assignRole('Super Admin');
    }
}
