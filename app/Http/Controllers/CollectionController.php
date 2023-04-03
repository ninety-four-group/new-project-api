<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\CollectionInterface;

class CollectionController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(CollectionInterface $interface)
    {
        $this->interface = $interface;
    }

    public function getCollections(Request $request)
    {
        $data = $this->interface->all($request);
        return $this->success($data, 'Collection List');
    }
}
