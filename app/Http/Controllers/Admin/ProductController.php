<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ProductInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductVariationTypeResource;
use App\Models\Product;
use App\Models\ProductVariationType;

class ProductController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(ProductInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware('abilities:view_product')->only('index', 'show');
        $this->middleware('abilities:add_product')->only('store');
        $this->middleware('abilities:edit_product')->only('update');
        $this->middleware('abilities:delete_product')->only('destroy');
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
            'warehouses' => $request->warehouses,
            'tags' => $request->tags,
            'collections' => $request->collections,
            'media' => $request->media,
            'last_updated_user_id' => null
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
        $query = Product::whereId($id);

        $query->with('tags');
        $query->with('media');
        $query->with('warehouse');

        $find = $query->first();


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
            'tags' => $request->tags,
            'media' => $request->media ?? $find->media,
            'warehouses' => $request->warehouses ?? $find->warehouses,
            'collections' => $request->collections ?? $find->collections,
            'last_updated_user_id' => null
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

        $find->warehouse()->detach();
        $find->tags()->detach();
        $find->media()->detach();

        $find->delete();
        return $this->success(null, 'Successfully delete');
    }

    public function getProductVariation(Request $request)
    {
        $variations = ProductVariationType::where('product_id', $request->id)->with('product')->orderBy('sort', 'asc')->get();
        return $this->success(ProductVariationTypeResource::collection($variations), 'Variations List');
    }

    public function sortProductVariation(Request $request)
    {
        $request->validate(['sort' => 'required']);
        foreach ($request->sort as $each) {
            ProductVariationType::where('id', $each['id'])->update(['sort' => $each['sort']]);
        }

        return $this->success(null, 'Successfully updated');
    }

    public function toggleNewArrival($id)
    {
        $product = Product::find($id);
        if ($product->isNewArrival) {
            if ($product->isNewArrival->is_enabled) {
                $product->isNewArrival()->update(['is_enabled' => false]);
            } else {
                $product->isNewArrival()->update(['is_enabled' => true]);
            }
        } else {
            $product->isNewArrival()->create([
                'product_id' => $id,
                'is_enabled' => true
            ]);
        }

        return $this->success($product, 'Successfully updated');
    }

    public function toggleBestSelling($id)
    {
        $product = Product::find($id);
        if ($product->isBestSelling) {
            if ($product->isBestSelling->is_enabled) {
                $product->isBestSelling()->update(['is_enabled' => false]);
            } else {
                $product->isBestSelling()->update(['is_enabled' => true]);
            }
        } else {
            $product->isBestSelling()->create([
                'product_id' => $id,
                'is_enabled' => true
            ]);
        }

        return $this->success($product, 'Successfully updated');
    }

    public function togglePopular($id)
    {
        $product = Product::find($id);
        if ($product->isPopular) {
            if ($product->isPopular->is_enabled) {
                $product->isPopular()->update(['is_enabled' => false]);
            } else {
                $product->isPopular()->update(['is_enabled' => true]);
            }
        } else {
            $product->isPopular()->create([
                'product_id' => $id,
                'is_enabled' => true
            ]);
        }
        return $this->success($product, 'Successfully updated');
    }
}
