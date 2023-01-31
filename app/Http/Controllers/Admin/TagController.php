<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\TagInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;

class TagController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(TagInterface $interface)
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
        return $this->success($data, 'Tag List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request)
    {
        $data = [
            'name' => $request->name,
            'mm_name' => $request->mm_name,
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

        $warehouse->delete();
        return $this->success(null, 'Successfully delete');
    }
}
