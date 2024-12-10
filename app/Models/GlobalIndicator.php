<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalIndicator extends Model

{
    use HasFactory;

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
