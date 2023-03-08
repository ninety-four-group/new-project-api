<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\CategoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    use HttpResponses;

    protected $category;

    public function __construct(CategoryInterface $interface)
    {
        $this->category = $interface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->category->all($request);

        return $this->success($categories, 'Category List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = [
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'highlight_flag' => $request->highlight_flag,
            'media_id' => $request->image,
        ];


        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('category', 'public');
        // }

        $category = $this->category->store($data);

        return $this->success($category, 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->category->get($id);
        return $this->success($category, 'Category Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->error(null, 'Category not found', 404);
        }

        $data = [
            'name' => $request->name ?? $category->name,
            'mm_name' => $request->mm_name ?? $category->mm_name,
            'slug' => $request->name ? Str::slug($request->name) : $category->slug,
            'parent_id' => $request->parent_id ?? $category->parent_id,
            'highlight_flag' => $request->highlight_flag ?? $category->highlight_flag,
            'media_id' => $request->image ?? $category->media_id,

        ];

        // if ($request->hasFile('image')) {
        //     if ($category->image) {
        //         Storage::disk('public')->delete($category->image);
        //     }

        //     $data['image'] = $request->file('image')->store('category', 'public');
        // }


        $update = $this->category->update($id, $data);

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
        $category = Category::find($id);

        if (!$category) {
            return $this->error(null, 'Category not found', 404);
        }

        if(Product::where('category_id',$id)->exists()){
            return $this->error(null,"Can not delete because this category is linked with some products");
        }

        if(Category::where('parent_id',$id)->exists()){
            return $this->error(null,"Can not delete because this category is linked with some category");
        }

        // if ($category->image) {
        //     Storage::delete($category->image);
        // }

        $category->delete();
        return $this->success(null, 'Successfully delete');
    }
}
