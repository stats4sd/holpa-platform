<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlobalIndicator extends Model
{
    protected $table = 'global_indicators';

    protected $guarded = ['id'];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function localIndicators(): HasMany
    {
        return $this->hasMany(LocalIndicator::class);
    }
}
