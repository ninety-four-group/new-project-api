<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use HttpResponses;

    public function getTags()
    {
        $data = Tag::all();
        return $this->success($data, 'Tag List');
    }
}
