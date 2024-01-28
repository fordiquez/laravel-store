<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'is_active' => $this->is_active,
            'is_navigational' => $this->is_navigational,
            'subcategories' => $this->subcategories()->count() ? $this::loopCategories($this->subcategories) : null,
        ];
    }
}
