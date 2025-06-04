<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'description' => $this->description,
            'price' => $this->price,
            'artist_name' => $this->whenLoaded('artist', function () {
                return $this->artist && $this->artist->user ? $this->artist->user->name : null;
            }),
            'category_name' => $this->whenLoaded('category', function () {
                return $this->category ? $this->category->name : null;
            }),
            'created_at' => $this->created_at,
        ];
    }
}