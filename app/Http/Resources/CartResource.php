<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cartItems = $this->cartItems->load('product');
        $hasUnavailableItems = $cartItems->some(function ($item) {
            return !$item->product || $item->product->quantity < $item->quantity;
        });

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'items' => CartItemResource::collection($cartItems),
            'total_items' => $cartItems->count(),
            'total_price' => $this->getTotalPrice(),
            'can_checkout' => !$hasUnavailableItems && $cartItems->count() > 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
