<?php

namespace App\Http\Controllers\Admin;

use App\Models\SkuWarehouse;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Resources\StockResource;
use App\Models\StockKeepingUnit;

class StockController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('abilities:view_stock')->only('index', 'show');
        $this->middleware('abilities:add_stock')->only('store');
        $this->middleware('abilities:edit_stock')->only('update');
        $this->middleware('abilities:delete_stock')->only('destroy');
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

        $query = SkuWarehouse::query();

        // $query->with('warehouses');
        // $query->with('variations');
        // $query->with('variations.variation');
        // $query->with('product');

        $data = $query->paginate($limit);

        return $this->success(StockResource::collection($data)->additional(['meta' => [
            'total_page' => (int) ceil($data->total() / $data->perPage()),
        ]])->response()->getData(), 'Stock List');
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
        $checkWarehouse = SkuWarehouse::where('product_id', $request->product_id)->where('sku_id', $request->sku_id)->where('warehouse_id', $request->warehouse_id)->first();
        $checkSKU = StockKeepingUnit::where('product_id', $request->product_id)->where('id', $request->sku_id)->first();
        $comingTotalQty = 0;

        if ($checkWarehouse && $checkSKU) {
            $maxSKUQty = $checkSKU->quantity;
            $comingTotalQty = $checkWarehouse->quantity + $request->quantity;

            if ($comingTotalQty > $maxSKUQty) {
                return $this->error(null, 'The maximum SKU total quantity is ' . $maxSKUQty);
            }
            $checkWarehouse->quantity += $request->quantity;
            $checkWarehouse->update();
            return $this->success($checkWarehouse, 'Successfully Updated');
        } else {
            $comingTotalQty = $request->quantity;

            $save = SkuWarehouse::create(['product_id' => $request->product_id, 'sku_id' => $request->sku_id, 'warehouse_id' => $request->warehouse_id, 'quantity' => $request->quantity]);
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
        $find = SkuWarehouse::find($id);

        if (!$find) {
            return $this->error(null, 'Stock not found', 404);
        }

        $find->product_id = $request->product_id ?? $find->product_id;
        $find->warehouse_id = $request->warehouse_id ?? $find->warehouse_id;
        $find->sku_id = $request->sku_id ?? $find->sku_id;
        $find->quantity = $request->quantity ?? $find->quantity;
        $find->update();
        return $this->success($find, 'Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = SkuWarehouse::find($id);

        if (!$find) {
            return $this->error(null, 'Stock not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
