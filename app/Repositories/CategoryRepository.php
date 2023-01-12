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
        $limit = $request->query('limit',10);
        $query = Category::query();
        $query->where('parent_id',null);
        $query->with('subcategory');
        $query->with('subcategory.subcategory');
        $categories = $query->paginate($limit);
        return CategoryResource::collection($categories)->response()->getData();
    }

    public function get($id)
    {
    }

    public function store(array $data)
    {
      $category = Category::create($data);
      return $category;
    }

    public function update($id, array $data)
    {
    }

    public function delete($id)
    {
    }
}
