<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'landing']);
Route::get('/katalog', [StorefrontController::class, 'catalog']);
Route::get('/produk/{slug}', [StorefrontController::class, 'productDetail']);

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/lupa-password', [AuthController::class, 'showForgotPassword']);
    Route::post('/lupa-password', [AuthController::class, 'resetForgotPassword']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(\App\Http\Middleware\IsCustomer::class)->group(function (): void {
        Route::get('/profil', [ProfileController::class, 'edit']);
        Route::patch('/profil', [ProfileController::class, 'update']);
        Route::patch('/profil/password', [ProfileController::class, 'updatePassword']);

        Route::get('/keranjang', [CartController::class, 'index']);
        Route::get('/keranjang/item/{itemId}', function () {
            return redirect('/keranjang')->with('error', 'Aksi item keranjang harus melalui tombol +, -, atau hapus.');
        });
        Route::post('/keranjang/tambah/{slug}', [CartController::class, 'add']);
        Route::patch('/keranjang/item/{itemId}', [CartController::class, 'updateQuantity']);
        Route::delete('/keranjang/item/{itemId}', [CartController::class, 'remove']);

        Route::post('/checkout', [CheckoutController::class, 'placeOrder']);

        Route::get('/pesanan', [OrderController::class, 'index']);
        Route::get('/pesanan/{orderCode}/konfirmasi', [OrderController::class, 'confirmation']);
        Route::post('/pesanan/{orderCode}/bayar', [OrderController::class, 'markPaid']);
        Route::post('/pesanan/{orderCode}/selesai', [OrderController::class, 'markCompleted']);
        Route::get('/pesanan/{orderCode}', [OrderController::class, 'tracking']);
    });
});

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function (): void {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/notifications', [AdminController::class, 'getNotifications']);
    Route::get('/pesanan', [AdminController::class, 'orders']);
    Route::get('/produk-pesanan', [AdminController::class, 'orders']); // Backward compatibility
    Route::get('/produk', [AdminController::class, 'products']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/pengaturan', [AdminController::class, 'settings']);
    Route::get('/laporan', [AdminController::class, 'analytics']);

    Route::post('/pesanan/{order}/verifikasi-pembayaran', [AdminController::class, 'verifyPayment']);
    Route::post('/pesanan/{order}/reminder-pickup', [AdminController::class, 'sendPickupReminder']);
    Route::post('/pesanan/{order}/status-lanjut', [AdminController::class, 'advanceOrderStatus']);
    Route::post('/pesanan/{order}/ubah-status', [AdminController::class, 'changeOrderStatus'])->name('admin.change-order-status');
    Route::post('/pesanan/bulk-verifikasi', [AdminController::class, 'bulkVerifyPayment'])->name('admin.bulk-verify-payment');
    Route::post('/pesanan/bulk-status', [AdminController::class, 'bulkAdvanceStatus'])->name('admin.bulk-advance-status');

    Route::post('/users', [AdminController::class, 'storeUser']);
    Route::patch('/users/{user}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);

    Route::post('/produk', [AdminController::class, 'storeProduct']);
    Route::patch('/produk/{product}', [AdminController::class, 'updateProduct']);
    Route::post('/produk/{product}/toggle-status', [AdminController::class, 'toggleProductStatus']);

    Route::post('/pengaturan', [AdminController::class, 'saveSettings']);
});

Route::get('/dev/login/{role}', [AuthController::class, 'devLogin']);
