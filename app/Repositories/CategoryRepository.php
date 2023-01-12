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
        $limit = $request->query('limit',10);

        $query = Category::query();

        if($search){
            $query->where('name','LIKE',"%{$search}%");
            $query->where('mm_name','LIKE',"%{$search}%");
        }

        $query->with('subcategory');
        $query->with('subcategory.subcategory');

        $categories = $query->simplePaginate($limit);

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
