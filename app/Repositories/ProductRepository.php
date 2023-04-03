<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contracts\ProductInterface;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;

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

        if ($request->category_id) {
            $reqCategory = Category::where('id', $request->category_id)->first();
            if ($reqCategory) {
                $query->orWhere('category_id', $reqCategory->id);
                if ($reqCategory->parent_id) {
                    $checkOneCategory = Category::where('id', $reqCategory->parent_id)->first();
                    $query->orWhere('category_id', $checkOneCategory->id);
                    if (isset($checkOneCategory->parent_id)) {
                        $checkTwoCategory = Category::where('id', $checkOneCategory->parent_id)->first();
                        $query->orWhere('category_id', $checkTwoCategory->id);
                    } else {
                        $childCategories = Category::where('parent_id', $checkOneCategory->id)->select('id')->pluck('id');
                        $subChildCategories = Category::whereIn('parent_id', $childCategories)->first();
                        if ($subChildCategories) {
                            $query->orWhere('category_id', $subChildCategories->id);
                        }
                    }
                } else {
                    $childCategories = Category::where('parent_id', $reqCategory->id)->select('id')->pluck('id');
                    foreach ($childCategories as $child) {
                        $query->orWhere('category_id', $child);
                    }
                    $subChildCategories = Category::whereIn('parent_id', $childCategories)->first();
                    if ($subChildCategories) {
                        $query->orWhere('category_id', $subChildCategories->id);
                    }
                }
            }
        }

        if ($request->brand_ids) {
            $brandIdArr = explode(",", $request->brand_ids);
            $query->whereIn('brand_id', $brandIdArr);
        }

        if ($request->tag_id) {
            $tagId = $request->tag_id;
            $query->whereHas('tags', function ($query) use ($tagId) {
                $query->where('product_tags.tag_id', $tagId);
            });
        }

        if ($request->collection_ids) {
            $collectionIdArr = explode(",", $request->collection_ids);
            $query->whereHas('collections', function ($query) use ($collectionIdArr) {
                $query->whereIn('product_collections.collection_id', $collectionIdArr);
            });
        }

        if ($request->warehouse_id) {
            $warehouseId = $request->warehouse_id;

            $query->whereHas('warehouse', function ($query) use ($warehouseId) {
                $query->where('product_warehouses.warehouse_id', $warehouseId);
            });
        }

        if ($request->sort_by) {
            $filterValue = $request->sort_by;
            if ($filterValue === 'new_arrival') {
                $query->whereHas('isNewArrival', function ($q) {
                    $q->where('is_enabled', true);
                });
            }
            if ($filterValue === 'best_selling') {
                $query->whereHas('isBestSelling', function ($q) {
                    $q->where('is_enabled', true);
                });
            }
            if ($filterValue === 'popular') {
                $query->whereHas('isPopular', function ($q) {
                    $q->where('is_enabled', true);
                });
            }
        }

        if ($request->sortname === 'atz') {
            $query->orderBy('name', 'asc');
        }

        if ($request->sortname === 'zta') {
            $query->orderBy('name', 'desc');
        }




        $query->with('isNewArrival');
        $query->with('isBestSelling');
        $query->with('isPopular');
        $query->with('category');
        $query->with('brand');
        $query->with('lastUpdatedUser');
        $query->with('media');
        $query->with('collections');
        $query->with('tags');
        $query->with('sku.variations.variation');
        $query->with('sku.variations.variation.type');
        $query->with('stock');
        // $query->with('sku.variations.warehouse');
        $query->with('sku.warehouses');
        $query->with('sku.warehouses.warehouse');
        $query->with('sku.warehouses.sku');

        if ($request->sortprice) {
            if ($request->sortprice === 'lth') {
                $query->whereHas('sku', function ($q) {
                    $q->orderBy('price', 'asc')->orderBy('id', 'asc');
                })->orderBy(DB::raw('(SELECT price FROM stock_keeping_units WHERE stock_keeping_units.product_id = products.id ORDER BY id ASC LIMIT 1)'), 'ASC');
            }

            if ($request->sortprice === 'htl') {
                $query->whereHas('sku', function ($q) {
                    $q->orderBy('price', 'desc')->orderBy('id', 'asc');
                })->orderBy(DB::raw('(SELECT price FROM stock_keeping_units WHERE stock_keeping_units.product_id = products.id ORDER BY id ASC LIMIT 1)'), 'DESC');
            }

        } else {
            $query->with('sku');
        }


        if ($request->min_price && $request->max_price) {
            $minPrice = $request->min_price;
            $maxPrice = $request->max_price;
            $query->whereHas('sku', function ($q) use ($minPrice, $maxPrice) {
                $q->where('price', '>=', $minPrice)->where('price', '<=', $maxPrice);
            });
        }

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
        $query->with('collections');
        $query->with('sku');
        $query->with('sku.variations.variation');
        $query->with('sku.variations.variation.type');
        $query->with('stock');
        $query->with('stock.warehouse');
        $query->with('stock.sku');
        $query->with('stock.product');
        // $query->with('sku.variations.warehouse');
        $query->with('sku.warehouses');
        $query->with('sku.warehouses.warehouse');
        $query->with('sku.warehouses.sku');

        $query->with('isNewArrival');
        $query->with('isBestSelling');
        $query->with('isPopular');

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

        if ($data['collections']) {
            $product->collections()->sync($data['collections']);
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

        $find->tags()->detach();

        if ($data['tags']) {
            $find->tags()->attach($data['tags']);
        }

        if ($data['media']) {
            $find->media()->sync($data['media']);
        }

        if ($data['warehouses']) {
            $find->warehouse()->sync($data['warehouses']);
        }

        if ($data['collections']) {
            $find->collections()->sync($data['collections']);
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
