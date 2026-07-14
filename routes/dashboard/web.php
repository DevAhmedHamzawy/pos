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
    Route::resource('clients/{client}/orders/{order}/installments', InstallmentController::class);

    Route::resource('orders', AdminOrderController::class)->except('show');
    Route::get('orders/{order}/products', [AdminOrderController::class, 'products'])->name('orders.products');



