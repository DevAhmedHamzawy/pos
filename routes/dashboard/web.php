<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Client\OrderController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InstallmentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\SpacePartController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');


    Route::resource('brands', BrandController::class)->except('show');

    Route::resource('users', UserController::class)->except('show');

    Route::resource('categories', CategoryController::class)->except('show');

    Route::resource('products', ProductController::class)->except('show');
    Route::get('products/{product}/change_qty', [ProductController::class, 'changeQty'])->name('products.changeQty');
    Route::post('products/{product}/change_qty', [ProductController::class, 'updateQty'])->name('products.changeQty');
    Route::get('products/{product}/activity_log', [ProductController::class, 'activityLog'])->name('products.activityLog');

    Route::resource('clients', ClientController::class)->except('show');
    Route::resource('clients.orders', OrderController::class)->except('show');
    Route::get('product_search', [OrderController::class, 'productSearch'])->name('product_search');
    Route::get('product_qty', [OrderController::class, 'productQty'])->name('product_qty');
    Route::resource('clients/{client}/orders/{order}/installments', InstallmentController::class);

    Route::resource('orders', AdminOrderController::class)->except('show');
    Route::get('orders/{order}/products', [AdminOrderController::class, 'products'])->name('orders.products');
    Route::get('/orders/{order}/receipt', [AdminOrderController::class, 'receipt'])->name('orders.receipt');

    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('space_parts', SpacePartController::class);



