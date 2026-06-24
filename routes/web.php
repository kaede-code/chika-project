<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Customer\CustomerMenuController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\AdminProfileController;

// Redirect legacy routes agar tidak bisa diakses lintas role.
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return Auth::user()->role === 'admin'
        ? redirect('/admin/dashboard')
        : redirect('/customer/menu');
});


Route::get('/menu', function () {
    return Auth::check()
        ? (Auth::user()->role === 'admin' ? redirect('/admin/dashboard') : redirect('/customer/menu'))
        : redirect('/login');
});


Route::get('/dashboard', function () {
    return Auth::check() && Auth::user()->role === 'admin'
        ? redirect('/admin/dashboard')
        : redirect('/customer/menu');
});


Route::get('/riwayat', function () {
    return Auth::check() && Auth::user()->role === 'customer'
        ? redirect('/customer/riwayat')
        : redirect('/admin/orders');
});

Route::get('/profile', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return Auth::user()->role === 'admin'
        ? redirect('/admin/profile')
        : redirect('/customer/profile');
});



// Auth routes
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout']);

// Route role final (hanya prefix ini yang digunakan oleh UI)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('customer.menu');
    Route::get('/riwayat', [CustomerOrderController::class, 'index'])->name('customer.riwayat');
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('customer.profile');

    Route::get('/order', function () {
        return view('page.customer.order');
    })->name('customer.order');


    Route::post('/order/confirm', [\App\Http\Controllers\Customer\CustomerCheckoutController::class, 'confirm'])
        ->name('customer.order.confirm');

    // cart add (menyimpan quantity ke DB)
    Route::post('/cart/add/{product}', [\App\Http\Controllers\Customer\CustomerCartController::class, 'add'])
        ->name('customer.cart.add');


    Route::post('/order/{order}/upload-proof', [\App\Http\Controllers\Customer\PaymentProofController::class, 'store'])
        ->name('customer.order.upload-proof');

    Route::post('/order/{order}/terima', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'terima'])
        ->name('customer.order.terima');

    Route::post('/profile', [\App\Http\Controllers\Customer\CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
});


Route::middleware(['auth', 'role:admin,master_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/data', [AdminDashboardController::class, 'data'])->name('admin.dashboard.data');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');

    Route::resource('/products', ProductManagementController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');

    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.orders.update-status');
});

Route::middleware(['auth', 'role:master_admin'])->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/users/{user}/role', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateRole'])->name('admin.users.role');
    Route::post('/users/{user}/password', [\App\Http\Controllers\Admin\AdminUserController::class, 'updatePassword'])->name('admin.users.password');
});



