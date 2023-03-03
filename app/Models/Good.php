<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Good extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'vendor_code',
        'title',
        'slug',
        'category_id',
        'brand_id',
        'description',
        'short_description',
        'warning_description',
        'old_price',
        'price',
        'quantity',
        'status',
    ];

    protected $appends = ['preview'];

    protected $with = ['category'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'good_tag', 'good_id', 'tag_id');
    }

    public function propertyValues(): BelongsToMany
    {
        return $this->belongsToMany(PropertyValue::class);
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    protected function preview(): Attribute
    {
        return Attribute::get(fn () => $this->hasMedia('goods') ? $this->getFirstMediaUrl('goods') : url('static/not-found.svg'));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
