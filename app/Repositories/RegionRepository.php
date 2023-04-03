<?php

namespace App\Repositories;

use App\Contracts\RegionInterface;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionRepository implements RegionInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Region::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }


        $query->with('country');

        $data = $query->paginate($limit);

        return RegionResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Region::whereId($id);
        $query->with('country');
        $find = $query->first();

        if (!$find) {
            return null;
        }

        return new RegionResource($find);
    }

    public function store(array $data)
    {
        $data = Region::create($data);
        return new RegionResource($data);
    }

    public function update($id, array $data)
    {
        $find = Region::find($id);
        $find->update($data);
        return new RegionResource($find);
    }

    public function delete($id)
    {
    }
}
