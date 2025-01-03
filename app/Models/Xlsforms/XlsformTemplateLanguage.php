<?php

namespace App\Models\Xlsforms;

use App\Models\Language;
use App\Models\Locale;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class XlsformTemplateLanguage extends Model
{

    public function template(): MorphTo
    {
        return $this->morphTo();
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }

    public function languageStrings(): HasMany
    {
        return $this->hasMany(LanguageString::class);
    }

    public function getLocaleLanguageLabelAttribute()
    {
        return $this->locale->languageLabel;
    }

    // was this created from importing an Xlsform template file?
    // if false, then this it was created through the platform as an extra translation
    public function isAddedFromXlsformTemplate(): Attribute
    {
        return new Attribute(
            get: fn() => $this->locale->description === null,
        );
    }

    public function getStatusAttribute()
    {
        if($this->has_language_strings && !$this->needs_update) {
            return 'Ready for use';
        }
        elseif(!$this->has_language_strings) {
            return 'Not added';
        }
        elseif($this->has_language_strings && $this->needs_update) {
            return 'Out of date';
        }
    }
}
