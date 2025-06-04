<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_code' => $this->order_code, // Assuming order_code exists
            'order_date' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'total_price' => $this->total_price,
            'customer_name' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }),
            'customer_email' => $this->whenLoaded('user', function () {
                return $this->user->email;
            }),
            'commission_id' => $this->whenLoaded('commission', function () {
                return $this->commission->id;
            }),
            'commission_title' => $this->whenLoaded('commission', function () {
                return $this->commission->title;
            }),
            'artist_name' => $this->whenLoaded('commission', function () {
                return $this->commission->user ? $this->commission->user->name : null; // Artist is user of commission
            }),
            'artist_email' => $this->whenLoaded('commission', function () {
                return $this->commission->user ? $this->commission->user->email : null; // Artist is user of commission
            }),
            'payment_status' => $this->whenLoaded('payments', function () {
                // Assuming the latest payment reflects the overall payment status
                // This might need more complex logic based on your payment model
                return $this->payments->isNotEmpty() ? $this->payments->last()->status : 'pending';
            }),
            'payment_method' => $this->whenLoaded('payments', function () {
                // Assuming the latest payment reflects the payment method
                return $this->payments->isNotEmpty() ? $this->payments->last()->payment_method : null;
            }),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include other relations if needed, e.g., payment details
            'user' => new UserResource($this->whenLoaded('user')),
            'commission' => new CommissionResource($this->whenLoaded('commission')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')), // Assuming PaymentResource exists
        ];
    }
}