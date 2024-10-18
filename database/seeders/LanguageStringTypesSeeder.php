<?php

namespace Database\Seeders;

use App\Models\LanguageStringType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageStringTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LanguageStringType::create(['name' => 'label']);
        LanguageStringType::create(['name' => 'hint']);
        LanguageStringType::create(['name' => 'image']);
        LanguageStringType::create(['name' => 'audio']);
        LanguageStringType::create(['name' => 'video']);
        LanguageStringType::create(['name' => 'constraint_message']);
        LanguageStringType::create(['name' => 'required_message']);
        LanguageStringType::create(['name' => 'guidance_hint']);
    }
}
