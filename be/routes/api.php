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
use App\Http\Controllers\Auth\RegisterController;

// THÊM ROUTE DEBUG - ĐẶT Ở ĐẦU TRƯỚC CÁC ROUTE KHÁC
Route::get('/debug-variants', function() {
    try {
        // Test direct query
        $directVariants = \DB::table('product_variants')->where('Product_ID', 61)->get();
        
        // Test model query
        $product = \App\Models\Product::find(61);
        $modelVariants = $product ? $product->variants : null;
        
        // Test with eager loading
        $productWithVariants = \App\Models\Product::with('variants')->find(61);
        
        return response()->json([
            'direct_db_count' => $directVariants->count(),
            'direct_db_data' => $directVariants->toArray(),
            'model_variants_count' => $modelVariants ? $modelVariants->count() : 0,
            'model_variants_data' => $modelVariants ? $modelVariants->toArray() : null,
            'eager_loaded_count' => $productWithVariants && $productWithVariants->variants ? $productWithVariants->variants->count() : 0,
            'eager_loaded_data' => $productWithVariants && $productWithVariants->variants ? $productWithVariants->variants->toArray() : null,
            'relation_loaded' => $productWithVariants ? $productWithVariants->relationLoaded('variants') : false
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

// Products - ĐẶT THEO THỨ TỰ ƯU TIÊN
Route::get('/products/search', [ProductApiController::class, 'search']); // SEARCH TRƯỚC
Route::get('/products/{id}', [ProductApiController::class, 'show']);
Route::get('/products/slug/{slug}', [ProductApiController::class, 'getProductBySlug']);
Route::get('/products', [ProductApiController::class, 'index']); // INDEX SAU

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
Route::post('/users/{id}', [UserApiController::class, 'update']);
Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
Route::put('users/{user}', [UserApiController::class, 'update']);
Route::patch('users/{user}', [UserApiController::class, 'update']);
Route::post('/users/{id}/update-profile', [UserApiController::class, 'updateProfile']);

// Login
Route::post('/login', [UserApiController::class, 'login']);

// Đăng ký tài khoản (API)
Route::post('/register', [RegisterController::class, 'register']);

// Vouchers, Posts, Courts, Orders, etc.
Route::resource('vouchers', VoucherApiController::class);
Route::resource('post_categories', PostCategoryApiController::class);
Route::resource('posts', PostApiController::class);
Route::resource('product_reviews', ProductReviewApiController::class);
Route::resource('comments', PostCommentApi::class);
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

// Admin Banner
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banner', BannerController::class);
    Route::get('banner-image/{id}', [BannerController::class, 'image'])->name('banner.image');
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
Route::get('/top-reviews', [ProductRatingController::class, 'topReviews']); // <-- Thêm dòng này

// Notifications
Route::get('/notifications', [NotificationController::class, 'index']); // Lấy danh sách thông báo
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']); // Đánh dấu đã đọc 1 thông báo
Route::post('/notifications/mark-read', [NotificationController::class, 'markManyAsRead']); // Đánh dấu đã đọc nhiều thông báo
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']); // Xóa 1 thông báo
Route::post('/notifications/delete-many', [NotificationController::class, 'destroyMany']); // Xóa nhiều thông báo
Route::post('/notifications', [NotificationController::class, 'store']); // Tạo mới thông báo

// Đánh dấu tất cả thông báo là đã đọc cho user
Route::post('/notifications/read-all', [NotificationController::class, 'readAll']); // SỬA ĐÚNG CONTROLLER

// Contact Messages
Route::post('/contact', [ContactMessageController::class, 'store']);
Route::get('/contacts', [ContactMessageController::class, 'index']); // (tuỳ chọn, cho admin xem tất cả liên hệ)

// Expert Reviews
Route::get('/expert-reviews', [\App\Http\Controllers\Api\ExpertReviewApiController::class, 'index']);


Route::prefix('product-lines')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\ProductLineApiController::class, 'index']);
    Route::get('/brand/{brand}', [App\Http\Controllers\Api\ProductLineApiController::class, 'getByBrand']);
    Route::get('/{id}', [App\Http\Controllers\Api\ProductLineApiController::class, 'show']);
});