<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'seller_id' => $this->seller_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'store_name' => $this->seller->store_name,
            'price' => $this->price,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'subTotal' => $this->subTotal,
        ];
    }
}
