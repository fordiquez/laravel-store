<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'good_id',
        'description',
        'advantages',
        'disadvantages',
        'rating',
        'video_src'
    ];

    public function reviewImages(): HasMany
    {
        return $this->hasMany(ReviewImage::class);
    }
}
