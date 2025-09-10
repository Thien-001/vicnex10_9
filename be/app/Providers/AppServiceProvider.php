<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Đăng ký các service.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap bất kỳ service nào.
     */
    public function boot(): void
    {
        // Sử dụng view phân trang tùy chỉnh mặc định cho toàn bộ project
        Paginator::defaultView('vendor.pagination-admin.pagination-admin');

        // Nếu bạn dùng kiểu phân trang đơn giản (trang trước/sau)
        // Paginator::defaultSimpleView('vendor.pagination.pagination-admin');
    }
}
