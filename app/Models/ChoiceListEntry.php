<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChoiceListEntry extends Model
{
    protected $table = 'choice_list_entries';

    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(ChoiceList::class);
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'item');
    }

}
