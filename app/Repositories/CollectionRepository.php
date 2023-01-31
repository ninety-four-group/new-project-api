<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Contracts\CollectionInterface;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;

class CollectionRepository implements CollectionInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Collection::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('mm_name', 'LIKE', "%{$search}%");
        }

        if($request->start_date){
            $query->where('start_date', '>=' , $request->start_date);
        }

        if($request->end_date){
            $query->where('end_date', '<=' , $request->end_date);
        }

        $data = $query->simplePaginate($limit);

        return CollectionResource::collection($data)->response()->getData();
    }

    public function get($id)
    {
        $query = Collection::where('id', $id);

        $data = $query->get();
        return new CollectionResource($data);
    }

    public function store(array $data)
    {
        $store = Collection::create($data);
        return new CollectionResource($store);
    }

    public function update($id, array $data)
    {
        $data = Collection::find($id);

        $data->name = $data['name'];
        $data->mm_name = $data['mm_name'];
        $data->start_date = $data['start_date'];
        $data->end_date = $data['end_date'];

        $data->update();

        return new CollectionResource($data);
    }

    public function delete($id)
    {
    }
}
