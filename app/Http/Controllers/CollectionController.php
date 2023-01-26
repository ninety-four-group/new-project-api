<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    use HttpResponses;

    public function getCollections()
    {
        $data = json_decode(file_get_contents(base_path("/mockup/collections.json")), null, 512, JSON_THROW_ON_ERROR);
        return $this->success($data, 'Collections List');
    }
}
