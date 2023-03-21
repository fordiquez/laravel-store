<?php

namespace App\Http\Resources;

use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Good */
class GoodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vendor_code' => $this->vendor_code,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'warning_description' => $this->warning_description,
            'old_price' => !$this->old_price ?: number_format($this->old_price, thousands_separator: ' '),
            'price' => number_format($this->price, thousands_separator: ' '),
            'quantity' => $this->quantity,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'preview' => $this->preview,
            'media_count' => $this->media_count,
            'option_values_count' => $this->option_values_count,
            'reviews_count' => $this->reviews_count,
            'tags_count' => $this->tags_count,

            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,

            'brand' => new BrandResource($this->whenLoaded('brand')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'properties' => PropertyResource::collection($this->whenLoaded('properties')),
        ];
    }
}
