<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Good;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function category(Category $category)
    {
        if ($category->goods->count()) {
            return to_route('index.goods', $category);
        }

        return Inertia::render('Category', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => $category->breadcrumbs($category)
        ]);
    }

    public function goods(Category $category)
    {
        $goods = Good::whereCategoryId($category->id)->get();

        return Inertia::render('Goods', compact('goods'));
    }

    public function good(Good $good)
    {
        return Inertia::render('Good', compact('good'));
    }
}
