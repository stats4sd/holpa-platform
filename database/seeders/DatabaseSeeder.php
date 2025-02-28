<?php

namespace Database\Seeders;

use Database\Seeders\TestRealForms\ChoiceListsTableSeeder;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentOdkLink\Database\Seeders\PlatformSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ODK Platform setup
        $this->call(PlatformSeeder::class);
        $this->call(Un49LocationSeeder::class);

        // call the prep seeders always.
        foreach (glob(database_path('seeders/Prep/*.php')) as $file) {
            $class = 'Database\\Seeders\\Prep\\' . pathinfo($file, PATHINFO_FILENAME);
            $this->call($class);
            $this->call(ChoiceListsTableSeeder::class);
    }

        // Call the test seeders locally
        if (app()->environment('local')) {
            foreach (glob(database_path('seeders/Test/*.php')) as $file) {
                $class = 'Database\\Seeders\\Test\\' . pathinfo($file, PATHINFO_FILENAME);
                $this->call($class);
            }
        }
    }
}
