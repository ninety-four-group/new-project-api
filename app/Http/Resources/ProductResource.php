<?php

namespace App\Http\Resources;

use App\Http\Resources\SKUResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'name' => $this->name,
            'mm_name' => $this->mm_name,
            'description' => $this->description,
            'mm_description' => $this->mm_description,
            'category' => $this->category,
            'brand' => $this->brand,
            'video_url' => $this->video_url,
            'status' => $this->status,
            'is_popular' => $this->isPopular ? $this->isPopular->is_enabled : false,
            'is_best_selling' => $this->isBestSelling ? $this->isBestSelling->is_enabled : false,
            'is_new_arrival' => $this->isNewArrival ? $this->isNewArrival->is_enabled : false,
            'media' => MediaResource::collection($this->media),
            'tags' => TagResource::collection($this->tags),
            'collections' => CollectionResource::collection($this->collections),
            'sku' => ProductSKUResource::collection($this->sku),
            'stock' => $this->stock,
            'warehouses' => WarehouseResource::collection($this->warehouse),
            'last_updated_user' => $this->last_updated_user,
            'created_at' => date('d-m-Y h:i:s', strtotime($this->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($this->updated_at))
        ];
    }
}
