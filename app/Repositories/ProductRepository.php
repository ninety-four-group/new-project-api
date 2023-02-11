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

        $data = $query->paginate($limit);

        return ProductResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Product::whereId($id);
        $query->with('category');
        $query->with('brand');
        $query->with('lastUpdatedUser');
        $query->with('media');
        $query->with('tags');
        $data = $query->first();

        if (!$data) {
            return null;
        }

        return new ProductResource($data);
    }

    public function store(array $data)
    {
        $product = Product::create($data);

        if ($data['tags']) {
            $product->tags()->sync($data['tags']);
        }
        if ($data['media']) {
            $product->media()->sync($data['media']);
        }

        $query = Product::whereId($product->id);
        $query->with('category');
        $query->with('brand');
        $query->with('lastUpdatedUser');
        $query->with('media');
        $query->with('tags');
        $data = $query->first();
        return new ProductResource($data);
    }

    public function update($id, array $data)
    {
        $find = Product::whereId($id);

        if ($data['tags']) {
            $find->tags()->sync($data['tags']);
        }
        if ($data['media']) {
            $find->media()->sync($data['media']);
        }

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
