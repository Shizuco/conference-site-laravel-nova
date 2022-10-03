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
        $conf = Conference::whereId($id)->get();
        $parentId = $conf[0]->category_id;
        return response()->json(Category::where('parent_id', $parentId)->get());
    }

    public function currentCategory(int $id)
    {
        return response()->json(Category::whereId($id)->get());
    }

    public function getConferences(int $id)
    {
        return response()->json(Category::with('conferences')->whereId($id)->paginate(5));
    }

    public function getReports(int $id)
    {
        return response()->json(Category::with('reports')->whereId($id)->get());
    }
}
