<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GoodResource;
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
        return Inertia::render('Content', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => Category::breadcrumbs($category),
            'title' => $category->title,
            'goods' => $category->goods->count() ? GoodResource::collection(Good::whereCategoryId($category->id)->paginate(2)->onEachSide(1)->withQueryString()) : null,
        ]);
    }

    public function good(Good $good)
    {
        return Inertia::render('Good', [
            'good' => new GoodResource($good),
            'breadcrumbs' => Category::breadcrumbs($good->category),
            'title' => $good->title
        ]);
    }
}
