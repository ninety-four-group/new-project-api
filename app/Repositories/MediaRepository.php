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
            $query->where('title', 'LIKE', "%{$search}%");
            $query->orWhere('caption', 'LIKE', "%{$search}%");
            $query->orWhere('alt_text', 'LIKE', "%{$search}%");
            $query->orWhere('description', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);

        return MediaResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $query = Media::where('id', $id);
        $data = $query->first();
        return new MediaResource($data);
    }

    public function store(array $data)
    {
    }

    public function update($id, array $data)
    {
    }

    public function delete($id)
    {
    }
}
