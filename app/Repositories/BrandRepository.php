<?php

namespace App\Repositories;

use App\Contracts\BrandInterface;
use App\Http\Resources\BrandResource;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandRepository implements BrandInterface
{
    public function all(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = Brand::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $brands = $query->paginate($limit);

        return BrandResource::collection($brands)->additional(['meta' => [
            'total_page' => (int) ceil($brands->total() / $brands->perPage()),
        ]])->response()->getData();
    }

    public function get($id)
    {
        $brand = Brand::whereId($id)->first();
        return new BrandResource($brand);
    }

    public function store(array $data)
    {
        $brand = Brand::create($data);

        $query = Brand::whereId($brand->id);
        $query->with('media');
        $find = $query->first();
        return new BrandResource($find);
    }

    public function update($id, array $data)
    {

        $brand = Brand::find($id);

        $brand->name = $data['name'];
        $brand->slug = $data['slug'];
        $brand->media_id = $data['media_id'] ?? $brand->media_id;

        $brand->update();

        $brand->with('media');
        
        return new BrandResource($brand);
    }

    public function delete($id)
    {
    }
}
