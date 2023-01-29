<?php

namespace App\Models;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'src',
        'parent_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function loopCategories($categories) {
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

    public function getSrcAttribute($value): string|UrlGenerator|Application
    {
        return url("/storage/$value");
    }
}
