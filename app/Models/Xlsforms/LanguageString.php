<?php

namespace App\Models\Xlsforms;

use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\LanguageStringType;
use App\Models\XlsformLanguages\Locale;
use App\Models\XlsformLanguages\XlsformModuleVersionLocale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Znck\Eloquent\Relations\BelongsToThrough;

class LanguageString extends Model
{



    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $casts = [
        'updated_during_import' => 'boolean',
    ];

    // A language string is linked to either a SurveyRow or a ChoiceListEntry;
    public function linkedEntry(): MorphTo
    {
        return $this->morphTo('linked_entry');
    }

    public function languageStringType(): BelongsTo
    {
        return $this->belongsTo(LanguageStringType::class);
    }

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }

    public function language(): BelongsToThrough
    {
        return $this->belongsToThrough(Language::class, Locale::class);
    }
}
