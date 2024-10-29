<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GlobalIndicator extends Model
{
    protected $table = 'global_indicators';

    protected $guarded = ['id'];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function localIndicators(): BelongsToMany
    {
        return $this->belongsToMany(LocalIndicator::class)->withTimestamps();
    }
}
