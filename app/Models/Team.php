<?php

namespace App\Models;

use App\Models\Locale;
use App\Models\SampleFrame\Farm;
use App\Models\XlsformTemplates\ChoiceList;
use Hoa\Compiler\Llk\Rule\Choice;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use App\Models\Xlsforms\Xlsform;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Traits\HasXlsForms;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;
use Stats4sd\FilamentTeamManagement\Models\Team as FilamentTeamManagementTeam;

class Team extends FilamentTeamManagementTeam implements WithXlsforms, HasMedia
{
    use HasXlsForms;
    use InteractsWithMedia;

    protected $table = 'teams';

    protected $appends = ['odk_qr_code'];

    // TODO: I think this overrides the booted method on HasXlsForms - ideally we wouldn't need to copy the package stuff here...
    protected static function booted(): void
    {

        // when the model is created; automatically create an associated project on ODK Central and a top location level;
        static::created(static function ($owner) {

            // check if we are in local-only (no-ODK link) mode
            $odkLinkService = app()->make(OdkLinkService::class);
            if (config('filament-odk-link.odk.url') !== null && config('filament-odk-link.odk.url') !== '') {
                $owner->createLinkedOdkProject($odkLinkService, $owner);
            }

            // all teams get a default locale of english
            $en = Locale::whereHas('language', fn(Builder $query) => $query->where('iso_alpha2', 'en'))->first();

            $owner->locales()->attach($en);


        });
    }

    public function localIndicators(): HasMany
    {
        return $this->hasMany(LocalIndicator::class);
    }

    public function locales(): BelongsToMany
    {
        return $this->belongsToMany(Locale::class, 'locale_team', 'team_id', 'locale_id');
    }

    public function locationLevels(): MorphMany
    {
        return $this->morphMany(LocationLevel::class, 'owner');
    }

    public function locations(): MorphMany
    {
        return $this->morphMany(Location::class, 'owner');
    }

    public function farms(): MorphMany
    {
        return $this->morphMany(Farm::class, 'owner');
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

    public function xlsforms(): MorphMany
    {
        return $this->morphMany(Xlsform::class, 'owner');
    }

    public function choiceLists(): BelongsToMany
    {
        return $this->belongsToMany(ChoiceList::class, 'choice_list_team', 'team_id', 'choice_list_id')
            ->withPivot('is_complete');
    }

    public function markLookupListAsComplete(ChoiceList $choiceList): ?bool
    {
        $this->choiceLists()->sync([$choiceList->id => ['is_complete' => 1]], detaching: false);

        return $this->hasCompletedLookupList($choiceList);
    }

    public function markLookupListAsInComplete(ChoiceList $choiceList): ?bool
    {
        $this->choiceLists()->detach($choiceList->id);

        return $this->hasCompletedLookupList($choiceList);
    }

    public function hasCompletedLookupList(ChoiceList $choiceList): ?bool
    {
        return $this->choiceLists()->where('choice_lists.id', $choiceList->id)->first()?->pivot->is_complete;
    }
}
