<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'quantity' =>$this->quantity,
            'store_name' => $this->seller->store_name,
            'address' => $this->seller->address,
            'images' => ProductImageResource::collection($this->images),
            'colors' => ProductColorResource::collection($this->colors),
            'sizes' => ProductSizeResource::collection($this->sizes),
        ];
    }
}