<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductValueController;
use App\Http\Controllers\Admin\ProductVariantValueController;
use App\Http\Controllers\Admin\CategoryAttributeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderDetailController;
use App\Http\Controllers\Admin\ProductStatisticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\CourtBookingController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostCommentController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\FlashSaleController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\ChatBotController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ExpertController;
use App\Http\Controllers\Admin\ExpertReviewController;
use App\Http\Controllers\Auth\LoginController;

// Trang mặc định chuyển hướng đến /admin
Route::redirect('/', '/admin');

// Trang dashboard admin
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/admin/dashboard/filter', [DashboardController::class, 'filter'])
    ->name('admin.dashboard.filter');

// Nhóm route ADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
    Route::resource('orders', OrderController::class);
    Route::delete('order-details/{id}', [OrderDetailController::class, 'destroy'])->name('order-details.destroy');

    Route::get('/product-statistics', [ProductStatisticsController::class, 'index'])->name('product-statistics');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product_attributes', ProductAttributeController::class);
    Route::resource('variants', ProductVariantController::class);
    Route::resource('product_values', ProductValueController::class);
    Route::resource('product_variant_values', ProductVariantValueController::class);

    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);

    Route::get('category-attribute/create', [CategoryAttributeController::class, 'create'])->name('category-attribute.create');
    Route::post('category-attribute/store', [CategoryAttributeController::class, 'store'])->name('category-attribute.store');

    Route::get('comments', fn () => view('admin.comments.index'))->name('comments.index');

    Route::resource('courts', CourtController::class);
    Route::resource('bookings', CourtBookingController::class);

    Route::resource('vouchers', VoucherController::class);

    Route::resource('posts', PostController::class);

    Route::resource('post_categories', PostCategoryController::class);

    Route::prefix('comments')->name('comments.')->group(function () {
        Route::resource('product', \App\Http\Controllers\Admin\ProductReviewController::class);
        Route::resource('post', \App\Http\Controllers\Admin\PostCommentController::class); // THÊM DÒNG NÀY
    });

    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('revenue', [StatisticsController::class, 'indexrevenue'])->name('revenue');
        Route::get('order', [StatisticsController::class, 'indexorder'])->name('order');
        Route::get('booking', [StatisticsController::class, 'indexbooking'])->name('booking');
        Route::get('product', [StatisticsController::class, 'indexproduct'])->name('product');
    });

    Route::delete('products/delete-image/{id}', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])
        ->name('products.deleteImage');

    // Flash Sale routes
    Route::resource('flash-sales', FlashSaleController::class);

    // Banner routes
    Route::resource('banner', BannerController::class);
    Route::get('banner-image/{id}', [BannerController::class, 'image'])->name('banner.image');

    // Popup routes
    Route::resource('popup', PopupController::class);

    // Thêm 2 route xác nhận và hủy đơn hàng vào nhóm admin (đặt sau resource orders)
    Route::post('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Thêm route xác nhận đã giao hàng
    Route::post('orders/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');

    // Thêm route chuyển trạng thái Đang giao hàng
    Route::post('orders/{order}/shipping', [OrderController::class, 'shipping'])->name('orders.shipping');

    Route::resource('locations', \App\Http\Controllers\Admin\LocationController::class);

    Route::get('contact', [ContactMessageController::class, 'index'])->name('contact.index');
    Route::get('contact/{id}/edit', [ContactMessageController::class, 'edit'])->name('contact.edit');
    Route::put('contact/{id}', [ContactMessageController::class, 'update'])->name('contact.update');
    Route::delete('contact/{id}', [ContactMessageController::class, 'destroy'])->name('contact.destroy');

    // Expert routes
    Route::resource('experts', ExpertController::class);

    // Expert Review routes
    Route::resource('expert-reviews', ExpertReviewController::class);

    Route::patch('categories/{id}/toggle', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');
});

Route::get('popup-image/{filename}', function ($filename) {
    $path = storage_path('app/public/uploads/popups/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('popup.image');

Route::post('chatbot/badminton', [ChatBotController::class, 'chatBadminton']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login'); // hoặc về trang
})->name('logout');

// Route cho API Notification
Route::get('/notifications', [NotificationController::class, 'index']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

// Xác nhận lịch đặt sân
Route::post('bookings/{id}/confirm', [CourtBookingController::class, 'confirm'])->name('bookings.confirm');
Route::post('/admin/bookings/{booking}/confirm', [\App\Http\Controllers\Admin\CourtBookingController::class, 'confirm'])
    ->name('admin.bookings.confirm');

// Route hiển thị form đăng nhập admin
Route::get('/admin/login', function () {
    return view('admin.loginadmin.index');
})->name('admin.login');

// Route xử lý đăng nhập admin
Route::post('/admin/login', [App\Http\Controllers\Admin\AdminLoginController::class, 'login'])->name('admin.login.submit');

// Route xử lý đăng nhập cho user (React gọi)
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// THÊM ROUTE NÀY VÀO CUỐI FILE:
Route::get('/admin/products/product-lines/brand/{brandId}', [App\Http\Controllers\Admin\ProductController::class, 'getProductLinesByBrand']);



