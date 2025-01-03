<?php

namespace App\Models\Xlsforms;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\Locale;
use App\Models\Team;
use App\Services\XlsformTranslationHelper;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class XlsformTemplate extends OdkLinkXlsformTemplate implements WithXlsformFile
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

    public function xlsformTemplateLanguages(): MorphMany
    {
        return $this->morphMany(XlsformTemplateLanguage::class, 'template');
    }

    public function languageStrings(): HasManyThrough
    {
        return $this->hasManyThrough(LanguageString::class, XlsformTemplateLanguage::class, 'template_id', 'xlsform_template_language_id', 'id', 'id')
            ->where('xlsform_template_languages.template_type', static::class);
    }


    public function surveyRows(): HasManyDeep
    {
        return $this->hasManyDeep(
            SurveyRow::class,
            [XlsformModule::class, XlsformModuleVersion::class],
            [['form_type', 'form_id'], null, ['template_type', 'template_id']]
        );
    }

    public function choiceLists(): MorphMany
    {
        return $this->morphMany(ChoiceList::class, 'template');
    }

    public function choiceListEntries(): HasManyThrough
    {
        return $this->hasManyThrough(ChoiceListEntry::class, ChoiceList::class, 'template_id', 'choice_list_id', 'id', 'id')
            // reference: https://stackoverflow.com/questions/43285779/laravel-polymorphic-relations-has-many-through
            ->where('choice_lists.template_type', static::class);
    }

    // ensure that the XlsformTemplate has a language for each language in the xlsform uploaded
    // returns the collection of XlsformTemplateLanaguage entries that will be updated from the file.
    public function setXlsformTemplateLanguages(Collection $translatableHeadings): Collection
    {
        $languages = $translatableHeadings
            ->map(fn(string $heading) => (new XlsformTranslationHelper())->getLanguageFromColumnHeader($heading))
            ->unique();

        return $languages->map(function ($language) {


            $templateLanguage = $this->xlsformTemplateLanguages()
                ->whereHas('locale', fn($query) => $query->whereNull('description')) // only languages that were imported from the xlsform - any created through the platform have a description.
                ->firstOrCreate(['language_id' => $language->id]);

            $locale = Locale::firstOrCreate(['description' => null, 'language_id' => $language->id]);

            $templateLanguage->locale()->associate($locale);
            $templateLanguage->save();

            return $templateLanguage;
        });
    }
}
