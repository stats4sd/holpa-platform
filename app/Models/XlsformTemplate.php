<?php

namespace App\Models;

use App\Models\SurveyRow;
use App\Models\XlsformTemplateLanguage;
use App\Services\XlsformTranslationHelper;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;

class XlsformTemplate extends OdkLinkXlsformTemplate
{

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }

    public function languageStrings(): HasManyThrough
    {
        return $this->hasManyThrough(LanguageString::class, XlsformTemplateLanguage::class);
    }


    public function surveyRows(): HasMany
    {
        return $this->hasMany(SurveyRow::class);
    }

    public function choiceLists(): HasMany
    {
        return $this->hasMany(ChoiceList::class);
    }

    public function choiceListEntries(): HasManyThrough
    {
        return $this->hasManyThrough(ChoiceListEntry::class, ChoiceList::class);
    }

    // ensure that the XlsformTemplate has a language for each language in the xlsform uploaded
    public function setXlsformTemplateLanguages(Collection $translatableHeadings): void
    {
        $languages = $translatableHeadings
            ->map(fn(string $heading) => (new XlsformTranslationHelper())->getLanguageFromColumnHeader($heading))
            ->unique();

        $languages->each(function ($language) {


            $templateLanguage = $this->xlsformTemplateLanguages()
                ->whereHas('locale', fn($query) => $query->whereNull('description')) // only languages that were imported from the xlsform - any created through the platform have a description.
                ->firstOrCreate(['language_id' => $language->id]);

            $locale = Locale::firstOrCreate(['description' => null, 'language_id' => $language->id]);

            $templateLanguage->locale()->associate($locale);
            $templateLanguage->save();

        });
    }

}
