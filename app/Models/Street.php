<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'city_id',
        'name',
        'old_name'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
