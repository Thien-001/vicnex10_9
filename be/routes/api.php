<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductAttributeApiController;
use App\Http\Controllers\Api\ProductValueApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\VoucherApiController;
use App\Http\Controllers\Api\PostCategoryApiController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\ProductReviewApiController;
use App\Http\Controllers\Api\PostCommentApi;
use App\Http\Controllers\Api\CourtApi;
use App\Http\Controllers\Api\CourtBookingApi;
use App\Http\Controllers\Api\CartApi;
use App\Http\Controllers\Api\OrderDetailApi;
use App\Http\Controllers\Api\OrderApi;
use App\Http\Controllers\Api\RolesApiController;
use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Api\PopupApiController;
use App\Http\Controllers\Admin\ChatBotController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\VnpayController;
use App\Http\Controllers\Api\LocationApi;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\CommentRatingApiController;
use App\Http\Controllers\Api\ProductRatingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ContactMessageController;

// JWT Auth routes
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'me']);

// Products
Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);
Route::get('/products/slug/{slug}', [ProductApiController::class, 'getProductBySlug']);

// Categories
Route::get('/categories', [CategoryApiController::class, 'index']);
Route::post('/categories', [CategoryApiController::class, 'store']);
Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);

// Attributes
Route::get('/attributes', [ProductAttributeApiController::class, 'index']);
Route::post('/attributes', [ProductAttributeApiController::class, 'store']);
Route::get('/attributes/{id}', [ProductAttributeApiController::class, 'show']);
Route::put('/attributes/{id}', [ProductAttributeApiController::class, 'update']);
Route::delete('/attributes/{id}', [ProductAttributeApiController::class, 'destroy']);

// Values
Route::get('/values', [ProductValueApiController::class, 'index']);
Route::get('/values/{id}', [ProductValueApiController::class, 'show']);
Route::post('/values', [ProductValueApiController::class, 'store']);
Route::put('/values/{id}', [ProductValueApiController::class, 'update']);
Route::delete('/values/{id}', [ProductValueApiController::class, 'destroy']);

// Users
Route::get('/users', [UserApiController::class, 'index']);
Route::get('/users/{id}', [UserApiController::class, 'show']);
Route::post('/users', [UserApiController::class, 'store']);
Route::put('/users/{id}', [UserApiController::class, 'update']);
Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
Route::post('/users/{id}/update-profile', [UserApiController::class, 'updateProfile']);

// Vouchers, Posts, Courts, Orders, etc.
Route::resource('vouchers', VoucherApiController::class);
Route::resource('post_categories', PostCategoryApiController::class);
Route::resource('posts', PostApiController::class);
Route::resource('product_reviews', ProductReviewApiController::class);
Route::resource('courts', CourtApi::class);
Route::resource('court_bookings', CourtBookingApi::class);
Route::resource('carts', CartApi::class);
Route::resource('order_details', OrderDetailApi::class);
Route::apiResource('orders', OrderApi::class);
Route::apiResource('roles', RolesApiController::class);
Route::get('/orders/user/{id}', [OrderApi::class, 'getOrdersByUser']);

// Banner API
Route::get('banners', [BannerApiController::class, 'index']);

// Flash Sale (Admin)
Route::prefix('admin')->group(function () {
    Route::apiResource('flash-sales', \App\Http\Controllers\Admin\FlashSaleController::class);
});
Route::get('flash-sales', [\App\Http\Controllers\Api\FlashSaleApi::class, 'index']);

// Admin Banner (đã sửa lỗi trùng tên route)
Route::prefix('admin')->group(function () {
    Route::resource('banner', BannerController::class);
    Route::get('banner-image/{id}', [BannerController::class, 'image'])->name('admin.banner.image.api');
});

// Popup
Route::get('popup', [PopupApiController::class, 'index']);

// ChatBot
Route::post('/chatbot/badminton', [ProductApiController::class, 'chatbot']);

// Product Variants
Route::get('/product-variants', [ProductVariantController::class, 'index']);
Route::post('/vouchers/check', [VoucherApiController::class, 'check']);
Route::post('/orders/{id}/cancel', [OrderApi::class, 'cancelOrder']);
Route::post('/court-bookings/{id}/cancel', [CourtBookingApi::class, 'cancel']);

// VNPAY Payment
Route::post('/vnpay/create', [VnpayController::class, 'createPayment']);

// Locations
Route::get('/locations', [LocationApi::class, 'index']);
Route::get('/locations/{id}/courts', [LocationApi::class, 'courts']);
Route::get('court_bookings/user/{id}', [CourtBookingApi::class, 'getByUser']);

// Bình luận sản phẩm
Route::get('products/{product}/comments', [CommentApiController::class, 'productComments']);
Route::post('products/{product}/comments', [CommentApiController::class, 'storeProductComment']);

// Bình luận bài viết
Route::get('posts/{post}/comments', [CommentApiController::class, 'postComments']);
Route::post('posts/{post}/comments', [CommentApiController::class, 'storePostComment']);

// Đánh giá bình luận (like/dislike)
Route::post('comments/{id}/rate', [CommentRatingApiController::class, 'store']);
Route::get('comments/{id}/rate', [CommentRatingApiController::class, 'count']);

// Đánh giá sản phẩm (rating) - CHUẨN RESTful, hỗ trợ ảnh
Route::get('/products/{productId}/ratings', [ProductRatingController::class, 'list']);   // Lấy danh sách đánh giá + ảnh
Route::post('/products/{productId}/ratings', [ProductRatingController::class, 'store']); // Gửi đánh giá kèm ảnh

// LẤY TOP ĐÁNH GIÁ CAO NHẤT TOÀN SHOP
Route::get('/top-reviews', [ProductRatingController::class, 'topReviews']);

// Notifications
Route::get('/notifications', [NotificationController::class, 'index']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-read', [NotificationController::class, 'markManyAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
Route::post('/notifications/delete-many', [NotificationController::class, 'destroyMany']);

// Contact Messages
Route::post('/contact', [ContactMessageController::class, 'store']);
Route::get('/contacts', [ContactMessageController::class, 'index']);

// Expert Reviews
Route::get('/expert-reviews', [\App\Http\Controllers\Api\ExpertReviewApiController::class, 'index']);

// Kiểm tra đã mua sản phẩm
Route::get('orders/check-purchased', [OrderApi::class, 'checkPurchased']);