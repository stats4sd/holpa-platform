<?php

namespace App\Models;

use App\Models\Traits\IsLookupList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChoiceListEntry extends Model implements HasLanguageStrings
{
    use IsLookupList;


    protected $casts = [
        'is_localisable' => 'boolean',
        'is_dataset' => 'boolean',
        'properties' => 'collection',
    ];

    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(ChoiceList::class);
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }

}
