<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\CategoryInterface;
use App\Http\Resources\CategoryResource;
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

        if($request->level){
            if($request->level === "one"){
                $categories = Category::where('parent_id' , null)->get();
            }else if($request->level === "two"){
                $main_categories = Category::where('parent_id' , null)->select('id')->pluck('id');
                $categories = Category::whereIn('parent_id',$main_categories)->get();
            }else if($request->level === "three"){
                $main_categories = Category::where('parent_id' , null)->select('id')->pluck('id');
                $sub_categories = Category::whereIn('parent_id',$main_categories)->select('id')->pluck('id');
                $categories = Category::whereIn('parent_id',$sub_categories)->get();
            }else{
                $categories = Category::all();
            }
        }else{
            $categories = Category::all();
        }

        return $this->success(CategoryResource::collection($categories), 'Category List');
    }

}
