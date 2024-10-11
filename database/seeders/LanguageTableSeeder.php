<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::create(['label' => 'English', 'code' => 'en']);
        Language::create(['label' => 'Español', 'code' => 'es']);
        Language::create(['label' => 'Français', 'code' => 'fr']);
        Language::create(['label' => 'Kiswahili', 'code' => 'sw']);
    }
}
