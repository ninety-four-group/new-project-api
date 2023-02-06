<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ProductInterface;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductRepository implements ProductInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Product::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        $query->with('category');
        $query->with('brand');
        $query->with('lastUpdatedUser');
        $query->with('media');
        $query->with('tags');

        $data = $query->simplePaginate($limit);

        return ProductResource::collection($data)->response()->getData();
    }

    public function get($id)
    {
        $query = Product::whereId($id);
        $query->with('category');
        $query->with('brand');
        $query->with('lastUpdatedUser');
        $query->with('media');
        $data = $query->first();

        if (!$data) {
            return null;
        }

        return new ProductResource($data);
    }

    public function store(array $data)
    {
        $product = Product::create($data);

        $product->tags()->sync($data['tags']);

        return new ProductResource($product);
    }

    public function update($id, array $data)
    {
        $find = Product::whereId($id);
        $find->with('category');
        $find->with('brand');
        $find->with('lastUpdatedUser');
        $find->with('media');
        $find->update($data);
        return new ProductResource($find->first());
    }

    public function delete($id)
    {
    }
}
