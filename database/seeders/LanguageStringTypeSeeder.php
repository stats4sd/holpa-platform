<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageStringTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languageStringTypes = [
            ['name' => 'label'],
            ['name' => 'hint'],
            ['name' => 'required_message'],
            ['name' => 'constraint_message'],
            ['name' => 'mediaimage'],
        ];

        DB::table('language_string_types')->insert($languageStringTypes);
    }
}
