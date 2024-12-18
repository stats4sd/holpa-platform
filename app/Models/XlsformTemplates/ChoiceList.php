<?php

namespace App\Models\XlsformTemplates;

use App\Models\Traits\IsLookupList;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

    public function template(): MorphTo
    {
        return $this->morphTo();
    }

}
