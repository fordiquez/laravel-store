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

class IndexController extends Controller
{
    public function dashboard()
    {
        return inertia('Index/Dashboard');
    }

    public function category(Category $category)
    {
        if (!count($category->subcategories)) {
            return to_route('index.goods', $category);
        }

        return inertia('Index/Category', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => Category::breadcrumbs($category),
        ]);
    }

    public function goods(Category $category)
    {
        $goods = Good::whereCategoryId($category->id);

        return inertia('Index/Goods', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => Category::breadcrumbs($category),
            'brands' => BrandResource::collection(Brand::whereIn('id', $goods->pluck('brand_id')->all())->get()),
            // TODO: unwrap filters
            'filters' => [
                'prices' => [
                    'min' => intval($goods->get()->min('price')),
                    'max' => intval($goods->get()->max('price')),
                ],
            ],
            'goods' => GoodResource::collection($goods->filtered()->sorted()->paginate(10)->withQueryString()),
        ]);
    }

    public function search(Request $request)
    {
        $goods = Good::searched();

        return inertia('Index/Goods', [
            'title' => $request->get('search'),
            'brands' => BrandResource::collection(Brand::whereIn('id', $goods->pluck('brand_id')->all())->get()),
            // TODO: unwrap filters
            'filters' => [
                'prices' => [
                    'min' => intval($goods->get()->min('price')),
                    'max' => intval($goods->get()->max('price')),
                ],
            ],
            'goods' => GoodResource::collection($goods->filtered()->sorted()->paginate(10)->withQueryString()),
        ]);
    }

    public function good(Good $good)
    {
        return inertia('Index/Good', [
            'good' => new GoodResource($good),
            'breadcrumbs' => Category::breadcrumbs($good->category),
            'title' => $good->title,
        ]);
    }
}
