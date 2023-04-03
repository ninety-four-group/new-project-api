<?php

namespace App\Http\Controllers\Admin;

use App\Models\Township;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\TownshipInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTownshipRequest;
use App\Http\Requests\UpdateTownshipRequest;

class TownshipController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(TownshipInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware('abilities:view_township')->only('index', 'show');
        $this->middleware('abilities:add_township')->only('store');
        $this->middleware('abilities:edit_township')->only('update');
        $this->middleware('abilities:delete_township')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->interface->all($request);
        return $this->success($data, 'Township List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTownshipRequest $request)
    {
        $data = [
            'city_id' => $request->city_id,
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
        return $this->success($data, 'Township Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTownshipRequest $request, $id)
    {
        $find = Township::find($id);

        if (!$find) {
            return $this->error(null, 'Township not found', 404);
        }

        $data = [
            'city_id' => $request->city_id ?? $find->city_id,
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
        $find = Township::find($id);

        if (!$find) {
            return $this->error(null, 'Township not found', 404);
        }



        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
