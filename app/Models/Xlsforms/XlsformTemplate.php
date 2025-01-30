<?php

namespace App\Models\Xlsforms;

use App\Models\Team;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModule;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;

class XlsformTemplate extends OdkLinkXlsformTemplate
{
    // Custom HOLPA feature - when a template is updated, in addition to giving teams an Xlsform version of it, give them a custom module + module version.
    protected static function booted(): void
    {
        parent::booted();

        static::updated(function ($item) {

            if ($item->available) {

                $teams = Team::all();

                foreach ($teams as $team) {
                    // check if team has xlsform for this xlsform_template already
                    $xlsform = $team->xlsforms->where('xlsform_template_id', $item->id)->first();

                    $xlsformModule = XlsformModule::updateOrCreate([
                        'form_type' => 'App\Models\Xlsforms\Xlsform',
                        'form_id' => $xlsform->id,
                        'label' => $team->name . ' custom module',
                        'name' => $team->name . ' custom module',
                    ]);

                    XlsformModuleVersion::updateOrCreate([
                        'xlsform_module_id' => $xlsformModule->id,
                        'name' => 'custom',
                    ]);
                }
            }

        });
    }
}
