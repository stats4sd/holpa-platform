<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Prep\CropTableSeeder;
use Database\Seeders\Prep\UnitTableSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;
use Stats4sd\FilamentOdkLink\Database\Seeders\PlatformSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RoleAndPermissionSeeder::class);

        if (app()->environment('local')) {
            $this->call([
                Test\TeamsTableSeeder::class,
                Test\ProgramsTableSeeder::class,
                Test\TestUserSeeder::class,
            ]);
        }

        $this->call(LanguageSeeder::class);
        $this->call(LanguageStringTypesSeeder::class);
        $this->call(PlatformSeeder::class);
    }
}
