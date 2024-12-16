<?php

namespace App\Models\XlsformTemplates;

use App\Models\HasLanguageStrings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function template(): MorphTo
    {
        return $this->morphTo('template');
    }

    // if the survey row is linked to a module, which is the 'parent' or 'default' surveyrow?
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }


    public function required(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value,
            set: function($value) {
                return match(strtolower($value)) {
                    'true','yes' => true,
                    'false','no' => false,
                    default => false,
                };
            }
        );
    }
}
