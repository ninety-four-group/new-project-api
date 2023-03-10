<?php

namespace App\Http\Resources;

use App\Http\Resources\VariationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSKUResource extends JsonResource
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
            'code' => $this->code,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->status,
            // 'stock' => StockResource::collection($this->warehouses),
            'variations' => is_array($this->variations) ? array_map(function($d){return VariationResource::collection($d);},$this->variations) : $this->variations,
            // 'variations' =>  $this->variations,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_updated_user_id' => $this->last_updated_user_id,
        ];
    }
}
