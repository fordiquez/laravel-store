<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'state_id', 'name', 'old_name', 'type', 'is_state_center', 'big_city', 'is_active'];

    protected $casts = [
        'is_state_center' => 'boolean',
        'big_city' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
