<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\CategoryInterface;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    use HttpResponses;

    protected $category;

    public function __construct(CategoryInterface $interface)
    {
        $this->category = $interface;
    }

    public function getCategories(Request $request)
    {


        $query = Category::query();

        if ($request->level) {
            if ($request->level === "one") {
                $query = $query->where('parent_id', null);
            } else if ($request->level === "two") {
                $main_categories = Category::where('parent_id', null)->select('id')->pluck('id');
                $query = $query->whereIn('parent_id', $main_categories);
            } else if ($request->level === "three") {
                $main_categories = Category::where('parent_id', null)->select('id')->pluck('id');
                $sub_categories = Category::whereIn('parent_id', $main_categories)->select('id')->pluck('id');
                $query = $query->whereIn('parent_id', $sub_categories);
            }
        }

        if ($request->parent_id) {
            $query->where('parent_id', $request->parent_id);
        }

        $categories = $query->get();

        return $this->success(CategoryResource::collection($categories), 'Category List');
    }
}
