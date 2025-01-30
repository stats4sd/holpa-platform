<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

// Run this when you want to use the prebuilt ODK Central projects and forms etc for local testing.
// It will set up all the platform-side database requirements (xlsform_templates, survey_rows, etc) with TestTemplateSeeder
// *AND THEN* also add the links to ODK Central and the media files required to actually deploy and test the ODK forms.
class TestOdkStuffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->call(TestTemplatesSeeder::class);

        // set foreign key constraints to 0
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach (glob(database_path('seeders/TestOdkStuff/*.php')) as $file) {
            $class = 'Database\\Seeders\\TestOdkStuff\\' . pathinfo($file, PATHINFO_FILENAME);
            $this->call($class);
        }

        // reset foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
