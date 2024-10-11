<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Prep\CropTableSeeder;
use Database\Seeders\Prep\UnitTableSeeder;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentOdkLink\Database\Seeders\PlatformSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if(app()->environment('local')) {
            $this->call([
                Test\TeamsTableSeeder::class,
                Test\TestUserSeeder::class,
            ]);
        }

        $this->call(PlatformSeeder::class);
        $this->call(LanguageTableSeeder::class);

        $this->call([
            CropTableSeeder::class,
            UnitTableSeeder::class,
        ]);

    }
}
