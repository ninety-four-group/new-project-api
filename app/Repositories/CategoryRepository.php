<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\CategoryInterface;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;

class CategoryRepository implements CategoryInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Category::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->where('mm_name', 'LIKE', "%{$search}%");
        }

        $query->with('subcategory');
        $query->with('subcategory.subcategory');

        $categories = $query->simplePaginate($limit);

        return CategoryResource::collection($categories)->response()->getData();
    }

    public function get($id)
    {
        $query = Category::where('id', $id);

        $query->with('subcategory');
        $query->with('subcategory.subcategory');
        $category = $query->get();
        return new CategoryResource($category);
    }

    public function store(array $data)
    {
        $category = Category::create($data);
        return new CategoryResource($category);
    }

    public function update($id, array $data)
    {
        $category = Category::find($id);

        $category->name = $data['name'];
        $category->mm_name = $data['mm_name'];
        $category->parent_id = $data['parent_id'];
        $category->slug = $data['slug'];
        $category->image = $data['image'] ?? $category->image;

        $category->update();
        return new CategoryResource($category);
    }

    public function delete($id)
    {
    }

    public function getList(Request $request)
    {
    }
}
