<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'url'];

    protected $appends = ['logo'];

    public function goods(): HasMany
    {
        return $this->hasMany(Good::class);
    }

    protected function logo(): Attribute
    {
        return Attribute::get(fn () => $this->hasMedia('logo') ? $this->getFirstMediaUrl('logo') : url('static/not-found.svg'));
    }
}
