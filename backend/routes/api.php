<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OptionGroupController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\RestaurantSettingsController;
use App\Http\Controllers\Admin\WhatsappSettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\Menu\OrderController;
use App\Http\Controllers\Menu\PaymentController;

Route::prefix('v1')->group(function () {
    // Admin auth
    Route::post('/admin/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/admin/logout', [AuthController::class, 'logout']);
        Route::get('/admin/me', [AuthController::class, 'me']);

        // Admin resources
        Route::apiResource('/admin/categories', CategoryController::class);
        Route::apiResource('/admin/products', ProductController::class);
        Route::apiResource('/admin/option-groups', OptionGroupController::class);
        Route::apiResource('/admin/coupons', CouponController::class);
        Route::apiResource('/admin/users', UserController::class);

        Route::get('/admin/orders', [AdminOrderController::class, 'index']);
        Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show']);
        Route::patch('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);

        Route::get('/admin/settings/restaurant', [RestaurantSettingsController::class, 'show']);
        Route::post('/admin/settings/restaurant', [RestaurantSettingsController::class, 'update']);
        Route::get('/admin/settings/whatsapp', [WhatsappSettingsController::class, 'show']);
        Route::post('/admin/settings/whatsapp', [WhatsappSettingsController::class, 'update']);
    });

    // Customer menu
    Route::get('/menu/{restaurant:slug}/categories', [MenuController::class, 'categories']);
    Route::get('/menu/{restaurant:slug}/products', [MenuController::class, 'products']);
    Route::get('/menu/{restaurant:slug}/product/{product}', [MenuController::class, 'product']);

    // Checkout
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order:number}', [OrderController::class, 'show']);

    // Payments
    Route::post('/payments/paymob/init', [PaymentController::class, 'initPaymob']);
    Route::post('/payments/paymob/webhook', [PaymentController::class, 'handlePaymobWebhook']);
    Route::post('/payments/manual/confirm', [PaymentController::class, 'manualConfirm']);
});
