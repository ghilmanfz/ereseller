<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('pages.order-history', [
            'orders' => $orders,
        ]);
    }

    public function confirmation(string $orderCode): View
    {
        $order = $this->customerOrder($orderCode);

        return view('pages.order-confirmation', [
            'order' => $order,
            'paymentAccounts' => PaymentAccount::query()->where('is_active', true)->get(),
        ]);
    }

    public function markPaid(Request $request, string $orderCode): RedirectResponse
    {
        $order = $this->customerOrder($orderCode);

        if (! in_array($order->payment_method, ['transfer', 'ewallet'], true)) {
            return back()->with('error', 'Konfirmasi pembayaran hanya untuk transfer atau e-wallet.');
        }

        $validated = $request->validate([
            'payment_proof' => ['required', 'image', 'max:4096'],
        ]);

        $proofPath = $validated['payment_proof']->store('payment-proofs', 'public');

        if (in_array($order->status, ['pending_payment', 'payment_submitted'], true)) {
            $order->update([
                'status' => 'payment_submitted',
                'payment_proof_path' => $proofPath,
                'payment_proof_uploaded_at' => now(),
            ]);
        }

        return redirect('/pesanan/'.$order->order_code)->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function markCompleted(string $orderCode): RedirectResponse
    {
        $order = $this->customerOrder($orderCode);

        if (! in_array($order->status, ['shipped', 'ready_for_pickup'], true)) {
            return back()->with('error', 'Pesanan belum bisa ditandai selesai.');
        }

        $payload = [
            'status' => 'completed',
            'completed_at' => now(),
        ];

        if (in_array($order->payment_method, ['cod', 'pay_at_store'], true) && ! $order->paid_at) {
            $payload['paid_at'] = now();
        }

        $order->update($payload);

        return back()->with('success', 'Pesanan ditandai selesai. Terima kasih sudah berbelanja.');
    }

    public function tracking(string $orderCode): View
    {
        $order = $this->customerOrder($orderCode);

        return view('pages.order-tracking', [
            'order' => $order,
        ]);
    }

    private function customerOrder(string $orderCode): Order
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return Order::query()
            ->with(['items', 'items.product'])
            ->where('order_code', $orderCode)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }
}
