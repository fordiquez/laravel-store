<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GoodResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodController extends Controller
{
    public function goods(Category $category)
    {
        $goods = Good::with('properties')->whereCategoryId($category->id);

        return inertia('Index/Goods', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => Category::breadcrumbs($category),
            'brands' => BrandResource::collection(Brand::whereIn('id', $goods->pluck('brand_id')->all())->get()),
            'prices' => [
                'min' => intval($goods->get()->min('price')),
                'max' => intval($goods->get()->max('price')),
            ],
            'properties' => Good::getFilterableProperties($goods->get())->toArray(),
            'goods' => GoodResource::collection($goods->filtered()->sorted()->paginate(10)->withQueryString()),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate(['search' => ['required', 'string', 'min:2']]);

        $goods = Good::searched();

        return inertia('Index/Goods', [
            'title' => $request->get('search'),
            'brands' => BrandResource::collection(Brand::whereIn('id', $goods->pluck('brand_id')->all())->get()),
            'prices' => [
                'min' => intval($goods->get()->min('price')),
                'max' => intval($goods->get()->max('price')),
            ],
            'properties' => Good::getFilterableProperties($goods->get())->toArray(),
            'goods' => GoodResource::collection($goods->filtered()->sorted()->paginate(10)->withQueryString()),
        ]);
    }

    public function index(Good $good)
    {
        return inertia('Good/Index', [
            'title' => $good->title,
            'breadcrumbs' => Category::breadcrumbs($good->category),
            'good' => new GoodResource($good),
        ]);
    }

    public function properties(Good $good)
    {
        $good->load('properties');

        return inertia('Good/Properties', [
            'title' => $good->title,
            'breadcrumbs' => Category::breadcrumbs($good->category),
            'good' => new GoodResource($good),
        ]);
    }

    public function reviews(Good $good)
    {
        $good->load('reviews');

        return inertia('Good/Reviews', [
            'title' => $good->title,
            'breadcrumbs' => Category::breadcrumbs($good->category),
            'good' => new GoodResource($good),
        ]);
    }
}
