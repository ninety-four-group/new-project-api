<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ProductInterface;
use App\Http\Resources\CategoryResource;

class ProductRepository implements ProductInterface
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
        $category = Category::whereId($id)
                    ->with('subcategory')
                    ->with('subcategory.subcategory')->first();

        if (!$category) {
            return null;
        }

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
}