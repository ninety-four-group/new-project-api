<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\VariationInterface;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\VariationResource;
use App\Models\Variation;

class VariationRepository implements VariationInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Variation::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->type_id) {
            $query->where('type_id', $request->type_id);
        }

        $query->with('media');
        $query->with('type');

        $data = $query->paginate($limit);

        return VariationResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $find = Variation::whereId($id)
                    ->with('type')
                    ->with('media')
                    ->first();

        if (!$find) {
            return null;
        }

        return new VariationResource($find);
    }

    public function store(array $data)
    {
        $data = Variation::create($data);


        return new VariationResource($data);
    }

    public function update($id, array $data)
    {

        $find = Variation::find($id);

        $find->name = $data['name'];
        $find->type_id = $data['type_id'];
        $find->type_value = $data['type_value'];
        $find->media_id = $data['media_id'];
        $find->update();
        return new VariationResource($find);
    }

    public function delete($id)
    {
    }
}
