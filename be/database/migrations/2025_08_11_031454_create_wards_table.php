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
        Schema::create('wards', function (Blueprint $table) {
            $table->string('code')->primary();      // Mã phường/xã, dùng làm khóa chính
            $table->string('name');                 // Tên phường/xã
            $table->string('district_code');        // Mã quận/huyện liên kết
            // Nếu muốn lưu thêm thông tin, có thể thêm các trường khác ở đây
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
