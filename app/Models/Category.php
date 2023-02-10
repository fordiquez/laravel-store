<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'slug', 'parent_id', 'manual_url'];

    protected $with = ['image'];

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

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
