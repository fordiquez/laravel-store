<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodProperty extends Model
{
    use HasFactory;

    protected $table = 'good_property';

    protected $fillable = ['property_id', 'good_id', 'value'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }
}
