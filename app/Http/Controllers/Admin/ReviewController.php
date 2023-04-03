<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;

class ReviewController extends Controller
{

    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        $query = ProductReview::query();

        if ($search) {
            $query->where('review', 'LIKE', "%{$search}%");
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }


        $query->with('product');
        $query->with('user');

        $reviews = $query->paginate($limit);
        return $this->success(ProductReviewResource::collection($reviews)->additional(['meta' => [
            'total_page' => (int) ceil($reviews->total() / $reviews->perPage()),
        ]])->response()->getData(), 'Review List');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
