<?php

namespace Database\Seeders;

use Database\Seeders\TestTemplates\ChoiceListEntriesTableSeeder;
use Database\Seeders\TestTemplates\ChoiceListsTableSeeder;
use Database\Seeders\TestTemplates\LanguageStringsTableSeeder;
use Database\Seeders\TestTemplates\RequiredMediaTableSeeder;
use Database\Seeders\TestTemplates\SurveyRowsTableSeeder;
use Database\Seeders\TestTemplates\XlsformModulesTableSeeder;
use Database\Seeders\TestTemplates\XlsformModuleVersionLocaleTableSeeder;
use Database\Seeders\TestTemplates\XlsformModuleVersionsTableSeeder;
use Database\Seeders\TestTemplates\XlsformsTableSeeder;
use Database\Seeders\TestTemplates\XlsformTemplateSectionsTableSeeder;
use Database\Seeders\TestTemplates\XlsformTemplatesTableSeeder;
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
