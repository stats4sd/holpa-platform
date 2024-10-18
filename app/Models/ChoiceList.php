<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChoiceList extends Model
{
    protected $table = 'choice_lists';

    protected $casts = [
        'is_localisable' => 'boolean',
        'is_dataset' => 'boolean',
    ];

    public function choiceListEntries(): HasMany
    {
        return $this->hasMany(ChoiceListEntry::class);
    }

    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
    }
}
