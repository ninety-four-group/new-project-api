<?php

namespace App\Repositories;

use App\Contracts\SKUInterface;
use App\Http\Resources\SKUResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\VariationResource;
use App\Models\ProductVariationType;
use App\Models\SkuVariation;
use App\Models\SkuWarehouse;
use App\Models\StockKeepingUnit;
use App\Models\Variation;

class SKURepository implements SKUInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = StockKeepingUnit::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $query->with('warehouses');
        $query->with('variations');
        $query->with('variations.variation');
        $query->with('product');

        $data = $query->paginate($limit);

        return SKUResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $find = StockKeepingUnit::whereId($id)
                    ->first();

        if (!$find) {
            return null;
        }

        return new SKUResource($find);
    }

    public function store(array $data)
    {
        $sku = StockKeepingUnit::create($data);

        // SkuWarehouse::create(['sku_id' => $sku['id'] , 'warehouse_id' => $data['warehouse_id'] ,'quantity' => $data['quantity']]);

        foreach ($data['variations'] as $variation) {
            $checkVariation = SkuVariation::where('sku_id', $sku['id'])->where('variation_id', $variation)->first();
            if (!$checkVariation) {
                SkuVariation::create(['sku_id' => $sku['id'],'variation_id' => $variation]);
            }

            $variation = Variation::find($variation);

            $checkSort = ProductVariationType::where('product_id', $data['product_id'])->where('type', $variation->type)->first();
            if (!$checkSort) {
                ProductVariationType::create(['product_id'=> $data['product_id'], 'type' => $variation->type]);
            }
        }

        return new SKUResource($sku);
    }

    public function update($id, array $data)
    {
        $find = StockKeepingUnit::find($id);
        $find->product_id = $data['product_id'];
        $find->code = $data['code'];
        $find->quantity = $data['quantity'];
        $find->price = $data['price'];
        $find->status = $data['status'];
        $find->update();

        SkuVariation::where('sku_id', $id)->forceDelete();

        foreach ($data['variations'] as $variation) {
            SkuVariation::create(['sku_id' =>$id,'variation_id' => $variation]);
        }

        return new SKUResource($find);
    }

    public function delete($id)
    {
    }
}
