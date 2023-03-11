<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\ProductInterface;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailResource;

class ProductController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(ProductInterface $interface)
    {
        $this->interface = $interface;
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
        $query->with(['category','brand','sku','sku.variations', 'sku.variations.variation' , 'sku.variations.variation.media']);
        $data = $query->first();


        foreach ($data['sku'] as $sku) {
            $skuVariations = $sku['variations'];
            $groupedVariations = [];
            foreach ($skuVariations as $v) {
                $type = $v['variation']['type'];
                if (!isset($groupedVariations[$type])) {
                    $groupedVariations[$type] = [$v['variation']];
                }else{
                    array_push($groupedVariations[$type], $v['variation']);
                }
            }
            unset($sku['variations']);
            $sku['variations'] = $groupedVariations;
        }


        return $this->success(new ProductDetailResource($data), 'Product Detail');
    }
}
