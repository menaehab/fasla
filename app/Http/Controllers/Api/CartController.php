<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;

class CartController extends Controller
{
    /**
     * Display the authenticated user's cart.
     */
    public function index(Request $request)
    {
        $cart = Cart::with(['cartItems.product.images', 'cartItems.product.colors', 'cartItems.product.sizes'])
            ->firstOrCreate(['user_id' => $request->user()->id]);

        return new CartResource($cart);
    }

    /**
     * Add a product to cart.
     */
    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Check if product has enough stock
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'message' => 'الكمية المطلوبة غير متوفرة في المخزون',
                'available_quantity' => $product->quantity
            ], 400);
        }

        // Get or create cart for user
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        // Check if product already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity if product already in cart
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($product->quantity < $newQuantity) {
                return response()->json([
                    'message' => 'الكمية المطلوبة غير متوفرة في المخزون',
                    'available_quantity' => $product->quantity,
                    'current_in_cart' => $cartItem->quantity
                ], 400);
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->discounted_price ?? $product->price
            ]);
        } else {
            // Add new item to cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->discounted_price ?? $product->price
            ]);
        }

        $cart->load(['cartItems.product.images', 'cartItems.product.colors', 'cartItems.product.sizes']);

        return response()->json([
            'message' => 'تم إضافة المنتج إلى السلة بنجاح',
            'cart' => new CartResource($cart)
        ], 201);
    }

    /**
     * Update cart item quantity.
     */
    public function update(UpdateCartItemRequest $request, $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check if cart belongs to authenticated user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'غير مصرح لك بتعديل هذا العنصر'], 403);
        }

        $product = $cartItem->product;

        // Check if product still exists
        if (!$product) {
            return response()->json([
                'message' => 'هذا المنتج لم يعد متوفراً'
            ], 404);
        }

        // Check if product has enough stock
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'message' => 'الكمية المطلوبة غير متوفرة في المخزون',
                'available_quantity' => $product->quantity
            ], 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'price' => $product->discounted_price ?? $product->price
        ]);

        $cart = $cartItem->cart->load(['cartItems.product.images', 'cartItems.product.colors', 'cartItems.product.sizes']);

        return response()->json([
            'message' => 'تم تحديث الكمية بنجاح',
            'cart' => new CartResource($cart)
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Request $request, $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check if cart belongs to authenticated user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'غير مصرح لك بحذف هذا العنصر'], 403);
        }

        $cartItem->delete();

        $cart = Cart::with(['cartItems.product.images', 'cartItems.product.colors', 'cartItems.product.sizes'])
            ->where('user_id', $request->user()->id)
            ->first();

        return response()->json([
            'message' => 'تم حذف المنتج من السلة بنجاح',
            'cart' => new CartResource($cart)
        ]);
    }

    /**
     * Clear all items from cart.
     */
    public function clear(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return response()->json([
            'message' => 'تم تفريغ السلة بنجاح'
        ]);
    }

    /**
     * Validate cart before checkout.
     */
    public function validateCart(Request $request)
    {
        $cart = Cart::with(['cartItems.product'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart || $cart->cartItems->count() === 0) {
            return response()->json([
                'can_checkout' => false,
                'message' => 'السلة فارغة'
            ], 400);
        }

        $unavailableItems = [];
        $updatedItems = [];

        foreach ($cart->cartItems as $item) {
            if (!$item->product) {
                $unavailableItems[] = [
                    'cart_item_id' => $item->id,
                    'message' => 'المنتج لم يعد متوفراً'
                ];
            } elseif ($item->product->quantity < $item->quantity) {
                $unavailableItems[] = [
                    'cart_item_id' => $item->id,
                    'product_name' => $item->product->name,
                    'requested_quantity' => $item->quantity,
                    'available_quantity' => $item->product->quantity,
                    'message' => 'الكمية المطلوبة غير متوفرة'
                ];
            } elseif ($item->price !== ($item->product->discounted_price ?? $item->product->price)) {
                // Update price if changed
                $item->update(['price' => $item->product->discounted_price ?? $item->product->price]);
                $updatedItems[] = [
                    'cart_item_id' => $item->id,
                    'product_name' => $item->product->name,
                    'new_price' => $item->price
                ];
            }
        }

        if (count($unavailableItems) > 0) {
            return response()->json([
                'can_checkout' => false,
                'message' => 'بعض المنتجات في السلة غير متوفرة',
                'unavailable_items' => $unavailableItems,
                'updated_items' => $updatedItems
            ], 400);
        }

        return response()->json([
            'can_checkout' => true,
            'message' => 'السلة جاهزة للدفع',
            'updated_items' => $updatedItems,
            'cart' => new CartResource($cart->load(['cartItems.product.images', 'cartItems.product.colors', 'cartItems.product.sizes']))
        ]);
    }
}
