<?php

declare (strict_types = 1);

namespace App\Http\Controllers;
 
use App\Models\Category;
use App\Models\Conference;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::get()->toTree());
    }

    public function rootCategories()
    {
        return response()->json(Category::where('parent_id', null)->get());
    }

    public function subCategories(int $id)
    {
        $conf = Conference::where('id', $id)->firstOfFail();
        $parentId = $conf->category_id;
        return response()->json(Category::where('parent_id', $parentId)->get());
    }

    public function currentCategory(int $id)
    {
        return response()->json(Category::where('id', $id)->firstOrFail());
    }

    public function getConferences(int $id)
    {
        return response()->json(Category::with('conferences')->where('id', $id)->paginate(5));
    }

    public function getReports(int $id)
    {
        return response()->json(Category::with('reports')->where('id', $id)->firstOrFail());
    }
}
