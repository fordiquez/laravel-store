<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GoodResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Good;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function content(Category $category)
    {
        $goods = Good::whereCategoryId($category->id);

        return inertia('Content', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => Category::breadcrumbs($category),
            'title' => $category->title,
            'brands' => BrandResource::collection(Brand::whereIn('id', $goods->pluck('brand_id')->all())->get()),
            'filters' => [
                'prices' => [
                    'min' => intval($goods->get()->min('price')),
                    'max' => intval($goods->get()->max('price'))
                ]
            ],
            'goods' => GoodResource::collection($goods->filtered()->paginate(10)->withQueryString()),
        ]);
    }

    public function good(Good $good)
    {
        return Inertia::render('Good', [
            'good' => new GoodResource($good),
            'breadcrumbs' => Category::breadcrumbs($good->category),
            'title' => $good->title,
        ]);
    }
}
