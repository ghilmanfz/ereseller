<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private Order $order;
    private Product $product;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin
        $this->admin = User::query()->create([
            'name' => 'Admin User',
            'whatsapp' => '081111111111',
            'address' => 'Admin Address',
            'email' => 'admin@sr12.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create customer
        $this->customer = User::query()->create([
            'name' => 'Customer User',
            'whatsapp' => '082222222222',
            'address' => 'Customer Address',
            'email' => 'customer@sr12.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        // Create category
        $this->category = Category::query()->create([
            'name' => 'Skincare',
            'slug' => 'skincare',
        ]);

        // Create product
        $this->product = Product::query()->create([
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'slug' => 'test-product-abcd',
            'image_url' => 'https://example.com/image.jpg',
            'description' => 'Test Description',
            'price' => 100000,
            'compare_price' => 120000,
            'rating' => 4.5,
            'stock' => 50,
            'is_active' => true,
        ]);

        // Create sample order with payment_submitted status
        $this->order = Order::query()->create([
            'order_code' => 'SR12-260421-TEST',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'payment_submitted',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        // Create order items
        OrderItem::query()->create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'product_name' => 'Test Product',
            'price' => 100000,
            'quantity' => 1,
            'line_total' => 100000,
        ]);
    }

    // ==================== PAYMENT VERIFICATION TESTS ====================

    public function test_admin_can_verify_transfer_payment(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$this->order->id.'/verifikasi-pembayaran');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'processing',
        ]);
        
        // Verify paid_at is set (not null)
        $updatedOrder = Order::find($this->order->id);
        $this->assertNotNull($updatedOrder->paid_at);
    }

    public function test_admin_cannot_verify_cod_payment(): void
    {
        $codOrder = Order::query()->create([
            'order_code' => 'SR12-260421-COD',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'cod',
            'status' => 'awaiting_shipment_cod',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$codOrder->id.'/verifikasi-pembayaran');

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_admin_cannot_verify_non_submitted_payment(): void
    {
        $pendingOrder = Order::query()->create([
            'order_code' => 'SR12-260421-PENDING',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'pending_payment',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$pendingOrder->id.'/verifikasi-pembayaran');

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // ==================== ORDER STATUS ADVANCEMENT TESTS ====================

    public function test_admin_can_advance_processing_to_shipped(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-PROC',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'processing',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/status-lanjut');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
        ]);
    }

    public function test_admin_can_advance_shipped_to_completed(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-SHIP',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'shipped',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
            'shipped_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/status-lanjut');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
        
        // Verify completed_at is set
        $updatedOrder = Order::find($order->id);
        $this->assertNotNull($updatedOrder->completed_at);
    }

    public function test_admin_can_advance_cod_shipment(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-AWCOD',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'cod',
            'status' => 'awaiting_shipment_cod',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/status-lanjut');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
        ]);
    }

    public function test_admin_can_advance_ready_for_pickup_to_completed(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-RFDPU',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Ambil di toko SR12 Parungpanjang',
            'shipping_method' => 'pickup',
            'payment_method' => 'pay_at_store',
            'status' => 'ready_for_pickup',
            'subtotal' => 100000,
            'shipping_cost' => 0,
            'total' => 100000,
            'ready_for_pickup_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/status-lanjut');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    // ==================== PICKUP REMINDER TESTS ====================

    public function test_admin_can_send_pickup_reminder(): void
    {
        $pickupOrder = Order::query()->create([
            'order_code' => 'SR12-260421-PKREM',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Ambil di toko SR12 Parungpanjang',
            'shipping_method' => 'pickup',
            'payment_method' => 'pay_at_store',
            'status' => 'ready_for_pickup',
            'subtotal' => 100000,
            'shipping_cost' => 0,
            'total' => 100000,
            'ready_for_pickup_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$pickupOrder->id.'/reminder-pickup');

        $response->assertRedirect();
        $updatedOrder = Order::find($pickupOrder->id);
        $this->assertNotNull($updatedOrder->pickup_ready_reminded_at);
    }

    public function test_admin_cannot_remind_delivery_order(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$this->order->id.'/reminder-pickup');

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // ==================== SETTINGS TESTS ====================

    public function test_admin_can_save_settings(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia Updated',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('SR12 Sintia Updated', AppSetting::getValue('store_name'));
        $this->assertEquals('081234567890', AppSetting::getValue('store_whatsapp'));
        $this->assertEquals('Jl. New Address No. 123', AppSetting::getValue('pickup_address'));
    }

    // ==================== USER MANAGEMENT TESTS ====================

    public function test_admin_can_create_new_user(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'New User',
            'whatsapp' => '083333333333',
            'address' => 'New User Address',
            'role' => 'customer',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'whatsapp' => '083333333333',
            'role' => 'customer',
        ]);
    }

    public function test_admin_can_update_user_role(): void
    {
        $customer = User::query()->create([
            'name' => 'Test Customer',
            'whatsapp' => '084444444444',
            'address' => 'Test Address',
            'email' => 'test@sr12.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($this->admin)->patch('/admin/users/'.$customer->id, [
            'name' => 'Test Customer',
            'address' => 'Test Address',
            'role' => 'admin',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete('/admin/users/'.$this->admin->id);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $customer = User::query()->create([
            'name' => 'Deletable User',
            'whatsapp' => '085555555555',
            'address' => 'Test Address',
            'email' => 'deletable@sr12.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/users/'.$customer->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $customer->id]);
    }

    // ==================== PRODUCT MANAGEMENT TESTS ====================

    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/produk', [
            'category_id' => $this->category->id,
            'name' => 'New Product',
            'price' => 150000,
            'stock' => 100,
            'description' => 'New Product Description',
            'image_url' => 'https://example.com/new-image.jpg',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'price' => 150000,
            'stock' => 100,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $response = $this->actingAs($this->admin)->patch('/admin/produk/'.$this->product->id, [
            'category_id' => $this->category->id,
            'name' => 'Updated Product',
            'price' => 125000,
            'stock' => 75,
            'description' => 'Updated Description',
            'image_url' => 'https://example.com/updated-image.jpg',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'name' => 'Updated Product',
            'price' => 125000,
            'stock' => 75,
        ]);
    }

    public function test_admin_can_toggle_product_status(): void
    {
        $this->assertTrue($this->product->is_active);

        $this->actingAs($this->admin)->post('/admin/produk/'.$this->product->id.'/toggle-status');

        $this->product->refresh();
        $this->assertFalse($this->product->is_active);

        $this->actingAs($this->admin)->post('/admin/produk/'.$this->product->id.'/toggle-status');

        $this->product->refresh();
        $this->assertTrue($this->product->is_active);
    }

    // ==================== ACCESS CONTROL TESTS ====================

    public function test_customer_cannot_access_admin_pages(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_guest_redirected_from_admin_pages(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.dashboard');
    }

    public function test_admin_can_view_orders_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pesanan');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.orders');
    }

    public function test_admin_can_view_products_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/produk');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.products');
    }

    public function test_admin_can_view_users_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.users');
    }

    public function test_admin_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pengaturan');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.settings');
    }

    // ==================== STATUS CHANGE TESTS (Non-Monoton) ====================

    public function test_admin_can_change_order_status_with_valid_transition(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-STCH',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'pending_payment',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/ubah-status', [
            'new_status' => 'cancelled',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
        
        $updatedOrder = Order::find($order->id);
        $this->assertNotNull($updatedOrder->cancelled_at);
    }

    public function test_admin_can_change_processing_to_cancelled(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-CANC',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'processing',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/ubah-status', [
            'new_status' => 'shipped',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
        ]);
    }

    public function test_admin_cannot_change_order_status_with_invalid_transition(): void
    {
        $order = Order::query()->create([
            'order_code' => 'SR12-260421-INVL',
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer Name',
            'recipient_whatsapp' => '082222222222',
            'recipient_address' => 'Customer Address',
            'shipping_method' => 'delivery',
            'payment_method' => 'transfer',
            'status' => 'completed',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ]);

        // Try to change from completed to shipped (invalid)
        $response = $this->actingAs($this->admin)->post('/admin/pesanan/'.$order->id.'/ubah-status', [
            'new_status' => 'shipped',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed', // Status should remain unchanged
        ]);
    }

    // ==================== NOTIFICATION TESTS ====================

    public function test_admin_can_fetch_notifications(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/notifications');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'notifications' => [
                '*' => ['type', 'title', 'message', 'timestamp', 'order_id']
            ],
            'unread_count'
        ]);
    }

    // ==================== SETTINGS TESTS (Bank Account & E-Wallet) ====================

    public function test_admin_can_save_bank_account_and_ewallet_settings(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('1234567890', AppSetting::getValue('bank_account_number'));
        $this->assertEquals('081234567890', AppSetting::getValue('ewallet_number'));
    }

    // ==================== CUSTOMER PAGE PROTECTION TESTS ====================

    public function test_admin_cannot_access_customer_cart(): void
    {
        $response = $this->actingAs($this->admin)->get('/keranjang');

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_admin_cannot_access_customer_checkout(): void
    {
        $response = $this->actingAs($this->admin)->post('/checkout', [
            'recipient_name' => 'Test',
            'recipient_whatsapp' => '081234567890',
            'recipient_address' => 'Test',
            'shipping_method' => 'delivery',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_customer_can_access_cart(): void
    {
        $response = $this->actingAs($this->customer)->get('/keranjang');

        $response->assertStatus(200);
    }
}

