<?php

namespace App\Models;

use App\Models\Language;
use App\Models\LanguageString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

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

    public function languageStrings(): HasMany
    {
        return $this->hasMany(LanguageString::class);
    }
}
