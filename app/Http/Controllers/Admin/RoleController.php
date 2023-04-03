<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CountryInterface;
use App\Contracts\RegionInterface;
use App\Contracts\RoleInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Admin;
use App\Models\Region;
use App\Models\Role;

class RoleController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(RoleInterface $interface)
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
        return $this->success($data, 'Role List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $data = [
            'name' => $request->name,
            'permissions' => json_encode($request->permissions),
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
        return $this->success($data, 'Role Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $find = Role::find($id);

        if (!$find) {
            return $this->error(null, 'Role not found', 404);
        }

        $data = [
            'name' => $request->name  ?? $find->name,
            'permissions' => $request->permissions  ? json_encode($request->permissions) :  $find->permissions,
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
        $find = Role::find($id);

        if (!$find) {
            return $this->error(null, 'Role not found', 404);
        }

        if (Admin::where('role_id', $id)->exists()) {
            return $this->error(null, "Can not delete because this role is linked with some admins");
        }

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
