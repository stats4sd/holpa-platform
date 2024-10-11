<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Language extends Model
{

    // gets the column header for use in XLSforms (e.g. the "English (en)" part of "label::English (en)")
    public function columnHeader(): Attribute
    {
        return new Attribute(
            get: fn (): string => Str::ascii($this->label) . ' (' . $this->code . ')',
        );
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
