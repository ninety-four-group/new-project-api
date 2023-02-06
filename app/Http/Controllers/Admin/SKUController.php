<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\SKUInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSKURequest;

class SKUController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(SKUInterface $interface)
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
        return $this->success($data, 'SKU List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSKURequest $request)
    {
        $data = [
            'variation_id' => $request->variation_id,
            'warehouse_id' => $request->warehouse_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
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
            'variation_category_id' => $request->variation_category_id ?? $find->variation_category_id,
        ];

        if ($request->hasFile('image')) {
            if ($find->image) {
                Storage::disk('public')->delete($find->image);
            }

            $data['image'] = $request->file('image')->store('variation', 'public');
        }


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

        // if ($category->image) {
        //     Storage::delete($category->image);
        // }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
