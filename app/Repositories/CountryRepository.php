<?php

namespace App\Repositories;

use App\Contracts\CountryInterface;
use App\Http\Resources\CountryResource;
use App\Http\Resources\SKUResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\VariationResource;
use App\Models\Country;
use App\Models\StockKeepingUnit;
use App\Models\Variation;

class CountryRepository implements CountryInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Country::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
            $query->orWhere('code', 'LIKE', "%{$search}%");
        }


        $data = $query->paginate($limit);

        return CountryResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $find = Country::whereId($id)
                    ->first();

        if (!$find) {
            return null;
        }

        return new CountryResource($find);
    }

    public function store(array $data)
    {
        $data = Country::create($data);
        return new CountryResource($data);
    }

    public function update($id, array $data)
    {
        $find = Country::find($id);
        $find->update($data);
        return new CountryResource($find);
    }

    public function delete($id)
    {
    }
}
