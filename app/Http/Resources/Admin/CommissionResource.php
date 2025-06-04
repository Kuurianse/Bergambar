<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CommissionResource extends JsonResource
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
            'image' => $this->image,
            'status' => $this->status,
            'public_status' => $this->public_status, // Accessor
            'total_price' => $this->total_price,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user_id' => $this->user_id,
            'service_id' => $this->service_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'service' => new ServiceResource($this->whenLoaded('service')),
            // Optional relations for detail page - can be added later
            // 'orders' => OrderResource::collection($this->whenLoaded('orders')),
            // 'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            // 'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}