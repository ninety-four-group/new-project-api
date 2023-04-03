<?php

namespace App\Repositories;

use App\Contracts\CityInterface;
use App\Contracts\RegionInterface;
use App\Http\Resources\CityResource;
use App\Http\Resources\RegionResource;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;

class CityRepository implements CityInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = City::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        $query->with('region');


        $data = $query->paginate($limit);

        return CityResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = City::whereId($id);
        $query->with('region');
        $find = $query->first();
        if (!$find) {
            return null;
        }

        return new CityResource($find);
    }

    public function store(array $data)
    {
        $data = City::create($data);
        return new CityResource($data);
    }

    public function update($id, array $data)
    {
        $find = City::find($id);
        $find->update($data);
        return new CityResource($find);
    }

    public function delete($id)
    {
    }
}
