<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Program extends Model
{
    protected $table = 'programs';

    protected $guarded = ['id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
