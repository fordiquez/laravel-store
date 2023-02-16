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
            'image' => $this->image->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'subcategories' => $this->subcategories()->count() ? $this::loopCategories($this->subcategories) : null,
        ];
    }
}
