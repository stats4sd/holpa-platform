<?php

namespace App\Models\SurveyData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use App\Models\Traits\HasLinkedDataset;

class FarmSurveyData extends Model
{
    use HasLinkedDataset;

    protected $table = 'farm_survey_data';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'id');
    }

    // Link to repeat groups

    public function fishes(): HasMany
    {
        return $this->hasMany(Fish::class, 'farm_survey_data_id', 'id');
    }

    public function fishUses(): HasMany
    {
        return $this->hasMany(FishUse::class, 'farm_survey_data_id', 'id');
    }
}
