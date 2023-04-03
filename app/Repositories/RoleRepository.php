<?php

namespace App\Repositories;

use App\Contracts\RegionInterface;
use App\Contracts\RoleInterface;
use App\Http\Resources\RegionResource;
use App\Http\Resources\RoleResource;
use App\Models\Region;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleRepository implements RoleInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Role::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);

        return RoleResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Role::whereId($id);
        $find = $query->first();

        if (!$find) {
            return null;
        }

        return new RoleResource($find);
    }

    public function store(array $data)
    {
        $data = Role::create($data);
        return new RoleResource($data);
    }

    public function update($id, array $data)
    {
        $find = Role::find($id);
        $find->update($data);
        return new RoleResource($find);
    }

    public function delete($id)
    {
    }
}
