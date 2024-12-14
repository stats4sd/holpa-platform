<?php

namespace App\Models\LookupTables;

use App\Models\Traits\CanBeHiddenFromContext;
use App\Models\XlsformTemplates\ChoiceList;
use App\Models\XlsformTemplates\ChoiceListEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;

class Crop extends ChoiceListEntry implements HasExtraProperties
{
    protected $table = 'choice_list_entries';


    protected static function booted(): void
    {
        // Crop is a subset of ChoiceListEntry
        static::addGlobalScope('is_crop', function (Builder $query) {
            $query->whereHas('choiceList', function (Builder $query) {
                $query->where('list_name', 'crops');
            });
        });
    }


    public static function getChoiceListName(): string
    {
        return 'crops';
    }

    public static function extraProperties(): Collection
    {
        return collect([
            'expected_yield' => [
                'name' => 'expected_yield',
                'label' => 'Enter the expected yield for this crop for farms in your context in kg/ha.',
                'hint' => 'This is used to calculate indicators related to crop performance. If this is left blank, then the calculation will use the median yield from your data for the calculation.',
            ],
            'recommended_fertilizer_use' => [
                'name' => 'recommended_fertilizer_use',
                'label' => 'Enter the recommended fertilizer use for this crop. in kg/ha',
                'hint' => 'This is used to calculate indicators related to fertilizer use and crop performance. If this is left blank, then the calculation will use the median value from your data for the calculation.',
            ],
        ]);
    }

    public function getCsvContentsForOdk(?WithXlsforms $team = null): array
    {
        if ($team) {
            $isRelevant = $this->isRemoved($team) ? 0 : 1;
        } else {
            $isRelevant = null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'is_in_context' => $isRelevant,
        ];
    }

}
