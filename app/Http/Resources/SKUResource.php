<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SKUResource extends JsonResource
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
            'variations' => $this->variations,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'product' => new SKUProductResource($this->product),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'last_updated_user_id' => $this->last_updated_user_id,
        ];
    }
}
