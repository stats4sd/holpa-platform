<?php

namespace App\Models\XlsformLanguages;

use App\Models\Xlsforms\LanguageString;
use App\Models\Xlsforms\XlsformModuleVersion;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Znck\Eloquent\Relations\BelongsToThrough;

class XlsformModuleVersionLocale extends Pivot
{

    use \Znck\Eloquent\Traits\BelongsToThrough;

    public function xlsformModule(): BelongsTo
    {
        return $this->belongsTo(XlsformModuleVersion::class);
    }

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }

    public function language(): BelongsToThrough
    {
        return $this->belongsToThrough(Language::class, Locale::class);
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
            get: fn() => $this->locale->is_default,
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
