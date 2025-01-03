<?php

namespace App\Models\Xlsforms;

use App\Models\Interfaces\HasLanguageStrings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class SurveyRow extends Model implements HasLanguageStrings
{

    use CascadesDeletes;

    protected array $cascadeDeletes = ['languageStrings'];

    protected $casts = [
        'properties' => 'collection',
        'required' => 'boolean',
        'updated_during_import' => 'boolean',
    ];

    public function xlsformModuleVersion(): BelongsTo
    {
        return $this->belongsTo(XlsformModuleVersion::class);
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }

    // default language strings
    public function defaultLabel(): Attribute
    {
        return new Attribute(
            get: fn() => $this->languageStrings()
                ->whereHas('language', fn($query) => $query->where('languages.iso_alpha2', 'en'))
                ->whereHas('languageStringType', fn($query) => $query->where('language_string_types.name', 'label'))
                ->first()?->text ?? ''// hardcode English for now. Later we can make it match the team's default language.
        );
    }

    public function defaultHint(): Attribute
    {
        return new Attribute(
            get: fn() => $this->languageStrings()
                ->whereHas('language', fn($query) => $query->where('languages.iso_alpha2', 'en'))
                ->whereHas('languageStringType', fn($query) => $query->where('language_string_types.name', 'hint'))
                ->first()?->text ?? ''// hardcode English for now. Later we can make it match the team's default language.
        );
    }


//    public function required(): Attribute
//    {
//        return new Attribute(
//            get: fn($value) => $value===1 ? 'TRUE' : '',
//            set: function ($value) {
//                return match (strtolower($value)) {
//                    'true', 'yes' => 1,
//                    default => 0,
//                };
//            }
//        );
//    }
}
