<?php

namespace Database\Seeders;

use Database\Seeders\TestMiniForms\DatasetsTableSeeder;
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
            $this->call(AppUsersTableSeeder::class);
        $this->call(ChoiceListEntriesTableSeeder::class);
        $this->call(ChoiceListsTableSeeder::class);
        $this->call(DatasetsTableSeeder::class);
        $this->call(LanguageStringsTableSeeder::class);
        $this->call(LocalesTableSeeder::class);
        $this->call(MediaTableSeeder::class);
        $this->call(OdkProjectsTableSeeder::class);
        $this->call(RequiredMediaTableSeeder::class);
        $this->call(SurveyRowsTableSeeder::class);
        $this->call(XlsformModulesTableSeeder::class);
        $this->call(XlsformModuleVersionLocaleTableSeeder::class);
        $this->call(XlsformModuleVersionsTableSeeder::class);
        $this->call(XlsformsTableSeeder::class);
        $this->call(XlsformTemplateSectionsTableSeeder::class);
        $this->call(XlsformTemplatesTableSeeder::class);
        $this->call(XlsformVersionsTableSeeder::class);
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
