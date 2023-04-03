<?php

namespace App\Http\Controllers;

use App\Contracts\BrandInterface;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class BrandController extends Controller
{
    use HttpResponses;

    protected $brand;

    public function __construct(BrandInterface $interface)
    {
        $this->brand = $interface;
    }

    public function getBrands(Request $request)
    {
        $brands = Brand::all();
        return $this->success($brands, 'Brand List');
    }
}
