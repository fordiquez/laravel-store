<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function propertyValues(): HasMany
    {
        return $this->hasMany(PropertyValue::class);
    }
}
