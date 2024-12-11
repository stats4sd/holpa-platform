<?php

namespace App\Models\Xlsforms;

use App\Models\HasLanguageStrings;
use App\Models\Traits\IsLookupList;
use App\Models\XlsformTemplates\LanguageString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FormChoiceListEntry extends Model implements HasLanguageStrings
{
    use IsLookupList;

    protected $casts = [
        'is_localisable' => 'boolean',
        'is_dataset' => 'boolean',
        'properties' => 'collection',
        'updated_during_import' => 'boolean',
    ];

    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(FormChoiceList::class);
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }
}
