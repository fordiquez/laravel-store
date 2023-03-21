<?php

namespace App\Models;

use App\Enums\GoodStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
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
        'options',
    ];

    protected $appends = ['preview'];

    protected $with = ['category', 'brand'];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'status' => GoodStatus::class,
        'options' => 'array',
    ];

    public function scopeFiltered(Builder $query)
    {
        $query->when(request('brands'), function (Builder $q) {
            $q->whereIn('brand_id', request('brands'));
        })->when(request('prices'), function (Builder $q) {
            $q->whereBetween('price', [
                request('prices.from', 0),
                request('prices.to', 100000),
            ]);
        })->when(request('properties'), function (Builder $q) {
            $q->whereHas('properties', function (Builder $builder) {
                $builder->whereIn('value', request('properties'));
            });
        });
    }

    public function scopeSorted(Builder $query)
    {
        $query->when(request('sort'), function (Builder $q) {
            $column = request()->str('sort');

            if ($column->contains(['price', 'created_at'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';

                $q->orderBy((string) $column->remove('-'), $direction);
            }
        });
    }

    public function scopeSearched(Builder $query)
    {
        $query->when(request('search'), function (Builder $q) {
            $q->whereFullText(['title', 'description'], request('search'));
        });
    }

    public static function getFilterableProperties(Collection $goods): Collection
    {
        $singleProperties = collect();

        $goods->each(function (Good $good) use ($singleProperties) {
            $good->properties
                ->filter(fn (Property $property) => $property->filterable)
                ->each(fn (Property $property) => $singleProperties->push([
                    'good_id' => $property->pivot->good_id,
                    'property_id' => $property->pivot->property_id,
                    'name' => $property->name,
                    'slug' => $property->slug,
                    'value' => $property->pivot->value,
                ]));
        });

        $groupedValues = $singleProperties->mapToGroups(fn (array $item) => [$item['property_id'] => $item['value']]);

        $properties = collect();

        $singleProperties->each(function (array $item) use ($groupedValues, $properties) {
            $properties->getOrPut($item['property_id'], [
                'property_id' => $item['property_id'],
                'good_id' => $item['good_id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'values' => $groupedValues->get($item['property_id'])->toArray(),
            ]);
        });

        return $properties;
    }

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

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)->withPivot('value');
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
