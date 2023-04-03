<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\VariationType;
use App\Traits\HttpResponses;
use App\Models\VariationCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\VariationCategoryResource;
use App\Http\Requests\StoreVariationCategoryRequest;
use App\Http\Requests\StoreVariationTypeRequest;
use App\Http\Requests\UpdateVariationCategoryRequest;
use App\Http\Resources\VariationTypeResource;
use App\Models\Variation;

class VariationTypeController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('abilities:view_variation_type')->only('index', 'show');
        $this->middleware('abilities:add_variation_type')->only('store');
        $this->middleware('abilities:edit_variation_type')->only('update');
        $this->middleware('abilities:delete_variation_type')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = VariationType::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);

        return $this->success(VariationTypeResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData(),'Variation Type List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariationTypeRequest $request)
    {
       $save = VariationType::create([
            'name' => $request->name,
            'flag' => $request->flag
        ]);

        return $this->success(new VariationTypeResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = VariationType::find($id);
        if (!$data) {
            return $this->error(null, 'Variation Type not found', 404);
        }
        return $this->success(new VariationTypeResource($data), 'Variation Type Detail');
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
        $find = VariationType::find($id);

        if (!$find) {
            return $this->error(null, 'Variation Type not found', 404);
        }

        $find->name = $request->name ?? $find->name;
        $find->flag = $request->flag ?? $find->flag;
        $find->update();

        return $this->success(new VariationTypeResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = VariationType::find($id);

        if (!$data) {
            return $this->error(null, 'Variation Type not found', 404);
        }

        if(Variation::where('type_id',$id)->exists()){
            return $this->error(null,"Can not delete because this variation type is linked with some variations");
        }

        $data->delete();
        return $this->success(null, 'Successfully delete');
    }
}
