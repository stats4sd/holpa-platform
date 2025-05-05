<?php

namespace App\Models;

use App\Models\Holpa\LocalIndicator;
use App\Models\SampleFrame\Farm;
use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use Dom\Attr;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stats4sd\FilamentOdkLink\Models\Country;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Traits\HasXlsforms;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Stats4sd\FilamentTeamManagement\Models\Team as FilamentTeamManagementTeam;

class Team extends FilamentTeamManagementTeam implements HasMedia, WithXlsforms
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

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return parent::users()
            ->using(TeamMembership::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function admins(): BelongsToMany
    {
        return parent::admins()
            ->using(TeamMembership::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function members(): BelongsToMany
    {
        return parent::members()
            ->using(TeamMembership::class);
    }

    /** @return  HasMany<TeamMembership, $this> */
    public function teamMemberships(): HasMany
    {
        return $this->hasMany(TeamMembership::class);
    }

    /** @return HasMany<LocalIndicator, $this> */
    public function localIndicators(): HasMany
    {
        return $this->hasMany(LocalIndicator::class);
    }

    /** @return BelongsTo<Country, $this> */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /** @return HasMany<LocationLevel, $this> */
    public function locationLevels(): HasMany
    {
        return $this->hasMany(LocationLevel::class, 'owner_id');
    }

    /** @return HasMany<Location, $this> */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'owner_id');
    }

    /** @return HasMany<Farm, $this> */
    public function farms(): HasMany
    {
        return $this->hasMany(Farm::class, 'owner_id');
    }

    /** @return HasMany<Import, $this> */
    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

    // Customisations

    /** @return BelongsTo<XlsformModuleVersion, $this> */
    public function dietDiversityModuleVersion(): BelongsTo
    {
        return $this->belongsTo(XlsformModuleVersion::class, 'diet_diversity_module_version_id');
    }

    /** @return Attribute<string, never> */
    protected function languagesProgress(): Attribute
    {
        return new Attribute(
            get: function () {

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
            });

    }

    /** @return Attribute<string, never> */
    protected function samplingProgress(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->sampling_complete) {
                    return 'complete';
                }

                return $this->locationLevels()->exists() ? 'in_progress' : 'not_started';

            });
    }

    /** @return Attribute<string, never> */
    protected function PbaProgress(): Attribute
    {
        return new Attribute(
            get: function () {

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
            });

    }

    /** @return Attribute<string, never> */
    protected function lispProgress(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->lisp_complete) {
                    return 'complete';
                }

                return $this->localIndicators()->exists() ? 'in_progress' : 'not_started';

            }
        );
    }

    /** @return Attribute<string, never> */
    protected function pilotProgress(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->pilot_complete) {
                    return 'complete';
                }

                // $farm->household_form_completed + fieldwork_form_completed are only marked for 'live' submissions, so here we can just count if any submissions have come in.
                if ($this->farms->some(fn(Farm $farm) => $farm->submissions()->count() > 0)) {
                    return 'in_progress';
                }

                return 'not_started';
            }
        );
    }

    /** @return Attribute<string, never> */
    protected function dataCollectionProgress(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->data_collection_complete) {
                    return 'complete';
                }

                if($this->farms->some(fn(Farm $farm) => $farm->household_form_completed || $farm->fieldwork_form_completed)) {
                    return 'in_progress';
                }

                return 'not_started';
            }
        );
    }


    // For HOLPA, teams should automatically receive a version of all available XlsformTemplates.

    /** @return Attribute<bool, never> */
    protected function shouldReceiveAllXlsformTemplates(): Attribute
    {
        return new Attribute(
            get: fn(): bool => true,
        );
    }

    /** @return Attribute<boolean, never> */
    public function readyForLive(): Attribute
    {
        return new Attribute(
            get: fn(): bool => $this->languages_complete && $this->sampling_complete && $this->pba_complete && $this->lisp_complete,
        );
    }
}
