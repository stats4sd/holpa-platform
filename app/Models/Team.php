<?php

namespace App\Models;

use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Traits\HasXlsForms;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;
use Stats4sd\FilamentTeamManagement\Models\Team as FilamentTeamManagementTeam;

class Team extends FilamentTeamManagementTeam implements WithXlsforms
{
    use HasXlsForms;

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

            // create empty interpretation entries for the team:
            // TODO: this probably is not great, and we should not require a bunch of empty entries!

            // Below are tape-data-system specific business logic, HOPLA may have something similar.
            // Temporary keep it for reference first. We can remove them after confirming we do not need them.

            /*
            $interpretations = CaetIndex::all()->map(fn ($index) => [
               'owner_id' => $owner->id,
               'owner_type' => static::class,
               'caet_index_id' => $index->id,
               'interpretation' => '',
           ])->toArray();

           $owner->caetInterpretations()->createMany($interpretations);

           $owner->locationLevels()->create(['name' => 'Top level (rename)', 'has_farms' => 0, 'top_level' => 1, 'slug' =>'site-level']);
           */
        });
    }

    public function localIndicators(): HasMany
    {
        return $this->hasMany(LocalIndicator::class);
    }

    public function locationLevels(): MorphMany
    {
        return $this->morphMany(LocationLevel::class, 'owner');
    }

    public function locations(): MorphMany
    {
        return $this->morphMany(Location::class, 'owner');
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }
}
