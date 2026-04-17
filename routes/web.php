<?php

use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('pages.landing');
});

Route::get('/login', function () {
    return view('pages.auth.login');
});

Route::get('/register', function () {
    return view('pages.auth.register');
});

Route::get('/katalog', function () {
    return view('pages.catalog');
});

Route::get('/produk/{slug}', function (string $slug) {
    return view('pages.product-detail');
});

// ==================== AUTH ROUTES (simulated) ====================
Route::get('/keranjang', function () {
    return view('pages.cart-checkout');
});

Route::get('/pesanan/{id}/konfirmasi', function (string $id) {
    return view('pages.order-confirmation');
});

Route::get('/pesanan/{id}', function (string $id) {
    return view('pages.order-tracking');
});

// ==================== ADMIN ROUTES ====================
Route::get('/admin', function () {
    return view('pages.admin.dashboard');
});

Route::get('/admin/produk-pesanan', function () {
    return view('pages.admin.products-orders');
});

Route::get('/admin/laporan', function () {
    return view('pages.admin.analytics');
});

// ==================== DEV BYPASS LOGIN ====================
Route::get('/dev/login/{role}', function (string $role) {
    session(['dev_role' => $role]); // 'admin' or 'customer'
    return redirect($role === 'admin' ? '/admin' : '/katalog');
});

Route::get('/dev/logout', function () {
    session()->forget('dev_role');
    return redirect('/login');
});
