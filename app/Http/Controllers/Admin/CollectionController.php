<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CollectionInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Collection;

class CollectionController extends Controller
{

    use HttpResponses;

    protected $interface;

    public function __construct(CollectionInterface $interface)
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
        return $this->success($data, 'Collection List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCollectionRequest $request)
    {
        $data = [
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
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
        return $this->success($data, 'Collection Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCollectionRequest $request, $id)
    {
        $find = Collection::find($id);

        if (!$find) {
            return $this->error(null, 'Collection not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'mm_name' => $request->mm_name ?? $find->mm_name,
            'start_date' => $request->start_date ?? $find->start_date,
            'end_date' => $request->end_date ?? $find->end_date
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
        $data = Collection::find($id);

        if (!$data) {
            return $this->error(null, 'Collection not found', 404);
        }

        $data->delete();
        return $this->success(null, 'Successfully delete');
    }
}
