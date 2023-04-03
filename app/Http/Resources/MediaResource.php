<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
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
            'title' => $this->title,
            'caption' => $this->caption,
            'alt_text' => $this->alt_text,
            'description' => $this->description,
            'file' => Storage::disk('public')->url('media/'.$this->file),
            'thumbnail' => Storage::disk('public')->url('media/thumbnails/'.$this->file),
        ];
    }
}
