<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\ProductInterface;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailResource;
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

    public function getProductList(Request $request)
    {
        $products = $this->interface->all($request);
        return $this->success($products, 'Product List');
    }

    public function getProductDetail($id)
    {
        // $products = $this->interface->get($id);
        // return $this->success($products, 'Product Detail');
        $product = Product::find($id);

        if (!$product) {
            return $this->error(null, 'Product not found');
        }

        $query = Product::query();
        $query->where('id', $id);
        $query->with(['category', 'brand', 'sku', 'sku.variations', 'sku.variations.variation', 'sku.variations.variation.media', 'sku.variations.variation.type']);
        $data = $query->first();

        $productVariationType = ProductVariationType::where('product_id', $data['id'])->get();
        $variationTypes = collect($productVariationType)->map(function ($vt) {
            return $vt;
        });
        $sortedVariationTypes = array_values($variationTypes->sortBy('sort')->all());

        foreach ($data['sku'] as $index => $sku) {
            $groupedVariations = [];
            if (count($sku->warehouses) > 0) {
                $skuVariations = $sku['variations'];
                foreach ($skuVariations as $v) {
                    $type = $v['variation']['type']['name'];
                    if (!isset($groupedVariations[$type])) {
                        $groupedVariations[$type] = [$v['variation']];
                    } else {
                        array_push($groupedVariations[$type], $v['variation']);
                    }
                }
            } else {
                unset($data['sku'][$index]);
            }

            unset($sku['variations']);

            $sortMap = [];
            foreach ($sortedVariationTypes as $item) {
                $type = json_decode($item['type'], true);
                $sortMap[$type['name']] = $item['sort'];
            }

            // Sort the variations array in the second array based on the sort values
            uksort($groupedVariations, function ($a, $b) use ($sortMap) {
                return $sortMap[$a] - $sortMap[$b];
            });


            $sku['variations'] = $groupedVariations;
        }

        return $this->success(new ProductDetailResource($data), 'Product Detail');
    }
}
