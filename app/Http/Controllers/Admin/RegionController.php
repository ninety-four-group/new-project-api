<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CountryInterface;
use App\Contracts\RegionInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Requests\UpdateSKURequest;
use App\Models\Region;
use App\Models\StockKeepingUnit;

class RegionController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(RegionInterface $interface)
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
        return $this->success($data, 'Region List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegionRequest $request)
    {
        $data = [
            'country_id' => $request->country_id,
            'name' => $request->name,
            'mm_name' => $request->mm_name,
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
        return $this->success($data, 'Region Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegionRequest $request, $id)
    {
        $find = Region::find($id);

        if (!$find) {
            return $this->error(null, 'Region not found', 404);
        }

        $data = [
            'country_id' => $request->country_id ?? $find->country_id,
            'name' => $request->name  ?? $find->name,
            'mm_name' => $request->mm_name  ?? $find->mm_name,
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
        $find = Region::find($id);

        if (!$find) {
            return $this->error(null, 'Region not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
