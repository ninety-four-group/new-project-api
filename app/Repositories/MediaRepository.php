<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Contracts\MediaInterface;
use App\Http\Resources\MediaResource;

class MediaRepository implements MediaInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Media::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        $data = $query->simplePaginate($limit);

        return MediaResource::collection($data)->response()->getData();
    }

    public function get($id)
    {
        $query = Media::where('id', $id);

        $data = $query->get();
        return new MediaResource($data);
    }

    public function store(array $data)
    {
        $store = Media::create($data);
        return new MediaResource($store);
    }

    public function update($id, array $data)
    {

    }

    public function delete($id)
    {
    }
}
