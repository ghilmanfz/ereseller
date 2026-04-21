<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->currentCart();
        $items = $cart->items()->with('product.category')->get();

        $subtotal = (int) $items->sum(fn ($item) => $item->quantity * (float) $item->product->price);
        $shippingCost = $subtotal > 0 ? 15000 : 0;

        return view('pages.cart-checkout', [
            'cartItems' => $items,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
        ]);
    }

    public function add(Request $request, string $slug): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        if ($product->stock <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        $qty = (int) ($data['quantity'] ?? 1);
        $cart = $this->currentCart();

        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $newQty = ($item->exists ? $item->quantity : 0) + $qty;
        $item->quantity = min($newQty, $product->stock);
        $item->save();

        return redirect('/keranjang')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function updateQuantity(Request $request, int $itemId): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->currentCart();
        $item = $cart->items()->with('product')->findOrFail($itemId);

        $item->quantity = min((int) $data['quantity'], $item->product->stock);
        $item->save();

        return back();
    }

    public function remove(int $itemId): RedirectResponse
    {
        $cart = $this->currentCart();
        $item = $cart->items()->findOrFail($itemId);
        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    private function currentCart(): Cart
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return Cart::query()->firstOrCreate([
            'user_id' => $user->id,
        ]);
    }
}
