<?php

namespace App\Models\XlsformTemplates;

use App\Models\Traits\IsLookupList;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ChoiceList extends Model
{
    use IsLookupList;

    protected $casts = [
        'properties' => 'collection',
        'can_be_hidden_from_context' => 'boolean',
        'is_localisable' => 'boolean',
    ];

    public function choiceListEntries(): HasMany
    {
        return $this->hasMany(ChoiceListEntry::class);
    }

    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
    }

    public function hasCustomHandling(): Attribute
    {
        return new Attribute(
            get: fn() => $this->getCustomListNames()->contains($this->list_name),
        );
    }

    // a set of list_names to ignore on regular localisable processing
    public function getCustomListNames(): Collection
    {
        return collect([
            'district',
            'sub_district',
            'village',
            'farm',
            'language',
        ]);
    }
}
