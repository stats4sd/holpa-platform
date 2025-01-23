<?php

namespace App\Models\Xlsforms;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\Team;
use App\Models\XlsformLanguages\Locale;
use App\Models\XlsformLanguages\XlsformModuleVersionLocale;
use App\Services\XlsformTranslationHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class XlsformTemplate extends OdkLinkXlsformTemplate
{
    use HasRelationships;

    // TODO: I think this overrides the booted method on HasXlsForms - ideally we wouldn't need to copy the package stuff here...
    protected static function booted(): void
    {

        // when the model is updated
        static::updated(function ($item) {

            // when xlsform_template.available change from false to true
            if ($item->available) {
                // get all teams
                $teams = Team::all();

                foreach ($teams as $team) {
                    // check if team has xlsform for this xlsform_template already
                    $xlsform = $team->xlsforms->where('xlsform_template_id', $item->id);

                    // if team does not have any xlsform for this xlsform_template, create a xlsform for this team
                    if (count($xlsform) == 0) {

                        $xlsform = Xlsform::create([
                            'owner_id' => $team->id,
                            'owner_type' => Team::class,
                            'xlsform_template_id' => $item->id,
                            'title' => $item->title,
                        ]);

                        $xlsformModule = XlsformModule::create([
                            'form_type' => 'App\Models\Xlsforms\Xlsform',
                            'form_id' => $xlsform->id,
                            'label' => $team->name . ' custom module',
                            'name' => $team->name . ' custom module',
                        ]);

                        XlsformModuleVersion::create([
                            'xlsform_module_id' => $xlsformModule->id,
                            'name' => 'custom',
                        ]);
                    }
                }
            }
        });
    }

    public function xlsformModules(): MorphMany
    {
        return $this->morphMany(XlsformModule::class, 'form');
    }

    public function xlsformModuleVersions(): HasManyThrough
    {
        return $this->hasManyThrough(XlsformModuleVersion::class, XlsformModule::class, 'form_id', 'xlsform_module_id')
            ->where('xlsform_modules.form_type', static::class);
    }

    public function surveyRows(): HasManyDeep
    {
        return $this->hasManyDeep(
            SurveyRow::class,
            [XlsformModule::class, XlsformModuleVersion::class],
            [['form_type', 'form_id'], null, 'xlsform_module_version_id']
        );
    }

    public function choiceLists(): HasManyDeep
    {
        return $this->hasManyDeep(
            ChoiceList::class,
            [XlsformModule::class, XlsformModuleVersion::class],
            [['form_type', 'form_id'], null, 'xlsform_module_version_id']
        );
    }

    public function choiceListEntries(): HasManyDeep
    {
        return $this->hasManyDeep(
            ChoiceListEntry::class,
            [XlsformModule::class, XlsformModuleVersion::class, ChoiceList::class],
            [['form_type', 'form_id'], null, 'xlsform_module_version_id', 'choice_list_id']
        );
    }

    // Split up language strings into 2 relationships as there are 2 paths between xlsformtemplates and language strings.
    public function surveyLanguageStrings(): HasManyDeep
    {
        return $this->hasManyDeep(
            LanguageString::class,
            [XlsformModule::class, XlsformModuleVersion::class, SurveyRow::class],
            [['form_type', 'form_id'], 'xlsform_module_id', 'xlsform_module_version_id', ['linked_entry_type', 'linked_entry_id']],
        );
    }

    public function choiceListEntryLanguageStrings(): HasManyDeep
    {
        return $this->hasManyDeep(
            LanguageString::class,
            [XlsformModule::class, XlsformModuleVersion::class, ChoiceList::class, ChoiceListEntry::class],
            [['form_type', 'form_id'], 'xlsform_module_id', 'xlsform_module_version_id', 'choice_list_id', ['linked_entry_type', 'linked_entry_id']],
        );
    }


    // for a template to be available in a locale, *every* module should be linked to that locale
    public function locales(): Attribute
    {
        return new Attribute(
            get: function (): Collection {

                // get set of locales for each default module version
                $locales = $this->xlsformModules->map(fn(XlsformModule $xlsformModule) => $xlsformModule
                    ->defaultXlsformVersion
                    ->locales
                );

                // get list of locales present for *every* module
                return $locales->reduce(function ($carry, $item) {
                    return $carry->intersect($item);
                }, $locales->first())
                    ->values();
            }
        );
    }

}
