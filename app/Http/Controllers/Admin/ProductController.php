<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ProductInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(ProductInterface $interface)
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
        return $this->success($data, 'Product List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        $data = [
            'name' => $request->name,
            'mm_name' => $request->mm_name ?? $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'video_url' => $request->video_url,
            'description' => $request->description,
            'mm_description' => $request->mm_description,
            'status' => $request->status,
            'tags' => $request->tags,
            'media' => $request->media,
            'last_updated_user_id' => auth()->user()->id
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
        return $this->success($data, 'Product Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $find = Product::find($id);

        if (!$find) {
            return $this->error(null, 'Product not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'mm_name' => $request->mm_name ?? $find->mm_name,
            'category_id' => $request->category_id ?? $find->category_id,
            'brand_id' => $request->brand_id ?? $find->brand_id,
            'video_url' => $request->video_url ?? $find->video_url,
            'description' => $request->description ?? $find->description,
            'mm_description' => $request->mm_description ?? $find->mm_description,
            'status' => $request->status ?? $find->status,
            'media_id' => (int) $request->media_id ?? $find->media_id,
            'last_updated_user_id' => auth()->user()->id
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
        $find = Product::find($id);

        if (!$find) {
            return $this->error(null, 'Product not found', 404);
        }


        $find->delete();
        return $this->success(null, 'Successfully delete');
    }
}
