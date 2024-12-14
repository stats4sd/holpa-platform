<?php

namespace Database\Seeders\Prep;

use App\Models\Language;
use App\Models\Locale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create default En locale
        Language::firstWhere('iso_alpha2', 'en')
            ->locales()
            ->create([]);


    }
}
