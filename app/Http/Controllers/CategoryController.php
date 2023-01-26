<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\CategoryInterface;
use App\Models\Category;

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
        $categories = Category::all();
        return $this->success($categories, 'Category List');
    }
}
