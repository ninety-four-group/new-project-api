<?php

namespace App\Http\Controllers\Admin;

use App\Models\Variation;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Contracts\VariationInterface;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVariationRequest;
use App\Http\Requests\UpdateVariationRequest;
use App\Models\SkuVariation;

class VariationController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(VariationInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->interface->all($request);
        return $this->success($data, 'Variation List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariationRequest $request)
    {
        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'type_value' => $request->type_value,
            'media_id' => $request->media_id,
        ];

        $save = $this->interface->store($data);

        return $this->success($save, 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->interface->get($id);
        return $this->success($data, 'Variation Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariationRequest $request, $id)
    {
        $find = Variation::find($id);

        if (!$find) {
            return $this->error(null, 'Variation not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'type' => $request->type ?? $find->type,
            'type_value' => $request->type_value ?? $find->type_value,
            'media_id' => $request->media_id ?? $find->media_id,
        ];


        $update = $this->interface->update($id, $data);

        return $this->success($update, 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = Variation::find($id);

        if (!$find) {
            return $this->error(null, 'Variation not found', 404);
        }

        if(SkuVariation::where('variation_id',$id)->exists()){
            return $this->error(null,"Can not delete because this variation is linked with some SKUs");
        }
        // if ($category->image) {
        //     Storage::delete($category->image);
        // }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
