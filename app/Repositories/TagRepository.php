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

        $data = $query->get();
        return new TagResource($data);
    }

    public function store(array $data)
    {
        $store = Tag::create($data);
        return new TagResource($store);
    }

    public function update($id, array $data)
    {
        $data = Tag::find($id);

        $data->name = $data['name'];
        $data->mm_name = $data['mm_name'];
        $data->status = $data['status'];

        $data->update();

        return new TagResource($data);
    }

    public function delete($id)
    {
    }
}
