<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_save_branding_landing_images_and_featured_product_settings(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia Updated',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
            'landing_hero_badge' => 'Distributor Resmi SR12',
            'landing_hero_title' => 'Kulit Sehat Setiap Hari',
            'landing_hero_highlight' => 'bersama SR12 Sintia',
            'landing_hero_description' => 'Rangkaian skincare herbal pilihan untuk pelanggan Parungpanjang.',
            'landing_primary_button_text' => 'Belanja Produk',
            'landing_secondary_button_text' => 'Lihat Katalog',
            'landing_cta_title' => 'Siap Merawat Kulitmu?',
            'landing_cta_description' => 'Dapatkan promo dan rekomendasi produk langsung dari admin.',
            'landing_cta_primary_button_text' => 'Daftar Sekarang',
            'landing_cta_secondary_button_text' => 'Pelajari Produk',
            'featured_products_mode' => 'manual',
            'featured_product_ids' => [$this->product->id],
            'store_logo' => UploadedFile::fake()->image('logo.png', 200, 200),
            'landing_hero_image' => UploadedFile::fake()->image('hero.jpg', 900, 1200),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame('SR12 Sintia Updated', AppSetting::getValue('store_name'));
        $this->assertSame('Kulit Sehat Setiap Hari', AppSetting::getValue('landing_hero_title'));
        $this->assertSame('manual', AppSetting::getValue('featured_products_mode'));
        $this->assertSame((string) $this->product->id, AppSetting::getValue('featured_product_ids'));

        $logoPath = str_replace('/storage/', '', AppSetting::getValue('store_logo'));
        $heroPath = str_replace('/storage/', '', AppSetting::getValue('landing_hero_image'));

        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($heroPath);
    }

    public function test_admin_can_save_default_featured_product_mode(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
            'featured_products_mode' => 'default',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertSame('default', AppSetting::getValue('featured_products_mode'));
    }

    public function test_landing_page_renders_custom_landing_settings_and_manual_featured_products(): void
    {
        $manualProduct = Product::query()->create([
            'category_id' => $this->category->id,
            'name' => 'Manual Glow Serum',
            'slug' => 'manual-glow-serum',
            'image_url' => 'https://example.com/manual.jpg',
            'description' => 'Manual featured product',
            'price' => 175000,
            'compare_price' => 175000,
            'rating' => 3.5,
            'stock' => 20,
            'is_active' => true,
        ]);

        AppSetting::setValue('landing_hero_badge', 'Badge Custom');
        AppSetting::setValue('landing_hero_title', 'Judul Landing Custom');
        AppSetting::setValue('landing_hero_highlight', 'Highlight Custom');
        AppSetting::setValue('landing_hero_description', 'Deskripsi landing custom untuk pelanggan.');
        AppSetting::setValue('landing_primary_button_text', 'Belanja Custom');
        AppSetting::setValue('landing_secondary_button_text', 'Katalog Custom');
        AppSetting::setValue('landing_hero_image', '/storage/settings/custom-hero.jpg');
        AppSetting::setValue('landing_cta_title', 'CTA Custom');
        AppSetting::setValue('landing_cta_description', 'Deskripsi CTA custom.');
        AppSetting::setValue('landing_cta_primary_button_text', 'Daftar Custom');
        AppSetting::setValue('landing_cta_secondary_button_text', 'Produk Custom');
        AppSetting::setValue('featured_products_mode', 'manual');
        AppSetting::setValue('featured_product_ids', (string) $manualProduct->id);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Badge Custom');
        $response->assertSee('Judul Landing Custom');
        $response->assertSee('Highlight Custom');
        $response->assertSee('Belanja Custom');
        $response->assertSee('/storage/settings/custom-hero.jpg', false);
        $response->assertSee('CTA Custom');
        $response->assertSee('Manual Glow Serum');
    }

    public function test_landing_page_falls_back_to_default_featured_products_when_manual_selection_is_empty(): void
    {
        $topProduct = Product::query()->create([
            'category_id' => $this->category->id,
            'name' => 'Auto Top Product',
            'slug' => 'auto-top-product',
            'image_url' => 'https://example.com/top.jpg',
            'description' => 'Automatic featured product',
            'price' => 225000,
            'compare_price' => 225000,
            'rating' => 5,
            'stock' => 15,
            'is_active' => true,
        ]);

        AppSetting::setValue('featured_products_mode', 'manual');
        AppSetting::setValue('featured_product_ids', '');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($topProduct->name);
    }

    public function test_landing_page_preserves_default_cta_responsive_line_break(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bergabunglah Dengan Ribuan Reseller &amp;<br class="hidden sm:block"> Konsumen Loyal SR12 Parungpanjang', false);
    }

    public function test_admin_cannot_upload_hero_image_larger_than_two_megabytes(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
            'featured_products_mode' => 'default',
            'landing_hero_image' => UploadedFile::fake()->image('hero.jpg', 900, 1200)->size(2049),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('landing_hero_image');
    }

    public function test_admin_cannot_upload_store_logo_larger_than_two_megabytes(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
            'featured_products_mode' => 'default',
            'store_logo' => UploadedFile::fake()->image('logo.png', 200, 200)->size(2049),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('store_logo');
    }

    public function test_admin_replacing_setting_images_deletes_old_public_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/old-logo.png', 'old logo');
        Storage::disk('public')->put('settings/old-hero.jpg', 'old hero');
        AppSetting::setValue('store_logo', '/storage/settings/old-logo.png');
        AppSetting::setValue('landing_hero_image', '/storage/settings/old-hero.jpg');

        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
            'featured_products_mode' => 'default',
            'store_logo' => UploadedFile::fake()->image('new-logo.png', 200, 200),
            'landing_hero_image' => UploadedFile::fake()->image('new-hero.jpg', 900, 1200),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        Storage::disk('public')->assertMissing('settings/old-logo.png');
        Storage::disk('public')->assertMissing('settings/old-hero.jpg');

        $logoPath = str_replace('/storage/', '', AppSetting::getValue('store_logo'));
        $heroPath = str_replace('/storage/', '', AppSetting::getValue('landing_hero_image'));

        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($heroPath);
    }

    public function test_manual_featured_product_ids_are_filtered_to_active_products_and_capped_at_four(): void
    {
        $activeProducts = collect();

        foreach (range(1, 5) as $number) {
            $activeProducts->push(Product::query()->create([
                'category_id' => $this->category->id,
                'name' => 'Featured Product '.$number,
                'slug' => 'featured-product-'.$number,
                'image_url' => 'https://example.com/featured-'.$number.'.jpg',
                'description' => 'Featured product '.$number,
                'price' => 100000 + $number,
                'compare_price' => 120000 + $number,
                'rating' => 4.5,
                'stock' => 10,
                'is_active' => true,
            ]));
        }

        $inactiveProduct = Product::query()->create([
            'category_id' => $this->category->id,
            'name' => 'Inactive Featured Product',
            'slug' => 'inactive-featured-product',
            'image_url' => 'https://example.com/inactive-featured.jpg',
            'description' => 'Inactive featured product',
            'price' => 150000,
            'compare_price' => 175000,
            'rating' => 4.5,
            'stock' => 10,
            'is_active' => false,
        ]);

        $selectedIds = [
            $activeProducts[0]->id,
            $inactiveProduct->id,
            $activeProducts[1]->id,
            $activeProducts[2]->id,
            $activeProducts[3]->id,
            $activeProducts[4]->id,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/pengaturan', [
            'store_name' => 'SR12 Sintia',
            'store_whatsapp' => '081234567890',
            'pickup_address' => 'Jl. New Address No. 123',
            'pickup_reminder_template' => 'Pesanan Anda sudah siap!',
            'bank_account_number' => '1234567890',
            'ewallet_number' => '081234567890',
            'featured_products_mode' => 'manual',
            'featured_product_ids' => $selectedIds,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $expectedIds = $activeProducts
            ->take(4)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->implode(',');

        $this->assertSame($expectedIds, AppSetting::getValue('featured_product_ids'));
    }

    public function test_app_setting_returns_default_when_key_is_missing(): void
    {
        $this->assertSame('SR12 Sintia', AppSetting::getValue('store_name', 'SR12 Sintia'));
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

    public function test_settings_page_renders_branding_landing_and_featured_product_controls(): void
    {
        AppSetting::setValue('store_logo', '/storage/settings/current-logo.png');
        AppSetting::setValue('landing_hero_image', '/storage/settings/current-hero.jpg');
        AppSetting::setValue('featured_products_mode', 'manual');
        AppSetting::setValue('featured_product_ids', (string) $this->product->id);

        $response = $this->actingAs($this->admin)->get('/admin/pengaturan');

        $response->assertStatus(200);
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('Logo Toko');
        $response->assertSee('Konten Landing Page');
        $response->assertSee('Produk Terlaris Landing Page');
        $response->assertSee('name="landing_hero_badge"', false);
        $response->assertSee('name="landing_cta_secondary_button_text"', false);
        $response->assertSee('name="featured_products_mode"', false);
        $response->assertSee('value="manual"', false);
        $response->assertSee('name="featured_product_ids[]"', false);
        $response->assertSee('value="'.$this->product->id.'"', false);
        $response->assertSee('Test Product');
        $response->assertSee('Stok: 50');
        $response->assertSee('Rp 100.000');
        $response->assertSee('/storage/settings/current-logo.png', false);
        $response->assertSee('/storage/settings/current-hero.jpg', false);
    }

    public function test_settings_page_uses_old_featured_product_ids_when_old_input_is_csv(): void
    {
        $oldInputProduct = Product::query()->create([
            'category_id' => $this->category->id,
            'name' => 'Old Input Serum',
            'slug' => 'old-input-serum',
            'image_url' => 'https://example.com/old-input.jpg',
            'description' => 'Selected from old CSV input',
            'price' => 150000,
            'compare_price' => 150000,
            'rating' => 4.5,
            'stock' => 8,
            'is_active' => true,
        ]);

        AppSetting::setValue('featured_product_ids', (string) $this->product->id);

        $response = $this
            ->actingAs($this->admin)
            ->withSession([
                '_old_input' => [
                    'featured_product_ids' => (string) $oldInputProduct->id,
                ],
            ])
            ->get('/admin/pengaturan');

        $response->assertStatus(200);
        $content = $response->getContent();
        preg_match('/<input\s+[^>]*name="featured_product_ids\[\]"[^>]*value="'.$oldInputProduct->id.'"[^>]*>/m', $content, $oldInputMatch);
        preg_match('/<input\s+[^>]*name="featured_product_ids\[\]"[^>]*value="'.$this->product->id.'"[^>]*>/m', $content, $settingsInputMatch);

        $this->assertNotEmpty($oldInputMatch);
        $this->assertNotEmpty($settingsInputMatch);
        $this->assertStringContainsString('checked', $oldInputMatch[0]);
        $this->assertStringNotContainsString('checked', $settingsInputMatch[0]);
    }

    public function test_storefront_renders_configured_branding_in_navbar_and_footer(): void
    {
        AppSetting::setValue('store_name', 'Glow Sintia Store');
        AppSetting::setValue('store_logo', '/storage/settings/glow-logo.png');
        AppSetting::setValue('pickup_address', 'Jl. Mawar No. 77, Bogor');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Glow Sintia Store');
        $response->assertSee('/storage/settings/glow-logo.png', false);
        $response->assertSee('Jl. Mawar No. 77, Bogor');
        $response->assertDontSee('SINTIA SR12</span>', false);
    }

    public function test_admin_layout_renders_configured_branding_in_sidebar_and_title_area(): void
    {
        AppSetting::setValue('store_name', 'Admin Glow Store');
        AppSetting::setValue('store_logo', '/storage/settings/admin-logo.png');

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('<title>Dashboard - Admin Glow Store</title>', false);
        $response->assertSee('Admin Glow Store');
        $response->assertSee('/storage/settings/admin-logo.png', false);
        $response->assertDontSee('SR12 Sintia</span>', false);
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
