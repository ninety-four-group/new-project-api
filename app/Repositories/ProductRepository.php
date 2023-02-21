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

        if($request->warehouse_id){
            $warehouseId = $request->warehouse_id;

            $query->whereHas('warehouse',function($query) use($warehouseId){
                $query->where('product_warehouses.warehouse_id',$warehouseId);
            });
        }


        $query->with('category');
        $query->with('brand');
        $query->with('lastUpdatedUser');
        $query->with('media');
        $query->with('tags');
        $query->with('sku');
        $query->with('sku.variation');

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

        if ($data['warehouses']) {
            $product->warehouse()->sync($data['warehouses']);
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
        $find = Product::whereId($id)->first();

        if ($data['tags']) {
            $find->tags()->sync($data['tags']);
        }
        if ($data['media']) {
            $find->media()->sync($data['media']);
        }

        if ($data['warehouses']) {
            $find->warehouse()->sync($data['warehouses']);
        }

        $find->with('category');
        $find->with('brand');
        $find->with('lastUpdatedUser');
        $find->with('media');


        $find->name = $data['name'];
        $find->mm_name = $data['mm_name'];
        $find->video_url = $data['video_url'];
        $find->brand_id = $data['brand_id'];
        $find->category_id = $data['category_id'];
        $find->description = $data['description'];
        $find->mm_description = $data['mm_description'];
        $find->status = $data['status'];

        $find->update();

        return new ProductResource($find);
    }

    public function delete($id)
    {
    }
}
