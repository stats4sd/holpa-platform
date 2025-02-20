<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;

class LocalesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


// create en + es locales

        $localeEn = Language::firstWhere('iso_alpha2', 'en')
            ->locales()
            ->create([
                'is_default' => true,
            ]);

        foreach (Team::all() as $team) {
            $team->addLocale($localeEn);
        }


    }
}
