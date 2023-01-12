<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Contracts\CategoryInterface;

class CategoryRepository implements CategoryInterface
{
    public function all()
    {
        $query = Category::query();
        $query->with('subcategory');
        $query->with('parent');
        $categories = $query->get();

        return $categories;
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
