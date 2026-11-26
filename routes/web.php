<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ShippingController as AdminShippingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart (Guest & Customer)
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::patch('/item/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/item/{item}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/kosongkan', [CartController::class, 'clear'])->name('clear');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Customer Routes
Route::middleware(['auth', 'role:customer'])->name('customer.')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/alamat', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/alamat', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/alamat/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::patch('/alamat/{address}/utama', [AddressController::class, 'setDefault'])->name('addresses.default');
    Route::delete('/alamat/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/pesanan/{order}/bayar', [CustomerOrderController::class, 'uploadPayment'])->name('orders.payment');
    Route::patch('/pesanan/{order}/selesai', [CustomerOrderController::class, 'confirmReceived'])->name('orders.confirm');
    Route::patch('/pesanan/{order}/batal', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/pesanan/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');

    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::patch('customers/{customer}/toggle', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');

    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    Route::get('shippings', [AdminShippingController::class, 'index'])->name('shippings.index');
    Route::patch('shippings/{shipping}', [AdminShippingController::class, 'update'])->name('shippings.update');

    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
