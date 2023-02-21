<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = ['title', 'slug', 'parent_id', 'manual_url'];

    protected $appends = ['thumbnail'];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn(Category $category) => ($category->slug = $category->slug ?? str($category->title)->slug()));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function goods(): HasMany
    {
        return $this->hasMany(Good::class);
    }

    public function loopCategories($categories)
    {
        foreach ($categories as $category) {
            if ($category->subcategories()->count()) {
                $category['subcategories'] = $category->subcategories;
                $this->loopCategories($category->subcategories);
            }
        }
        return $categories;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function thumbnail(): Attribute
    {
        return Attribute::get(fn () => $this->hasMedia('categories') ? $this->getFirstMediaUrl('categories') : url('static/not-found.svg'));
    }
}
