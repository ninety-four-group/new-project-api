<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\BrandInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    use HttpResponses;

    protected $brand;

    public function __construct(BrandInterface $interface)
    {
        $this->brand = $interface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = $this->brand->all($request);
        return $this->success($brands, 'Brand List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'media_id' => $request->image,
        ];

        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('brand', 'public');
        // }

        $brand = $this->brand->store($data);

        return $this->success($brand, 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->get($id);
        return $this->success($brand, 'Brand Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return $this->error(null, 'Brand not found', 404);
        }

        $data = [
            'name' => $request->name ?? $brand->name,
            'slug' => $request->name ? Str::slug($request->name) : $brand->slug,
            'media_id' => $request->image ?? $brand->media_id,
        ];

        // if ($request->hasFile('image')) {
        //     if ($brand->image) {
        //         Storage::disk('public')->delete($brand->image);
        //     }

        //     $data['image'] = $request->file('image')->store('brand', 'public');
        // }


        $update = $this->brand->update($id, $data);

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
        $brand = Brand::find($id);

        if (!$brand) {
            return $this->error(null, 'Brand not found', 404);
        }

        // if ($category->image) {
        //     Storage::delete($category->image);
        // }

        $brand->delete();
        return $this->success(null, 'Successfully delete');
    }
}
