<?php

namespace App\Models;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\XlsformTemplates\ChoiceList;
use App\Models\XlsformTemplates\ChoiceListEntry;
use App\Models\XlsformTemplates\LanguageString;
use App\Models\XlsformTemplates\SurveyRow;
use App\Models\XlsformTemplates\XlsformTemplate;
use App\Services\XlsformTranslationHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class XlsformModuleVersion extends Model implements HasMedia, WithXlsformFile
{
    protected $table = 'xlsform_module_versions';

    protected $casts = [
        'is_default' => 'boolean',
    ];

    use InteractsWithMedia;

    // TODO: impliment WithXlsformDrafts to enable pyxform validation checks + module testing/drafts.

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('xlsform_file')
            ->singleFile()
            ->useDisk(config('filament-odk-link.storage.xlsforms'));
    }

    public function xlsformModule(): BelongsTo
    {
        return $this->belongsTo(XlsformModule::class);
    }

    // Which teams have selected to use this module instead of the default?
    // Linked Via chosen_modules
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'chosen_modules', 'xlsform_moddule_version_id', 'team_id');
    }



    // ** **** XLsform Components ********
    public function xlsfile(): Attribute
    {
        return new Attribute(
            get: fn(): string => $this->getFirstMediaPath('xlsform_file'),
        );
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
            ->where('choice_lists.template_type',static::class);
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
