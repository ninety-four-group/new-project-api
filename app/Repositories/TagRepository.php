<?php

namespace App\Repositories;

use App\Contracts\TagInterface;
use App\Contracts\WarehouseInterface;
use App\Http\Resources\BrandResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\WarehouseResource;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Warehouse;

class TagRepository implements TagInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Tag::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);

        return TagResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Tag::where('id', $id);
        $data = $query->first();
        return new TagResource($data);
    }

    public function store(array $data)
    {
        $store = Tag::create($data);
        return new TagResource($store);
    }

    public function update($id, array $data)
    {
        $find = Tag::find($id);

        $find->name = $data['name'];
        $find->mm_name = $data['mm_name'];
        $find->status = $data['status'];

        $find->update();

        return new TagResource($find);
    }

    public function delete($id)
    {
    }
}
