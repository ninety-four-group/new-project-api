<?php

namespace App\Repositories;

use App\Contracts\SKUInterface;
use App\Http\Resources\SKUResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\VariationResource;
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


        $data = $query->simplePaginate($limit);

        return SKUResource::collection($data)->response()->getData();
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
        $data = StockKeepingUnit::create($data);
        return new SKUResource($data);
    }

    public function update($id, array $data)
    {
        $find = StockKeepingUnit::find($id);

        $find->name = $data['name'];
        $find->variation_category_id = $data['variation_category_id'];
        $find->image = $data['image'] ?? $find->image;
        $find->update();
        return new SKUResource($find);
    }

    public function delete($id)
    {
    }
}