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
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        if($request->level){
            if($request->level === "one"){
                $query = $query->where('parent_id' , null);
            }else if($request->level === "two"){
                $main_categories = Category::where('parent_id' , null)->select('id')->pluck('id');
                $query = $query->whereIn('parent_id',$main_categories);
            }else if($request->level === "three"){
                $main_categories = Category::where('parent_id' , null)->select('id')->pluck('id');
                $sub_categories = Category::whereIn('parent_id',$main_categories)->select('id')->pluck('id');
                $query = $query->whereIn('parent_id',$sub_categories);
            }
        }


        $query->with('subcategory');
        $query->with('subcategory.subcategory');

        $categories = $query->paginate($limit);


        return CategoryResource::collection($categories)->additional(['meta' => [
            'total_page' => (int) ceil($categories->total() / $categories->perPage()),
        ]])->response()->getData();
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
