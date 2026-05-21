<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $from = $request->string('from')->toString() !== ''
            ? Carbon::parse($request->string('from')->toString())->startOfDay()
            : now()->startOfMonth();

        $to = $request->string('to')->toString() !== ''
            ? Carbon::parse($request->string('to')->toString())->endOfDay()
            : now()->endOfDay();

        $baseOrders = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->when($request->filled('payment'), function ($query) use ($request): void {
                $query->where('payment_method', $request->string('payment')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status')->toString());
            });

        $orderCount = (clone $baseOrders)->count();
        $revenue = (int) (clone $baseOrders)
            ->whereIn('status', ['processing', 'shipped', 'ready_for_pickup', 'completed'])
            ->sum('total');
        $aov = (int) ((clone $baseOrders)->avg('total') ?? 0);

        $trendBase = (clone $baseOrders);
        if (! $request->filled('status')) {
            $trendBase->whereIn('status', ['processing', 'shipped', 'ready_for_pickup', 'completed']);
        }

        $trendRows = $trendBase
            ->selectRaw('DATE(created_at) as day, SUM(total) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $trendMap = $trendRows->pluck('total', 'day');
        $trendLabels = [];
        $trendValues = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $date = $cursor->toDateString();
            $trendLabels[] = $cursor->translatedFormat('d M');
            $trendValues[] = (int) ($trendMap[$date] ?? 0);
            $cursor->addDay();
        }

        $roleCounts = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('pages.admin.dashboard', [
            'activeUsers' => User::query()->where('status', 'active')->count(),
            'orderCount' => $orderCount,
            'revenue' => $revenue,
            'aov' => $aov,
            'lowStockProducts' => Product::query()->where('stock', '<=', 10)->orderBy('stock')->limit(5)->get(),
            'recentOrders' => (clone $baseOrders)->latest()->limit(6)->get(),
            'roleCounts' => [
                'admin' => (int) ($roleCounts['admin'] ?? 0),
                'owner' => (int) ($roleCounts['owner'] ?? 0),
                'customer' => (int) ($roleCounts['customer'] ?? 0),
            ],
            'filters' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'payment' => $request->string('payment')->toString(),
                'status' => $request->string('status')->toString(),
            ],
            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,
        ]);
    }

    public function getNotifications(): \Illuminate\Http\JsonResponse
    {
        $notifications = collect();

        // Recent pending payment orders
        $pendingPayments = Order::query()
            ->where('status', 'payment_submitted')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'payment_pending',
                    'title' => 'Pembayaran Menunggu Verifikasi',
                    'message' => "{$order->recipient_name} - {$order->order_code}",
                    'timestamp' => $order->updated_at,
                    'order_id' => $order->id,
                ];
            });

        // Recent new orders
        $newOrders = Order::query()
            ->where('status', 'pending_payment')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'new_order',
                    'title' => 'Pesanan Baru',
                    'message' => "{$order->recipient_name} - Rp " . number_format($order->total, 0, ',', '.'),
                    'timestamp' => $order->created_at,
                    'order_id' => $order->id,
                ];
            });

        // Ready for pickup orders
        $readyPickup = Order::query()
            ->where('status', 'ready_for_pickup')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'ready_pickup',
                    'title' => 'Siap Diambil',
                    'message' => "{$order->recipient_name} - {$order->order_code}",
                    'timestamp' => $order->ready_for_pickup_at ?? $order->updated_at,
                    'order_id' => $order->id,
                ];
            });

        $notifications = $pendingPayments
            ->concat($newOrders)
            ->concat($readyPickup)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->count(),
        ]);
    }

    public function orders(Request $request): View
    {
        $orders = Order::query()
            ->with('user')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('order_code', 'like', '%'.$keyword.'%')
                        ->orWhere('recipient_name', 'like', '%'.$keyword.'%')
                        ->orWhere('recipient_whatsapp', 'like', '%'.$keyword.'%');
                });
            })
            ->when($request->filled('payment'), function ($query) use ($request): void {
                $query->where('payment_method', $request->string('payment')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status')->toString());
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pages.admin.orders', [
            'orders' => $orders,
            'currentQuery' => $request->string('q')->toString(),
            'currentPayment' => $request->string('payment')->toString(),
            'currentStatus' => $request->string('status')->toString(),
        ]);
    }

    public function bulkVerifyPayment(Request $request): RedirectResponse
    {
        $orderIds = $request->input('order_ids', []);
        if (empty($orderIds)) {
            return back()->with('error', 'Pilih setidaknya satu pesanan');
        }

        $orders = Order::query()->whereIn('id', $orderIds)->get();
        $verifiedCount = 0;

        foreach ($orders as $order) {
            if (($order->payment_method === 'transfer' || $order->payment_method === 'ewallet')
                && $order->status === 'payment_submitted') {
                $order->update([
                    'status' => $order->shipping_method === 'pickup' ? 'ready_for_pickup' : 'processing',
                    'paid_at' => now(),
                ]);
                $verifiedCount++;
            }
        }

        return back()->with('success', "Verifikasi pembayaran {$verifiedCount} pesanan berhasil");
    }

    public function bulkAdvanceStatus(Request $request): RedirectResponse
    {
        $orderIds = $request->input('order_ids', []);
        if (empty($orderIds)) {
            return back()->with('error', 'Pilih setidaknya satu pesanan');
        }

        $orders = Order::query()->whereIn('id', $orderIds)->get();
        $advancedCount = 0;

        foreach ($orders as $order) {
            if (in_array($order->status, ['awaiting_shipment_cod', 'processing'], true)) {
                $order->update(['status' => 'shipped', 'shipped_at' => now()]);
                $advancedCount++;
            } elseif ($order->status === 'shipped') {
                $order->update(['status' => 'completed', 'completed_at' => now()]);
                $advancedCount++;
            }
        }

        return back()->with('success', "Status {$advancedCount} pesanan berhasil diperbarui");
    }

    public function changeOrderStatus(Order $order, Request $request): RedirectResponse
    {
        $newStatus = $request->string('new_status')->toString();

        // Valid status transitions
        $allowedTransitions = [
            'pending_payment' => ['payment_submitted', 'cancelled'],
            'payment_submitted' => ['processing', 'ready_for_pickup', 'cancelled'],
            'awaiting_shipment_cod' => ['shipped', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['completed', 'cancelled'],
            'ready_for_pickup' => ['completed', 'cancelled'],
        ];

        $currentStatus = $order->status;

        // Validate transition
        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus], true)) {
            return back()->with('error', "Transisi status dari {$currentStatus} ke {$newStatus} tidak diizinkan");
        }

        // Update timestamps based on status
        $updates = ['status' => $newStatus];

        if ($newStatus === 'shipped') {
            $updates['shipped_at'] = now();
        } elseif ($newStatus === 'completed') {
            $updates['completed_at'] = now();
        } elseif ($newStatus === 'processing') {
            $updates['processing_at'] = now();
        } elseif ($newStatus === 'ready_for_pickup') {
            $updates['ready_for_pickup_at'] = now();
        } elseif ($newStatus === 'cancelled') {
            $updates['cancelled_at'] = now();
        }

        $order->update($updates);

        return back()->with('success', "Status pesanan diubah menjadi " . $this->getStatusLabel($newStatus));
    }

    private function getStatusLabel(string $status): string
    {
        $labels = [
            'pending_payment' => 'Menunggu Pembayaran',
            'payment_submitted' => 'Menunggu Verifikasi',
            'awaiting_shipment_cod' => 'Menunggu Pengiriman (COD)',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'ready_for_pickup' => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$status] ?? $status;
    }

    public function products(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('name', 'like', '%'.$keyword.'%')
                        ->orWhere('slug', 'like', '%'.$keyword.'%');
                });
            })
            ->when($request->filled('category'), function ($query) use ($request): void {
                $query->where('category_id', (int) $request->input('category'));
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $status = $request->string('status')->toString();
                if ($status === 'active') {
                    $query->where('is_active', true);
                }
                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
                if ($status === 'low_stock') {
                    $query->where('stock', '<=', 10);
                }
            })
            ->latest()
            ->get();

        return view('pages.admin.products', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'currentQuery' => $request->string('q')->toString(),
            'currentCategory' => $request->string('category')->toString(),
            'currentStatus' => $request->string('status')->toString(),
        ]);
    }

    public function users(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('name', 'like', '%'.$keyword.'%')
                        ->orWhere('whatsapp', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%');
                });
            })
            ->when($request->filled('role'), function ($query) use ($request): void {
                $query->where('role', $request->string('role')->toString());
            })
            ->orderByDesc('id')
            ->get();

        return view('pages.admin.users', [
            'users' => $users,
            'currentQuery' => $request->string('q')->toString(),
            'currentRole' => $request->string('role')->toString(),
            'adminCount' => User::query()->where('role', 'admin')->count(),
            'ownerCount' => User::query()->where('role', 'owner')->count(),
            'customerCount' => User::query()->where('role', 'customer')->count(),
        ]);
    }

    public function settings(): View
    {
        return view('pages.admin.settings', [
            'settings' => [
                'store_name' => AppSetting::getValue('store_name', 'SR12 Sintia'),
                'store_whatsapp' => AppSetting::getValue('store_whatsapp', '081111111111'),
                'pickup_address' => AppSetting::getValue('pickup_address', 'Parungpanjang, Bogor'),
                'pickup_reminder_template' => AppSetting::getValue('pickup_reminder_template', 'Pesanan Anda sudah siap diambil di toko SR12.'),
                            'bank_account_number' => AppSetting::getValue('bank_account_number', ''),
                            'ewallet_number' => AppSetting::getValue('ewallet_number', ''),
            ],
        ]);
    }

    public function analytics(Request $request): View
    {
        $from = $request->string('from')->toString() !== ''
            ? Carbon::parse($request->string('from')->toString())->startOfDay()
            : now()->startOfMonth();

        $to = $request->string('to')->toString() !== ''
            ? Carbon::parse($request->string('to')->toString())->endOfDay()
            : now()->endOfDay();

        $orders = Order::query()
            ->with('user')
            ->whereBetween('created_at', [$from, $to])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('order_code', 'like', '%'.$keyword.'%')
                        ->orWhere('recipient_name', 'like', '%'.$keyword.'%')
                        ->orWhere('recipient_whatsapp', 'like', '%'.$keyword.'%');
                });
            })
            ->when($request->filled('payment'), function ($query) use ($request): void {
                $query->where('payment_method', $request->string('payment')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status')->toString());
            });

        $transactions = (clone $orders)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $revenueBase = (clone $orders);
        if (! $request->filled('status')) {
            $revenueBase->whereIn('status', ['processing', 'shipped', 'ready_for_pickup', 'completed']);
        }

        $revenue = (int) $revenueBase->sum('total');
        $orderCount = (int) (clone $orders)->count();
        $aov = (int) ((clone $orders)->avg('total') ?? 0);

        $trendRows = (clone $orders)
            ->selectRaw('DATE(created_at) as day, SUM(total) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $trendMap = $trendRows->pluck('total', 'day');
        $trendLabels = [];
        $trendValues = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $date = $cursor->toDateString();
            $trendLabels[] = $cursor->translatedFormat('d M');
            $trendValues[] = (int) ($trendMap[$date] ?? 0);
            $cursor->addDay();
        }

        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->when($request->filled('payment'), function ($query) use ($request): void {
                $query->where('orders.payment_method', $request->string('payment')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('orders.status', $request->string('status')->toString());
            })
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('order_items.product_name', 'like', '%'.$keyword.'%')
                        ->orWhere('order_items.product_id', 'like', '%'.$keyword.'%');
                });
            })
            ->selectRaw('order_items.product_id, order_items.product_name as product_name, SUM(order_items.quantity) as sold, SUM(order_items.line_total) as revenue')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('sold')
            ->limit(8)
            ->get();

        return view('pages.admin.analytics', [
            'revenue' => $revenue,
            'orders' => $orderCount,
            'aov' => $aov,
            'transactions' => $transactions,
            'topProducts' => $topProducts,
            'lowStockProducts' => Product::query()->where('stock', '<=', 10)->orderBy('stock')->limit(8)->get(),
            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,
            'filters' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'q' => $request->string('q')->toString(),
                'payment' => $request->string('payment')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }

    public function exportAnalytics(Request $request): StreamedResponse
    {
        $from = $request->string('from')->toString() !== ''
            ? Carbon::parse($request->string('from')->toString())->startOfDay()
            : now()->startOfMonth();

        $to = $request->string('to')->toString() !== ''
            ? Carbon::parse($request->string('to')->toString())->endOfDay()
            : now()->endOfDay();

        $format = $request->string('format', 'csv')->toString();

        $rows = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('order_code', 'like', '%'.$keyword.'%')
                        ->orWhere('recipient_name', 'like', '%'.$keyword.'%')
                        ->orWhere('recipient_whatsapp', 'like', '%'.$keyword.'%');
                });
            })
            ->when($request->filled('payment'), function ($query) use ($request): void {
                $query->where('payment_method', $request->string('payment')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status')->toString());
            })
            ->orderByDesc('created_at')
            ->get([
                'order_code',
                'recipient_name',
                'recipient_whatsapp',
                'payment_method',
                'shipping_method',
                'status',
                'subtotal',
                'shipping_cost',
                'total',
                'created_at',
            ]);

        $fileDate = now()->format('Ymd_His');

        if ($format === 'excel') {
            return response()->streamDownload(function () use ($rows): void {
                $out = fopen('php://output', 'w');
                fwrite($out, "Kode Pesanan\tPelanggan\tWhatsApp\tPembayaran\tPengiriman\tStatus\tSubtotal\tOngkir\tTotal\tTanggal\n");
                foreach ($rows as $row) {
                    $line = [
                        $row->order_code,
                        $row->recipient_name,
                        $row->recipient_whatsapp,
                        $row->payment_method,
                        $row->shipping_method,
                        $row->status,
                        (string) $row->subtotal,
                        (string) $row->shipping_cost,
                        (string) $row->total,
                        $row->created_at?->format('Y-m-d H:i:s') ?? '',
                    ];
                    fwrite($out, implode("\t", $line)."\n");
                }
                fclose($out);
            }, "laporan_analisis_{$fileDate}.xls", [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            ]);
        }

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Kode Pesanan', 'Pelanggan', 'WhatsApp', 'Pembayaran', 'Pengiriman', 'Status', 'Subtotal', 'Ongkir', 'Total', 'Tanggal']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->order_code,
                    $row->recipient_name,
                    $row->recipient_whatsapp,
                    $row->payment_method,
                    $row->shipping_method,
                    $row->status,
                    (string) $row->subtotal,
                    (string) $row->shipping_cost,
                    (string) $row->total,
                    $row->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }
            fclose($out);
        }, "laporan_analisis_{$fileDate}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function verifyPayment(Order $order): RedirectResponse
    {
        if (! in_array($order->payment_method, ['transfer', 'ewallet'], true)) {
            return back()->with('error', 'Verifikasi pembayaran hanya untuk transfer/e-wallet.');
        }

        if ($order->status !== 'payment_submitted') {
            return back()->with('error', 'Pesanan belum mengirim bukti pembayaran.');
        }

        $nextStatus = $order->shipping_method === 'pickup' ? 'ready_for_pickup' : 'processing';

        $order->update([
            'status' => $nextStatus,
            'paid_at' => now(),
            'processing_at' => $nextStatus === 'processing' ? now() : null,
            'ready_for_pickup_at' => $nextStatus === 'ready_for_pickup' ? now() : null,
        ]);

        return back()->with('success', 'Pembayaran pesanan '.$order->order_code.' berhasil diverifikasi.');
    }

    public function sendPickupReminder(Order $order): RedirectResponse
    {
        if ($order->shipping_method !== 'pickup') {
            return back()->with('error', 'Reminder pickup hanya untuk pesanan ambil di toko.');
        }

        if ($order->status !== 'ready_for_pickup') {
            return back()->with('error', 'Pesanan belum siap diambil.');
        }

        $order->update([
            'pickup_ready_reminded_at' => now(),
        ]);

        return back()->with('success', 'Reminder pickup untuk '.$order->order_code.' berhasil ditandai terkirim.');
    }

    public function advanceOrderStatus(Order $order): RedirectResponse
    {
        $nextStatus = match ($order->status) {
            'awaiting_shipment_cod' => 'shipped',
            'processing' => 'shipped',
            'shipped' => 'completed',
            'ready_for_pickup' => 'completed',
            default => null,
        };

        if (! $nextStatus) {
            return back()->with('error', 'Status pesanan tidak bisa dilanjutkan dari tahap saat ini.');
        }

        $payload = ['status' => $nextStatus];

        if ($nextStatus === 'shipped') {
            $payload['shipped_at'] = now();
            if (! $order->processing_at) {
                $payload['processing_at'] = now();
            }
        }

        if ($nextStatus === 'completed') {
            $payload['completed_at'] = now();
            if (in_array($order->payment_method, ['cod', 'pay_at_store'], true) && ! $order->paid_at) {
                $payload['paid_at'] = now();
            }
        }

        $order->update($payload);

        return back()->with('success', 'Status pesanan '.$order->order_code.' berhasil diperbarui ke '.$nextStatus.'.');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'role' => ['required', 'in:admin,owner,customer'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $digits = preg_replace('/\D+/', '', $data['whatsapp']) ?? '';
        if (str_starts_with($digits, '62')) {
            $digits = '0'.substr($digits, 2);
        }

        if ($digits === '' || User::query()->where('whatsapp', $digits)->exists()) {
            return back()->withErrors([
                'whatsapp' => 'Nomor WhatsApp tidak valid atau sudah terdaftar.',
            ])->withInput();
        }

        User::query()->create([
            'name' => $data['name'],
            'whatsapp' => $digits,
            'address' => $data['address'],
            'email' => $data['email'] ?? $digits.'@sr12.local',
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => 'active',
        ]);

        return back()->with('success', 'User baru berhasil dibuat.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'role' => ['required', 'in:admin,owner,customer'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $whatsappInput = $data['whatsapp'] ?? $user->whatsapp;
        $digits = preg_replace('/\D+/', '', (string) $whatsappInput) ?? '';
        if (str_starts_with($digits, '62')) {
            $digits = '0'.substr($digits, 2);
        }

        if ($digits === '') {
            return back()->withErrors([
                'whatsapp' => 'Nomor WhatsApp tidak valid.',
            ])->withInput();
        }

        $exists = User::query()
            ->where('whatsapp', $digits)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'whatsapp' => 'Nomor WhatsApp sudah digunakan oleh user lain.',
            ])->withInput();
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'] ?? $user->email,
            'whatsapp' => $digits,
            'address' => $data['address'],
            'role' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function deleteUser(User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return back()->with('error', 'Akun admin yang sedang login tidak bisa dihapus.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::query()->create([
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
            'image_url' => $imagePath ? \Illuminate\Support\Facades\Storage::url($imagePath) : null,
            'description' => $data['description'] ?? '',
            'price' => (float) $data['price'],
            'compare_price' => (float) $data['price'],
            'rating' => 4.5,
            'stock' => (int) $data['stock'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            // Delete old image if stored locally
            if ($imageUrl && str_starts_with($imageUrl, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $imageUrl);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $imageUrl = \Illuminate\Support\Facades\Storage::url($imagePath);
        }

        $product->update([
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'price' => (float) $data['price'],
            'stock' => (int) $data['stock'],
            'description' => $data['description'] ?? '',
            'image_url' => $imageUrl,
        ]);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleProductStatus(Product $product): RedirectResponse
    {
        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        return back()->with('success', 'Status produk berhasil diperbarui.');
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_whatsapp' => ['required', 'string', 'max:20'],
            'pickup_address' => ['required', 'string', 'max:1000'],
            'pickup_reminder_template' => ['required', 'string', 'max:2000'],
                    'bank_account_number' => ['nullable', 'string', 'max:255'],
                    'ewallet_number' => ['nullable', 'string', 'max:20'],
        ]);

        foreach ($data as $key => $value) {
            AppSetting::setValue($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function categories(Request $request): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = $request->string('q')->toString();
                $query->where(function ($inner) use ($keyword): void {
                    $inner->where('name', 'like', '%'.$keyword.'%')
                        ->orWhere('slug', 'like', '%'.$keyword.'%');
                });
            })
            ->orderBy('name')
            ->get();

        return view('pages.admin.categories', [
            'categories' => $categories,
            'currentQuery' => $request->string('q')->toString(),
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::query()->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
        ]);

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

}
