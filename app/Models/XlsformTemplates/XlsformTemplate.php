<?php

namespace App\Models\XlsformTemplates;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\Locale;
use App\Models\XlsformModule;
use App\Models\XlsformTemplateLanguage;
use App\Services\XlsformTranslationHelper;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;

class XlsformTemplate extends OdkLinkXlsformTemplate implements WithXlsformFile
{

    public function xlsformModules(): MorphMany
    {
        return $this->morphMany(XlsformModule::class, 'form');
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


    public function surveyRows(): MorphMany
    {
        return $this->morphMany(SurveyRow::class, 'template');
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
