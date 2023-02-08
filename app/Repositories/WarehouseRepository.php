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

        $warehouses = $query->paginate($limit);

        return WarehouseResource::collection($warehouses)->additional(['meta' => [
            'total_page' => (int) ceil($warehouses->total() / $warehouses->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Warehouse::where('id', $id);

        $warehouse = $query->get();
        return new WarehouseResource($warehouse);
    }

    public function store(array $data)
    {
        $warehouse = Warehouse::create($data);
        return new WarehouseResource($warehouse);
    }

    public function update($id, array $data)
    {
        $warehouse = Warehouse::find($id);

        $warehouse->name = $data['name'];
        $warehouse->slug = $data['slug'];

        $warehouse->update();

        return new WarehouseResource($warehouse);
    }

    public function delete($id)
    {
    }
}
