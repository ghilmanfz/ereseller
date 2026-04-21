<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_whatsapp' => ['required', 'string', 'max:20'],
            'recipient_address' => ['nullable', 'string', 'max:1000'],
            'shipping_method' => ['required', 'in:pickup,delivery'],
            'payment_method' => ['required', 'in:transfer,ewallet,cod,pay_at_store'],
        ]);

        if ($data['shipping_method'] === 'delivery' && empty(trim((string) ($data['recipient_address'] ?? '')))) {
            return back()->withInput()->withErrors([
                'recipient_address' => 'Alamat wajib diisi untuk pengiriman ke alamat.',
            ]);
        }

        if ($data['shipping_method'] === 'delivery' && $data['payment_method'] === 'pay_at_store') {
            return back()->withInput()->withErrors([
                'payment_method' => 'Bayar di toko hanya tersedia untuk metode ambil di toko.',
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cart = Cart::query()->where('user_id', $user->id)->firstOrFail();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $subtotal = (int) $items->sum(fn ($item) => $item->quantity * (float) $item->product->price);
        $shippingCost = $data['shipping_method'] === 'pickup' ? 0 : 15000;
        $initialStatus = $this->determineInitialStatus($data['shipping_method'], $data['payment_method']);

        $recipientAddress = $data['shipping_method'] === 'pickup'
            ? 'Ambil di toko SR12 Parungpanjang'
            : (string) $data['recipient_address'];

        $order = DB::transaction(function () use ($user, $items, $data, $subtotal, $shippingCost, $cart, $initialStatus, $recipientAddress) {
            $order = Order::query()->create([
                'order_code' => $this->generateOrderCode(),
                'user_id' => $user->id,
                'recipient_name' => $data['recipient_name'],
                'recipient_whatsapp' => $data['recipient_whatsapp'],
                'recipient_address' => $recipientAddress,
                'shipping_method' => $data['shipping_method'],
                'payment_method' => $data['payment_method'],
                'status' => $initialStatus,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $subtotal + $shippingCost,
                'ready_for_pickup_at' => $initialStatus === 'ready_for_pickup' ? now() : null,
            ]);

            foreach ($items as $item) {
                $lineTotal = (float) $item->product->price * $item->quantity;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'line_total' => $lineTotal,
                ]);

                $stockBefore = $item->product->stock;
                $item->product->decrement('stock', $item->quantity);

                $item->product->stockLogs()->create([
                    'user_id' => $user->id,
                    'action' => 'order_checkout',
                    'quantity_delta' => -$item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => max(0, $stockBefore - $item->quantity),
                    'note' => 'Order '.$order->order_code,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect('/pesanan/'.$order->order_code.'/konfirmasi');
    }

    private function generateOrderCode(): string
    {
        return 'SR12-'.now()->format('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 4));
    }

    private function determineInitialStatus(string $shippingMethod, string $paymentMethod): string
    {
        if (in_array($paymentMethod, ['transfer', 'ewallet'], true)) {
            return 'pending_payment';
        }

        if ($shippingMethod === 'pickup') {
            return 'ready_for_pickup';
        }

        return 'awaiting_shipment_cod';
    }
}
