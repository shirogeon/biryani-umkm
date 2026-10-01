<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Facing Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $featuredProducts = Product::available()->featured()->take(6)->get();
    $allProducts = Product::available()->take(8)->get();
    $storeName = StoreSetting::get('store_name', 'Dapur Nasi Biryani Berkah');
    $storeTagline = StoreSetting::get('store_tagline', 'Rasa Rempah Autentik, Beras Basmati Premium Pilihan');
    return view('customer.home', compact('featuredProducts', 'allProducts', 'storeName', 'storeTagline'));
})->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{slug}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/cart', function () {
    return view('customer.cart');
})->name('cart.index');

Route::get('/checkout', function () {
    $storePhone = StoreSetting::get('store_phone', '6281298765432');
    $shippingFlatRate = StoreSetting::get('shipping_flat_rate', 10000);
    $bankName = StoreSetting::get('bank_name', 'BCA');
    $bankAccount = StoreSetting::get('bank_account_number', '8735019281');
    $bankHolder = StoreSetting::get('bank_account_holder', 'Dapur Biryani Berkah');
    $qrisMerchant = StoreSetting::get('qris_merchant_name', 'DAPUR BIRYANI BERKAH QRIS');
    return view('customer.checkout', compact('storePhone', 'shippingFlatRate', 'bankName', 'bankAccount', 'bankHolder', 'qrisMerchant'));
})->name('checkout.index');

Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');
Route::get('/pesanan-berhasil/{code}', [OrderController::class, 'success'])->name('order.success');
Route::get('/lacak-pesanan/{code?}', [OrderController::class, 'track'])->name('order.track');
Route::get('/struk/{code}', [OrderController::class, 'receipt'])->name('order.receipt');


/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Orders
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{id}/receipt', [OrderManagementController::class, 'receipt'])->name('orders.receipt');
    Route::delete('/orders/{id}', [OrderManagementController::class, 'destroy'])->name('orders.destroy');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});
