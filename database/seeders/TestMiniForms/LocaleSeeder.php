<?php

namespace Database\Seeders\TestMiniForms;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create en + es locales

        $localeEn = Language::firstWhere('iso_alpha2', 'en')
            ->locales()
            ->create([
                'is_default' => true,
            ]);

        $localeEs = Language::firstWhere('iso_alpha2', 'es')
            ->locales()
            ->create([
                'is_default' => true,
            ]);

        foreach (Team::all() as $team) {
            $team->locales()->sync([$localeEn, $localeEs]);
        }
    }
}
