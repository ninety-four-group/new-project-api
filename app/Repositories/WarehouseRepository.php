<?php

namespace App\Repositories;

use App\Contracts\WarehouseInterface;
use App\Http\Resources\BrandResource;
use App\Http\Resources\WarehouseResource;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Warehouse;

class WarehouseRepository implements WarehouseInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Warehouse::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $warehouses = $query->simplePaginate($limit);

        return WarehouseResource::collection($warehouses)->response()->getData();
    }

    public function get($id)
    {
        $query = Warehouse::where('id', $id);

        $warehouse = $query->get();
        return new WarehouseResource($warehouse);
    }

    public function store(array $data)
    {
        $brand = Warehouse::create($data);
        return new BrandResource($brand);
    }

    public function update($id, array $data)
    {
        $brand = Brand::find($id);

        $brand->name = $data['name'];
        $brand->slug = $data['slug'];
        $brand->image = $data['image'] ?? $brand->image;

        $brand->update();

        return new BrandResource($brand);
    }

    public function delete($id)
    {
    }
}
