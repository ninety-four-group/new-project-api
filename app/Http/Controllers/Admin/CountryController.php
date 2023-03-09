<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CountryInterface;
use App\Contracts\SKUInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\StoreSKURequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Requests\UpdateSKURequest;
use App\Models\Country;
use App\Models\Region;
use App\Models\StockKeepingUnit;

class CountryController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(CountryInterface $interface)
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
        return $this->success($data, 'Country List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        $data = [
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'code' => $request->code,
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
        return $this->success($data, 'Country Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $find = Country::find($id);

        if (!$find) {
            return $this->error(null, 'Country not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'mm_name' => $request->mm_name  ?? $find->mm_name,
            'code' => $request->code  ?? $find->code,
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
        $find = Country::find($id);

        if (!$find) {
            return $this->error(null, 'Country not found', 404);
        }

        if(Region::where('country_id',$id)->exists()){
            return $this->error(null,"Can not delete because this country is linked with some regions");
        }


        // if ($category->image) {
        //     Storage::delete($category->image);
        // }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
