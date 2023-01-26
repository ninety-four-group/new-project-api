<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use HttpResponses;

    public function getTags()
    {
        $data = json_decode(file_get_contents(base_path("/mockup/tags.json")), null, 512, JSON_THROW_ON_ERROR);
        return $this->success($data, 'Tag List');
    }
}
