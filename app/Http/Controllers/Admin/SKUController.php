<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\SKUInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSKURequest;
use App\Http\Requests\UpdateSKURequest;
use App\Models\StockKeepingUnit;

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
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
            'warehouse' => $request->warehouse,
            'variation' => $request->variation,
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
    public function update(UpdateSKURequest $request, $id)
    {
        $find = StockKeepingUnit::find($id);

        if (!$find) {
            return $this->error(null, 'SKU not found', 404);
        }

        $data = [
            'warehouses' => $request->warehouses,
            'variation' => $request->variation,
            'product_id' => $request->product_id  ?? $find->product_id,
            'quantity' => $request->quantity  ?? $find->quantity,
            'price' => $request->price  ?? $find->price,
            'status' => $request->status  ?? $find->status,
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
        $find = StockKeepingUnit::find($id);

        if (!$find) {
            return $this->error(null, 'SKU not found', 404);
        }

        // if ($category->image) {
        //     Storage::delete($category->image);
        // }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
