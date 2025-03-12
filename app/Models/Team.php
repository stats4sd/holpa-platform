<?php

namespace App\Models;

use App\Models\Holpa\LocalIndicator;
use App\Models\SampleFrame\Farm;
use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stats4sd\FilamentOdkLink\Models\Country;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Traits\HasXlsforms;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentTeamManagement\Models\Team as FilamentTeamManagementTeam;

class Team extends FilamentTeamManagementTeam implements WithXlsforms, HasMedia
{
    use HasXlsforms;
    use InteractsWithMedia;

    protected $table = 'teams';

    protected $appends = ['odk_qr_code'];

    protected $casts = [
        'lisp_complete' => 'boolean',
        'sampling_complete' => 'boolean',
        'languages_complete' => 'boolean',
        'pba_complete' => 'boolean',
    ];

    protected static function booted(): void
    {

        // when the model is created; automatically create an associated project on ODK Central and a top location level;
        static::created(static function (self $owner) {

            // all teams get a default locale of english
            $en = Language::where('iso_alpha2', 'en')->first();

            $owner->languages()->sync($en->id, false);

            // create xlsform models for all active xlsform template for this newly created team
            $xlsformTemplates = XlsformTemplate::where('available', 1)->get();

            // suppose a newly created team does not have any xlsform, it is not necessary to do checking
            foreach ($xlsformTemplates as $xlsformTemplate) {
                Xlsform::create([
                    'owner_id' => $owner->id,
                    'xlsform_template_id' => $xlsformTemplate->id,
                    'title' => $xlsformTemplate->title,
                ]);
            }

            // manually set the default time_frame
            $owner->time_frame = 'in the last 12 months';
            $owner->save();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('local_indicators')
            ->singleFile();

        $this->addMediaCollection('custom_questions')
            ->singleFile();

    }

    public function localIndicators(): HasMany
    {
        return $this->hasMany(LocalIndicator::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function locationLevels(): HasMany
    {
        return $this->hasMany(LocationLevel::class, 'owner_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'owner_id');
    }

    public function farms(): HasMany
    {
        return $this->hasMany(Farm::class, 'owner_id');
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }


    // Customisations

    public function dietDiversityModuleVersion(): BelongsTo
    {
        return $this->belongsTo(XlsformModuleVersion::class, 'diet_diversity_module_version_id');
    }

    public function getXlsformHhModuleVersionAttribute()
    {
        $xlsform_HH = $this->xlsforms()->first(); // Get the first XLSForm belong to this team (hh)

        if ($xlsform_HH) {
            $xlsform_HH_custom_module = $xlsform_HH->xlsformModules->first(); // Get the first module
            if ($xlsform_HH_custom_module) {
                return $xlsform_HH_custom_module->xlsformModuleVersions()->first(); // Get the first version
            }
        }
        return null;
    }

    public function getXlsformFwModuleVersionAttribute()
    {
        $xlsform_FW = $this->xlsforms()->skip(1)->first(); // Get the second XLSForm belong to this team (fw)

        if ($xlsform_FW) {
            $xlsform_FW_custom_module = $xlsform_FW->xlsformModules->first(); // Get the first module
            if ($xlsform_FW_custom_module) {
                return $xlsform_FW_custom_module->xlsformModuleVersions()->first(); // Get the first version
            }
        }
        return null;
    }

    public function getLispProgressAttribute(): string
    {
        if ($this->lisp_complete) {
            return 'complete';
        }

        return $this->localIndicators()->exists() ? 'in_progress' : 'not_started';
    }

    public function getSamplingProgressAttribute(): string
    {
        if ($this->sampling_complete) {
            return 'complete';
        }

        return $this->locationLevels()->exists() ? 'in_progress' : 'not_started';
    }

    public function getLanguagesProgressAttribute(): string
    {
        if ($this->languages_complete) {
            return 'complete';
        }

        // teams have English as a language as default, check for others
        $englishLanguageId = Language::where('name', 'English')->first()->id;

        $hasAddedLanguages = $this->languages()
            ->where('language_id', '!=', $englishLanguageId)
            ->exists();

        $hasCountry = $this->country()->exists();

        return $hasAddedLanguages || $hasCountry ? 'in_progress' : 'not_started';
    }

    public function getPbaProgressAttribute(): string
    {
        if ($this->pba_complete) {
            return 'complete';
        }

        if (
            $this->time_frame !== null ||
            $this->diet_diversity_module_version_id !== null ||
            $this->choiceListEntries()->exists()
        ) {
            return 'in_progress';
        }

        return 'not_started';
    }

    // For HOLPA, teams should automatically receive a version of all available XlsformTemplates.
    public function shouldReceiveAllXlsformTemplates(): Attribute
    {
        return new Attribute(
            get: fn(): bool => true,
        );
    }

}
