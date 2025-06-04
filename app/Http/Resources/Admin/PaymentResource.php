<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'transaction_id' => $this->transaction_id, // Assuming this field exists
            'payment_date' => $this->payment_date ? $this->payment_date->format('Y-m-d H:i:s') : ($this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null),
            'notes' => $this->notes, // Assuming this field exists
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}