<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
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
            'user_id' => $this->user_id,
            'portfolio_link' => $this->portfolio_link,
            'is_verified' => (bool) $this->is_verified,
            'rating' => $this->rating,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            // Eager load user information. 
            // You might want to create a simpler UserResource for this context 
            // if UserResource itself is too heavy or contains sensitive admin-only data.
            // For now, we use the existing UserResource.
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}