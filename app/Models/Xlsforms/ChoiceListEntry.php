<?php

namespace App\Models\Xlsforms;

use App\Models\Interfaces\HasLanguageStrings;
use App\Models\Team;
use App\Models\Traits\CanBeHiddenFromContext;
use App\Models\Traits\IsLookupList;
use App\Services\HelperService;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Znck\Eloquent\Relations\BelongsToThrough;

class ChoiceListEntry extends Model implements HasLanguageStrings
{
    use IsLookupList;
    use CanBeHiddenFromContext;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $casts = [
        'is_localisable' => 'boolean',
        'is_dataset' => 'boolean',
        'properties' => 'collection',
        'updated_during_import' => 'boolean',
    ];

    protected static function booted()
    {
        // top-level scoping to ensure teams only receive their entries
        static::addGlobalScope('team', function (Builder $query) {

            if (Filament::hasTenancy() && $team = HelperService::getSelectedTeam()) {
                $query->whereHasMorph('owner', Team::class, function (Builder $query) use ($team) {
                    $query->where('id', $team->id);
                })
                ->orWhereNull('owner_id');

            }

        });
    }

    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(ChoiceList::class);
    }

    public function xlsformModuleVersion(): BelongsToThrough
    {
        return $this->belongsToThrough(XlsformModuleVersion::class, ChoiceList::class);
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }

    // Some choice lists are linked to specific data models to let us add custom information.
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

}
