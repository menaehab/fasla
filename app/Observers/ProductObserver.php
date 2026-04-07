<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\CartItem;

class ProductObserver
{
    /**
     * Handle the Product "deleted" event.
     * Remove product from all carts when deleted
     */
    public function deleted(Product $product): void
    {
        CartItem::where('product_id', $product->id)->delete();
    }

    /**
     * Handle the Product "updated" event.
     * Check if quantity is 0 and remove from carts
     */
    public function updated(Product $product): void
    {
        if ($product->quantity == 0) {
            CartItem::where('product_id', $product->id)->delete();
        }
    }
}
