<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class IndexController extends Controller
{
    public function dashboard()
    {
        return inertia('Index/Dashboard');
    }

    public function category(Category $category)
    {
        if (!count($category->subcategories)) {
            return to_route('goods.index', $category);
        }

        return inertia('Index/Category', [
            'category' => new CategoryResource($category),
            'breadcrumbs' => Category::breadcrumbs($category),
        ]);
    }
}
