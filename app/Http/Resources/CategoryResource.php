<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent' => $this->parent,
            'parent_id' => $this->parent_id,
            'id' => $this->id,
            'name' => $this->name,
            'mm_name' => $this->mm_name,
            'slug' => $this->slug,
            'image' => Storage::disk('public')->url($this->image),
            'highlight_flag' => $this->highlight_flag,
            'subcategory' => SubCategoryResource::collection($this->subcategory),
            'created_at' => date('d-m-Y h:i:s', strtotime($this->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($this->updated_at))
        ];
    }
}
