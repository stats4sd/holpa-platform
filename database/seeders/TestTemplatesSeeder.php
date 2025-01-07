<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (glob(database_path('seeders/TestTemplates/*.php')) as $file) {
            $class = 'Database\\Seeders\\Prep\\' . pathinfo($file, PATHINFO_FILENAME);
            $this->call($class);
        }
    }
}
