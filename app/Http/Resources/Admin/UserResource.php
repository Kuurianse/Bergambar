<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
// ArtistResource will be created later, but we add the use statement now.
use App\Http\Resources\Admin\ArtistResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'username' => $this->username, // Assuming you have a username field
            'role' => $this->role,       // Assuming 'role' is a direct attribute
            'bio' => $this->bio,
            'profile_picture' => $this->profile_picture,
            // 'is_active' => $this->is_active, // Assuming you have an is_active attribute or accessor
            // For example, if you determine active status differently:
            'is_active' => $this->email_verified_at !== null, // Example: active if email is verified
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            // Add any other fields needed by the admin panel, matching admin-panel/lib/types.ts

            // Artist related information
            'is_artist' => $this->whenLoaded('artist', fn() => true, false),
            'artist_id' => $this->whenLoaded('artist', fn() => $this->artist->id, null),
            // Optionally include full artist details if needed directly here
            // 'artist_details' => $this->whenLoaded('artist', fn() => new ArtistResource($this->artist), null),
        ];
    }
}