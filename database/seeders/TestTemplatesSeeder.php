<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestTemplatesSeeder extends Seeder
{
    public function run(): void
    {

        // set foreign key constraints to 0
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach (glob(database_path('seeders/TestTemplates/*.php')) as $file) {
            $class = 'Database\\Seeders\\TestTemplates\\' . pathinfo($file, PATHINFO_FILENAME);
            $this->call($class);
        }

        // reset foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
