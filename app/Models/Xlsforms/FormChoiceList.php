<?php

namespace App\Models\Xlsforms;

use App\Models\Traits\IsLookupList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormChoiceList extends Model
{
    use IsLookupList;

    protected $casts = [
        'properties' => 'collection',
    ];

    public function choiceListEntries(): HasMany
    {
        return $this->hasMany(FormChoiceListEntry::class);
    }
}
