<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\TagInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

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
            'mm_name' => $request->mm_name ?? $request->name,
            'status' => $request->status
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

        $find = Tag::find($id);

        if (!$find) {
            return $this->error(null, 'Tag not found', 404);
        }

        $data = $this->interface->get($id);
        return $this->success($data, 'Tag Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $find = Tag::find($id);

        if (!$find) {
            return $this->error(null, 'Tag not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'mm_name' => $request->mm_name ?? $find->mm_name,
            'status' => $request->status ?? $find->status
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
        $data = Tag::find($id);

        if (!$data) {
            return $this->error(null, 'Tag not found', 404);
        }

        $data->delete();
        return $this->success(null, 'Successfully delete');
    }
}
