<?php

namespace App\Models\Xlsforms;

use App\Models\Traits\IsLookupList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function xlsformModuleVersion(): BelongsTo
    {
        return $this->belongsTo(XlsformModuleVersion::class);
    }

}
