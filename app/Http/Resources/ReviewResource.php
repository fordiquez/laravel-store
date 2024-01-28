<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Review */
class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_buyer' => $this->is_buyer,
            'content' => $this->content,
            'advantages' => $this->advantages,
            'disadvantages' => $this->disadvantages,
            'rating' => $this->rating,
            'video_src' => $this->video_src,
            'created_at' => $this->created_at->format('F d, Y'),
            'updated_at' => $this->updated_at,

            'user_id' => $this->user_id,
            'good_id' => $this->good_id,

            'username' => $this->user->full_name,

            'good' => new GoodResource($this->whenLoaded('good')),
        ];
    }
}
