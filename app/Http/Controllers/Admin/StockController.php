<?php

namespace App\Http\Controllers\Admin;

use App\Models\SkuWarehouse;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Resources\StockResource;

class StockController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockRequest $request)
    {
        $checkWarehouse = SkuWarehouse::where('product_id',$request->product_id)->where('sku_id', $request->sku_id)->where('warehouse_id', $request->warehouse_id)->first();
       
        if ($checkWarehouse) {
            $checkWarehouse->quantity += $request->quantity;
            $checkWarehouse->update();
            return $this->success($checkWarehouse, 'Successfully Updated');
        } else {
            $save = SkuWarehouse::create(['product_id' => $request->product_id , 'sku_id' => $request->sku_id , 'warehouse_id' => $request->warehouse_id,'quantity' =>$request->quantity]);
            return $this->success($save, 'Successfully Created');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $find = SkuWarehouse::with('warehouse')->with('sku')->find($id);

        if (!$find) {
            return $this->error(null, 'Stock not found', 404);
        }

        return $this->success(new StockResource($find), 'Stock Detail');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
