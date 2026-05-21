<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OrderDummySeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::query()->where('role', 'customer')->first();
        if (! $customer) {
            return;
        }

        $products = Product::query()->where('is_active', true)->orderBy('id')->take(8)->get();
        if ($products->isEmpty()) {
            return;
        }

        $dummyOrders = [
            ['code' => 'ORD-DM-1001', 'days_ago' => 7, 'shipping' => 'delivery', 'payment' => 'transfer', 'status' => 'completed', 'items' => [[0, 2], [1, 1]]],
            ['code' => 'ORD-DM-1002', 'days_ago' => 6, 'shipping' => 'delivery', 'payment' => 'ewallet', 'status' => 'shipped', 'items' => [[2, 2], [0, 1]]],
            ['code' => 'ORD-DM-1003', 'days_ago' => 5, 'shipping' => 'pickup', 'payment' => 'transfer', 'status' => 'ready_for_pickup', 'items' => [[3, 1], [1, 2]]],
            ['code' => 'ORD-DM-1004', 'days_ago' => 4, 'shipping' => 'delivery', 'payment' => 'cod', 'status' => 'awaiting_shipment_cod', 'items' => [[4, 1], [5, 1]]],
            ['code' => 'ORD-DM-1005', 'days_ago' => 4, 'shipping' => 'delivery', 'payment' => 'transfer', 'status' => 'processing', 'items' => [[0, 1], [2, 1], [3, 1]]],
            ['code' => 'ORD-DM-1006', 'days_ago' => 3, 'shipping' => 'pickup', 'payment' => 'pay_at_store', 'status' => 'ready_for_pickup', 'items' => [[1, 1], [6, 1]]],
            ['code' => 'ORD-DM-1007', 'days_ago' => 2, 'shipping' => 'delivery', 'payment' => 'transfer', 'status' => 'payment_submitted', 'items' => [[0, 2], [7, 1]]],
            ['code' => 'ORD-DM-1008', 'days_ago' => 2, 'shipping' => 'delivery', 'payment' => 'ewallet', 'status' => 'payment_submitted', 'items' => [[2, 1], [3, 1]]],
            ['code' => 'ORD-DM-1009', 'days_ago' => 1, 'shipping' => 'delivery', 'payment' => 'transfer', 'status' => 'pending_payment', 'items' => [[4, 2]]],
            ['code' => 'ORD-DM-1010', 'days_ago' => 1, 'shipping' => 'delivery', 'payment' => 'cod', 'status' => 'awaiting_shipment_cod', 'items' => [[5, 1], [1, 1]]],
            ['code' => 'ORD-DM-1011', 'days_ago' => 0, 'shipping' => 'pickup', 'payment' => 'pay_at_store', 'status' => 'ready_for_pickup', 'items' => [[6, 2]]],
            ['code' => 'ORD-DM-1012', 'days_ago' => 0, 'shipping' => 'delivery', 'payment' => 'transfer', 'status' => 'cancelled', 'items' => [[7, 1], [0, 1]]],
        ];

        foreach ($dummyOrders as $index => $data) {
            $createdAt = Carbon::now()->subDays((int) $data['days_ago'])->setTime(10 + ($index % 8), 15, 0);
            $items = $this->buildItems($products, collect($data['items']));
            $subtotal = $items->sum('line_total');
            $shippingCost = $data['shipping'] === 'pickup' ? 0 : 10000;
            $total = $subtotal + $shippingCost;

            $order = Order::query()->updateOrCreate(
                ['order_code' => $data['code']],
                [
                    'user_id' => $customer->id,
                    'recipient_name' => $customer->name,
                    'recipient_whatsapp' => $customer->whatsapp,
                    'recipient_address' => $customer->address,
                    'shipping_method' => $data['shipping'],
                    'payment_method' => $data['payment'],
                    'status' => $data['status'],
                    'tracking_number' => str_starts_with($data['status'], 'ship') || $data['status'] === 'completed' ? 'RESI-DM-'.($index + 1001) : null,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'payment_proof_uploaded_at' => in_array($data['status'], ['payment_submitted', 'processing', 'shipped', 'ready_for_pickup', 'completed'], true)
                        && in_array($data['payment'], ['transfer', 'ewallet'], true)
                        ? $createdAt->copy()->addHours(1)
                        : null,
                    'paid_at' => in_array($data['status'], ['processing', 'shipped', 'ready_for_pickup', 'completed'], true)
                        ? $createdAt->copy()->addHours(2)
                        : null,
                    'processing_at' => in_array($data['status'], ['processing', 'shipped', 'completed'], true)
                        ? $createdAt->copy()->addHours(3)
                        : null,
                    'shipped_at' => in_array($data['status'], ['shipped', 'completed'], true)
                        ? $createdAt->copy()->addHours(8)
                        : null,
                    'ready_for_pickup_at' => in_array($data['status'], ['ready_for_pickup', 'completed'], true) && $data['shipping'] === 'pickup'
                        ? $createdAt->copy()->addHours(5)
                        : null,
                    'completed_at' => $data['status'] === 'completed' ? $createdAt->copy()->addDay() : null,
                    'cancelled_at' => $data['status'] === 'cancelled' ? $createdAt->copy()->addHours(4) : null,
                    'created_at' => $createdAt,
                    'updated_at' => Carbon::now(),
                ]
            );

            OrderItem::query()->where('order_id', $order->id)->delete();

            foreach ($items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }
        }
    }

    private function buildItems(Collection $products, Collection $itemMap): Collection
    {
        return $itemMap->map(function (array $row) use ($products): array {
            $product = $products->get($row[0]) ?? $products->first();
            $qty = max((int) ($row[1] ?? 1), 1);
            $price = (int) $product->price;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $price,
                'quantity' => $qty,
                'line_total' => $price * $qty,
            ];
        });
    }
}
