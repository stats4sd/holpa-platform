<?php

namespace App\Models\XlsformTemplates;

use App\Models\HasLanguageStrings;
use App\Models\Traits\CanBeHiddenFromContext;
use App\Models\Traits\IsLookupList;
use Dom\Attr;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(ChoiceList::class);
    }

    public function xlsformTemplate(): BelongsToThrough
    {
        return $this->belongsToThrough(XlsformTemplate::class, ChoiceList::class);
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }

}
