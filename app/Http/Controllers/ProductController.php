<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\ProductInterface;
use App\Http\Resources\ProductResource;
use App\Models\Product;

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

    public function getProductDetail($id){
        $products = $this->interface->get($id);
        return $this->success($products, 'Product Detail');
    }
}
