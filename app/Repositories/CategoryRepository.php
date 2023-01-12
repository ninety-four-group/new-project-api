<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Contracts\CategoryInterface;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;

class CategoryRepository implements CategoryInterface
{
    public function all()
    {
        $query = Category::query();
        $query->where('parent_id',null);
        $query->with('subcategory');
        $query->with('subcategory.subcategory');
        $categories = $query->paginate(1);
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
