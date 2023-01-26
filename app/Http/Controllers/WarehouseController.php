<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInterface;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class WarehouseController extends Controller
{
    use HttpResponses;

    protected $warehouse;

    public function __construct(WarehouseInterface $interface)
    {
        $this->warehouse = $interface;
    }

    public function getWarehouses(Request $request)
    {
        $warehouses = Warehouse::all();
        return $this->success($warehouses, 'Warehouse List');
    }
}
