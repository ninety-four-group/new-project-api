<?php

namespace App\Repositories;

use App\Contracts\TownshipInterface;
use App\Http\Resources\TownshipResource;
use App\Models\Township;
use Illuminate\Http\Request;

class TownshipRepository implements TownshipInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Township::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        $query->with('city');


        $data = $query->paginate($limit);

        return TownshipResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Township::whereId($id);
        $query->with('city');
        $find = $query->first();
        if (!$find) {
            return null;
        }

        return new TownshipResource($find);
    }

    public function store(array $data)
    {
        $data = Township::create($data);
        return new TownshipResource($data);
    }

    public function update($id, array $data)
    {
        $find = Township::find($id);
        $find->update($data);
        return new TownshipResource($find);
    }

    public function delete($id)
    {
    }
}
