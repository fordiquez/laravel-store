<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = ['title', 'slug', 'parent_id', 'is_active', 'is_navigational', 'manual_url'];

    protected $appends = ['thumbnail'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_navigational' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn (Category $category) => ($category->slug = $category->slug ?? str($category->title)->slug()));
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

    public static function breadcrumbs(Category $category, array $breadcrumbs = []): array
    {
        if (!count($breadcrumbs)) {
            $breadcrumbs[] = $category;
        }

        if ($category->parent) {
            $breadcrumbs = Arr::prepend($breadcrumbs, $category->parent);

            return self::breadcrumbs($category->parent, $breadcrumbs);
        }

        return $breadcrumbs;
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
