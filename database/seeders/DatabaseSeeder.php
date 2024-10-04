<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentOdkLink\Database\Seeders\PlatformSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Test\TestUserSeeder::class,
        ]);

        $this->call(PlatformSeeder::class);
    }
}
