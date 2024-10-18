<?php

namespace Database\Seeders\Test;

use App\Models\Program;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // create users
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $programAdmin = User::create([
            'name' => 'Test Program Admin',
            'email' => 'program_admin@example.com',
            'password' => bcrypt('password'),
        ]);


        // assign role to users
        $user->teams()->attach(Team::first());

        $admin->assignRole('Super Admin');

        $programAdmin->assignRole('Program Admin');
        $programAdmin->programs()->attach(Program::first());
    }
}
