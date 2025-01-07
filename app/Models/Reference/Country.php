<?php

namespace App\Models\Reference;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Znck\Eloquent\Traits\BelongsToThrough;

class Country extends Model
{
    use BelongsToThrough;

    protected $table = 'countries';

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function continent(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Continent::class, Region::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
