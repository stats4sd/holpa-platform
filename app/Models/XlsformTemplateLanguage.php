<?php

namespace App\Models;

use App\Models\Locale;
use App\Models\Language;
use App\Models\LanguageString;
use App\Models\XlsformTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class XlsformTemplateLanguage extends Model
{
    use HasFactory;

    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
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
