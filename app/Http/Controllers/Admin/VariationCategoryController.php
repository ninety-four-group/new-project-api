<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVariationCategoryRequest;
use App\Http\Requests\UpdateVariationCategoryRequest;
use App\Http\Resources\VariationCategoryResource;

class VariationCategoryController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = VariationCategory::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);

        return $this->success(VariationCategoryResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData(),'Variation Category List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariationCategoryRequest $request)
    {
       $save = VariationCategory::create([
            'name' => $request->name
        ]);

        return $this->success(new VariationCategoryResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = VariationCategory::find($id);
        if (!$data) {
            return $this->error(null, 'Variation Category not found', 404);
        }
        return $this->success(new VariationCategoryResource($data), 'Variation Category Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariationCategoryRequest $request, $id)
    {
        $find = VariationCategory::find($id);

        if (!$find) {
            return $this->error(null, 'Variation Category not found', 404);
        }

        $find->name = $request->name ?? $find->name;
        $find->update();

        return $this->success(new VariationCategoryResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = VariationCategory::find($id);

        if (!$data) {
            return $this->error(null, 'Variation Category not found', 404);
        }

        $data->delete();
        return $this->success(null, 'Successfully delete');
    }
}
