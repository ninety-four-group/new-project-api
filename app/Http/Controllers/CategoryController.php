<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\CategoryInterface;

class CategoryController extends Controller
{
    use HttpResponses;

    protected $category;

    public function __construct(CategoryInterface $interface)
    {
        $this->category = $interface;
    }

    public function getCategories(Request $request)
    {
        $categories = json_decode(file_get_contents(base_path("/mockup/categories.json")), null, 512, JSON_THROW_ON_ERROR);
        return $this->success($categories, 'Category List');
    }
}
