<?php

namespace App\Http\Resources;

use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class SubSubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mm_name' => $this->mm_name,
            'slug' => $this->slug,
            'image' => new MediaResource($this->media),
            'highlight_flag' => $this->highlight_flag,
            'created_at' => date('d-m-Y h:i:s', strtotime($this->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($this->updated_at))
        ];
    }
}
