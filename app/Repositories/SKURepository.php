<?php

namespace App\Repositories;

use App\Contracts\SKUInterface;
use App\Http\Resources\SKUResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\VariationResource;
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

        $query->with('warehouse');
        $query->with('variation');
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

        SkuWarehouse::create(['sku_id' => $sku['id'] , 'warehouse_id' => $data['warehouse'] ,'quantity' => $warehouse['quantity']]);

        if($data['variation']){
            SkuVariation::create(['sku_id' => $sku['id'],'variation_id' => $data['variation']]);
        }

        return new SKUResource($sku);
    }

    public function update($id, array $data)
    {
        $find = StockKeepingUnit::find($id);

        if($data['warehouses']){
            foreach ($data['warehouses'] as  $warehouse) {
                $checkWarehouse = SkuWarehouse::where('sku_id',$id)->where('warehouse_id' , $warehouse['id'])->first();
                if($checkWarehouse){
                    $checkWarehouse->quantity += $warehouse['quantity'];
                    $checkWarehouse->update();
                }else{
                    SkuWarehouse::create(['sku_id' => $find['id'] , 'warehouse_id' => $warehouse['id'] ,'quantity' => $warehouse['quantity']]);
                }
            }
        }

        if($data['variation']){
            $checkVariation = SkuVariation::where('sku_id',$id)->where('variation_id' ,$data['variation'])->first();
            if(!$checkVariation){
                SkuVariation::create(['sku_id' => $find['id'],'variation_id' => $data['variation']]);
            }
        }


        $find->update($data);
        return new SKUResource($find);
    }

    public function delete($id)
    {
    }
}
