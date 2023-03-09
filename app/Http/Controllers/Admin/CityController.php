<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CityInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use App\Models\Region;
use App\Models\Township;

class CityController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(CityInterface $interface)
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
        return $this->success($data, 'City List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $data = [
            'region_id' => $request->region_id,
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
        return $this->success($data, 'City Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, $id)
    {
        $find = City::find($id);

        if (!$find) {
            return $this->error(null, 'City not found', 404);
        }

        $data = [
            'region_id' => $request->region_id ?? $find->region_id,
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
        $find = City::find($id);

        if (!$find) {
            return $this->error(null, 'City not found', 404);
        }

        if(Township::where('city_id',$id)->exists()){
            return $this->error(null,"Can not delete because this city is linked with some townships");
        }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
