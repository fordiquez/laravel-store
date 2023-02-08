<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'state_id', 'name', 'old_name', 'type', 'is_state_center', 'big_city', 'is_active'];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function streets(): HasMany
    {
        return $this->hasMany(Street::class);
    }
}
