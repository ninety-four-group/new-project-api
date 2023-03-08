<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Contracts\WarehouseInterface;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\ProductWarehouse;
use App\Models\SkuWarehouse;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    use HttpResponses;

    protected $warehouse;

    public function __construct(WarehouseInterface $interface)
    {
        $this->warehouse = $interface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $warehouses = $this->warehouse->all($request);
        return $this->success($warehouses, 'Warehouse List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarehouseRequest $request)
    {
        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status
        ];

        $warehouse = $this->warehouse->store($data);

        return $this->success($warehouse, 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = $this->warehouse->get($id);
        return $this->success($warehouse, 'Warehouse Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWarehouseRequest $request, $id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return $this->error(null, 'Warehouse not found', 404);
        }

        $data = [
            'name' => $request->name ?? $warehouse->name,
            'slug' => $request->name ? Str::slug($request->name) : $warehouse->slug,
            'status' => $request->status ?? $warehouse->status
        ];


        $update = $this->warehouse->update($id, $data);

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
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return $this->error(null, 'Warehouse not found', 404);
        }   

        if(ProductWarehouse::where('warehouse_id',$id)->exists() || SkuWarehouse::where('warehouse_id',$id)->exists()){
            return $this->error(null,"Can not delete because this warehouse is linked with some products");
        }

        $warehouse->delete();
        return $this->success(null, 'Successfully delete');
    }
}
