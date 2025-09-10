<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->string('code')->primary();      // Mã quận/huyện, dùng làm khóa chính
            $table->string('name');                 // Tên quận/huyện
            $table->string('province_code');        // Mã tỉnh/thành liên kết
            // Nếu muốn lưu thêm thông tin, có thể thêm các trường khác ở đây
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
