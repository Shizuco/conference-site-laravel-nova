<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
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
        $parent_id = $conf[0]->category_id;
        return response()->json(Category::where('parent_id', $parent_id)->get());
    }

    public function currentCategory(int $id)
    {
        return response()->json(Category::whereId($id)->get());
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->parent) {
            $node = Category::find($request->parent);
            $node->appendNode($category);
        }
    }
    public function destroy(Request $request)
    {
        $node = Category::find($request->id);
        $node->delete();
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
