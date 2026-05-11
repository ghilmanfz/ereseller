<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('pages.admin.dashboard', [
            'activeUsers' => User::query()->count(),
            'orderCount' => Order::query()->count(),
            'revenue' => (int) Order::query()->whereIn('status', ['processing', 'shipped', 'ready_for_pickup', 'completed'])->sum('total'),
            'lowStockProducts' => Product::query()->where('stock', '<=', 10)->orderBy('stock')->limit(5)->get(),
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
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pages.admin.orders', [
            'orders' => $orders,
            'currentQuery' => $request->string('q')->toString(),
            'currentPayment' => $request->string('payment')->toString(),
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

    public function products(): View
    {
        return view('pages.admin.products', [
            'products' => Product::query()->with('category')->latest()->get(),
            'categories' => Category::query()->orderBy('name')->get(),
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
            ->orderByDesc('id')
            ->get();

        return view('pages.admin.users', [
            'users' => $users,
            'currentQuery' => $request->string('q')->toString(),
            'adminCount' => User::query()->where('role', 'admin')->count(),
            'ownerCount' => User::query()->where('role', 'owner')->count(),
            'customerCount' => User::query()->where('role', 'customer')->count(),
        ]);
    }

    public function settings(): View
    {
        return view('pages.admin.settings', [
            'settings' => $this->settingsPayload(),
            'activeProducts' => Product::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->select(['id', 'name', 'price', 'stock', 'image_url'])
                ->get(),
        ]);
    }

    private function settingsPayload(): array
    {
        return [
            'store_name' => AppSetting::getValue('store_name', 'SR12 Sintia'),
            'store_whatsapp' => AppSetting::getValue('store_whatsapp', '081111111111'),
            'pickup_address' => AppSetting::getValue('pickup_address', 'Parungpanjang, Bogor'),
            'pickup_reminder_template' => AppSetting::getValue('pickup_reminder_template', 'Pesanan Anda sudah siap diambil di toko SR12.'),
            'bank_account_number' => AppSetting::getValue('bank_account_number', ''),
            'ewallet_number' => AppSetting::getValue('ewallet_number', ''),
            'store_logo' => AppSetting::getValue('store_logo', ''),
            'landing_hero_badge' => AppSetting::getValue('landing_hero_badge', 'Distributor Resmi SR12'),
            'landing_hero_title' => AppSetting::getValue('landing_hero_title', 'Kulit Sehat Setiap Hari'),
            'landing_hero_highlight' => AppSetting::getValue('landing_hero_highlight', 'bersama SR12 Sintia'),
            'landing_hero_description' => AppSetting::getValue('landing_hero_description', 'Rangkaian skincare herbal pilihan untuk pelanggan Parungpanjang.'),
            'landing_primary_button_text' => AppSetting::getValue('landing_primary_button_text', 'Belanja Produk'),
            'landing_secondary_button_text' => AppSetting::getValue('landing_secondary_button_text', 'Lihat Katalog'),
            'landing_hero_image' => AppSetting::getValue('landing_hero_image', ''),
            'landing_cta_title' => AppSetting::getValue('landing_cta_title', 'Siap Merawat Kulitmu?'),
            'landing_cta_description' => AppSetting::getValue('landing_cta_description', 'Dapatkan promo dan rekomendasi produk langsung dari admin.'),
            'landing_cta_primary_button_text' => AppSetting::getValue('landing_cta_primary_button_text', 'Daftar Sekarang'),
            'landing_cta_secondary_button_text' => AppSetting::getValue('landing_cta_secondary_button_text', 'Pelajari Produk'),
            'featured_products_mode' => AppSetting::getValue('featured_products_mode', 'default'),
            'featured_product_ids' => AppSetting::getValue('featured_product_ids', ''),
        ];
    }

    public function analytics(): View
    {
        return view('pages.admin.analytics', [
            'revenue' => (int) Order::query()->whereIn('status', ['processing', 'shipped', 'ready_for_pickup', 'completed'])->sum('total'),
            'orders' => Order::query()->count(),
            'aov' => (int) Order::query()->avg('total'),
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
            'whatsapp' => ['required', 'string', 'max:20', 'unique:users,whatsapp'],
            'address' => ['required', 'string', 'max:1000'],
            'role' => ['required', 'in:admin,owner,customer'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $digits = preg_replace('/\D+/', '', $data['whatsapp']) ?? '';
        if (str_starts_with($digits, '62')) {
            $digits = '0'.substr($digits, 2);
        }

        User::query()->create([
            'name' => $data['name'],
            'whatsapp' => $digits,
            'address' => $data['address'],
            'email' => $digits.'@sr12.local',
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
            'address' => ['required', 'string', 'max:1000'],
            'role' => ['required', 'in:admin,owner,customer'],
        ]);

        $user->update($data);

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
            'landing_hero_badge' => ['nullable', 'string', 'max:255'],
            'landing_hero_title' => ['nullable', 'string', 'max:255'],
            'landing_hero_highlight' => ['nullable', 'string', 'max:255'],
            'landing_hero_description' => ['nullable', 'string', 'max:1000'],
            'landing_primary_button_text' => ['nullable', 'string', 'max:255'],
            'landing_secondary_button_text' => ['nullable', 'string', 'max:255'],
            'landing_cta_title' => ['nullable', 'string', 'max:255'],
            'landing_cta_description' => ['nullable', 'string', 'max:1000'],
            'landing_cta_primary_button_text' => ['nullable', 'string', 'max:255'],
            'landing_cta_secondary_button_text' => ['nullable', 'string', 'max:255'],
            'featured_products_mode' => ['nullable', 'in:default,manual'],
            'featured_product_ids' => ['nullable', 'array'],
            'featured_product_ids.*' => ['integer', 'exists:products,id'],
            'store_logo' => ['nullable', 'image', 'max:2048'],
            'landing_hero_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $currentSettings = $this->settingsPayload();
        $scalarKeys = [
            'store_name',
            'store_whatsapp',
            'pickup_address',
            'pickup_reminder_template',
            'bank_account_number',
            'ewallet_number',
            'landing_hero_badge',
            'landing_hero_title',
            'landing_hero_highlight',
            'landing_hero_description',
            'landing_primary_button_text',
            'landing_secondary_button_text',
            'landing_cta_title',
            'landing_cta_description',
            'landing_cta_primary_button_text',
            'landing_cta_secondary_button_text',
        ];

        foreach ($scalarKeys as $key) {
            $value = array_key_exists($key, $data) ? ($data[$key] ?? '') : ($currentSettings[$key] ?? '');

            AppSetting::setValue($key, (string) $value);
        }

        AppSetting::setValue('featured_products_mode', $data['featured_products_mode'] ?? $currentSettings['featured_products_mode']);
        AppSetting::setValue(
            'featured_product_ids',
            $request->has('featured_product_ids') ? $this->validatedFeaturedProductIds($request) : $currentSettings['featured_product_ids']
        );

        foreach (['store_logo', 'landing_hero_image'] as $field) {
            if ($request->hasFile($field)) {
                AppSetting::setValue($field, $this->storeSettingImage($request, $field));
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function validatedFeaturedProductIds(Request $request): string
    {
        $selectedIds = collect($request->input('featured_product_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($selectedIds->isEmpty()) {
            return '';
        }

        $activeIds = Product::query()
            ->where('is_active', true)
            ->whereIn('id', $selectedIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id);

        return $selectedIds
            ->filter(fn (int $id) => $activeIds->contains($id))
            ->take(4)
            ->implode(',');
    }

    private function storeSettingImage(Request $request, string $field): string
    {
        $oldUrl = AppSetting::getValue($field, '');
        $path = $request->file($field)->store('settings', 'public');

        if ($oldUrl && str_starts_with($oldUrl, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $oldUrl));
        }

        return Storage::url($path);
    }

    public function categories(): View
    {
        return view('pages.admin.categories', [
            'categories' => Category::query()->withCount('products')->orderBy('name')->get(),
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
